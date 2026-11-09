<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderProcessingService
{
    protected InventoryLedgerService $inventoryService;
    protected FinancialBookkeepingService $financialService;

    public function __construct(
        InventoryLedgerService $inventoryService,
        FinancialBookkeepingService $financialService
    ) {
        $this->inventoryService = $inventoryService;
        $this->financialService = $financialService;
    }

    public function createOrder(array $data, int $userId): Order
    {
        return DB::transaction(function () use ($data, $userId) {
            // 1. Verify stock availability
            if (!$this->inventoryService->verifyStockAvailability($data['items'])) {
                throw new \RuntimeException('Satu atau lebih produk tidak memiliki stok yang mencukupi.');
            }

            // 2. Calculate total amount
            $totalAmount = 0;
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $totalAmount += $product->price * $item['quantity'];
            }

            // 3. Create Order
            $invoiceNo = 'INV-JMI-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $order = Order::create([
                'invoice_no' => $invoiceNo,
                'user_id' => $userId,
                'umkm_merchant_id' => $data['umkm_merchant_id'],
                'total_amount' => $totalAmount,
                'status' => 'PENDING',
                'delivery_option' => $data['delivery_option'] ?? 'PICKUP',
                'delivery_notes' => $data['delivery_notes'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // 4. Create Order Items
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price_at_purchase' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity'],
                ]);
            }

            // 5. Create Simulation Payment Record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $data['payment_method'] ?? 'SIMULATION_QRIS',
                'payment_status' => 'UNPAID',
                'simulation_ref_no' => 'SIM-REF-' . date('YmdHis') . '-' . rand(100, 999),
                'is_demo_gateway' => true,
            ]);

            return $order->load(['items.product', 'payment']);
        });
    }

    public function confirmSimulationPayment(int $orderId, int $actorUserId): Order
    {
        return DB::transaction(function () use ($orderId, $actorUserId) {
            $order = Order::lockForUpdate()->findOrFail($orderId);

            if ($order->status !== 'PENDING') {
                throw new \LogicException('Pesanan ini sudah dibayar atau dibatalkan.');
            }

            // 1. Deduct stock for each item
            foreach ($order->items as $item) {
                $this->inventoryService->recordStockMutation(
                    $item->product_id,
                    'OUT',
                    $item->quantity,
                    'ORDER_PURCHASE',
                    $order->id,
                    "Pengurangan stok untuk pesanan #{$order->invoice_no}",
                    $actorUserId
                );
            }

            // 2. Mark order and payment as PAID
            $order->status = 'PAID';
            $order->save();

            $payment = $order->payment;
            if ($payment) {
                $payment->payment_status = 'PAID';
                $payment->paid_at = now();
                $payment->save();
            }

            // 3. Automatically record Income into the UMKM's business cash book
            $this->financialService->recordSaleIncome($order, $actorUserId);

            return $order;
        });
    }

    public function cancelOrder(int $orderId, int $actorUserId): Order
    {
        return DB::transaction(function () use ($orderId, $actorUserId) {
            $order = Order::lockForUpdate()->findOrFail($orderId);

            if ($order->status === 'CANCELLED') {
                return $order;
            }

            // If it was already paid, rollback stock
            if ($order->status === 'PAID' || $order->status === 'PROCESSING') {
                foreach ($order->items as $item) {
                    $this->inventoryService->recordStockMutation(
                        $item->product_id,
                        'IN',
                        $item->quantity,
                        'ORDER_CANCEL_RESTOCK',
                        $order->id,
                        "Pengembalian stok pesanan dibatalkan #{$order->invoice_no}",
                        $actorUserId
                    );
                }
            }

            $order->status = 'CANCELLED';
            $order->save();

            return $order;
        });
    }
}
