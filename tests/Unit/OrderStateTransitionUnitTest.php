<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\FinancialAccount;
use App\Models\FinancialCategory;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\UmkmMerchant;
use App\Services\FinancialBookkeepingService;
use App\Services\InventoryLedgerService;
use App\Services\OrderProcessingService;
use App\Services\ProductService;
use App\Services\UmkmService;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;
use Tests\Assert;

class OrderStateTransitionUnitTest extends TestCase
{
    private InventoryLedgerService $inventoryService;
    private FinancialBookkeepingService $financialService;
    private OrderProcessingService $orderService;
    private ProductService $productService;
    private UmkmService $umkmService;

    protected function setUp(): void
    {
        Model::resetStore();
        $this->inventoryService = new InventoryLedgerService();
        $this->financialService = new FinancialBookkeepingService();
        $this->orderService = new OrderProcessingService($this->inventoryService, $this->financialService);
        $this->productService = new ProductService();
        $this->umkmService = new UmkmService();

        UmkmMerchant::create([
            'user_id' => 3,
            'business_name' => 'Sentra Olahan Lorjuk Bu Maryam',
            'owner_name' => 'Ibu Maryam',
            'category' => 'KULINER',
            'address' => 'Pantai Jumiang',
            'phone_number' => '+6281234567892',
            'verification_status' => 'VERIFIED',
        ]);

        FinancialAccount::create([
            'umkm_merchant_id' => 1,
            'account_name' => 'Kas Operasional Usaha Lorjuk',
            'account_type' => 'BUSINESS',
            'initial_balance' => 1000000.0,
            'current_balance' => 1000000.0,
        ]);

        FinancialCategory::create([
            'name' => 'Penjualan Online Marketplace',
            'type' => 'INCOME',
            'is_system_default' => true,
        ]);

        Product::create([
            'umkm_merchant_id' => 1,
            'category_id' => 1,
            'name' => 'Rengginang Lorjuk Super',
            'slug' => 'rengginang-lorjuk-super',
            'price' => 35000.0,
            'stock' => 20,
            'low_stock_threshold' => 5,
            'unit' => 'bungkus',
            'is_active' => true,
        ]);
    }

    public function test_full_valid_order_fsm_and_cross_module_integration(): void
    {
        $order = $this->orderService->createOrder([
            'umkm_merchant_id' => 1,
            'items' => [['product_id' => 1, 'quantity' => 4]],
            'payment_method' => 'SIMULATION_QRIS',
        ], 6);

        Assert::same('PENDING', $order->status);
        Assert::same(140000.0, (float) $order->total_amount);

        // PENDING -> PAID triggers stock OUT & BUSINESS account income
        $paidOrder = $this->orderService->transitionStatus($order->id, 'PAID', 6);
        $product = Product::find(1);
        $account = FinancialAccount::find(1);
        $logs = InventoryLog::where('product_id', 1)->get();

        Assert::same('PAID', $paidOrder->status);
        Assert::same(16, (int) $product->stock);
        Assert::same(1140000.0, (float) $account->current_balance);
        Assert::same(1, $logs->count());

        // PAID -> PROCESSING -> READY_FOR_PICKUP -> COMPLETED
        $processing = $this->orderService->transitionStatus($order->id, 'PROCESSING', 3);
        Assert::same('PROCESSING', $processing->status);

        $ready = $this->orderService->transitionStatus($order->id, 'READY_FOR_PICKUP', 3);
        Assert::same('READY_FOR_PICKUP', $ready->status);

        $completed = $this->orderService->transitionStatus($order->id, 'COMPLETED', 3);
        Assert::same('COMPLETED', $completed->status);
    }

    public function test_cancel_paid_order_rolls_back_inventory(): void
    {
        $order = $this->orderService->createOrder([
            'umkm_merchant_id' => 1,
            'items' => [['product_id' => 1, 'quantity' => 5]],
        ], 6);

        $this->orderService->transitionStatus($order->id, 'PAID', 6);
        Assert::same(15, (int) Product::find(1)->stock);

        $cancelled = $this->orderService->transitionStatus($order->id, 'CANCELLED', 3);
        Assert::same('CANCELLED', $cancelled->status);
        Assert::same(20, (int) Product::find(1)->stock, 'Pembatalan order PAID wajib mengembalikan stok produk.');
    }

    public function test_rejects_invalid_fsm_transitions_and_completed_cancellation(): void
    {
        $order = $this->orderService->createOrder([
            'umkm_merchant_id' => 1,
            'items' => [['product_id' => 1, 'quantity' => 2]],
        ], 6);

        // Cannot jump directly from PENDING -> COMPLETED
        Assert::false($this->orderService->isValidTransition('PENDING', 'COMPLETED'));
        Assert::throws(\InvalidArgumentException::class, function () use ($order): void {
            $this->orderService->transitionStatus($order->id, 'COMPLETED', 3);
        });

        $this->orderService->transitionStatus($order->id, 'PAID', 6);
        $this->orderService->transitionStatus($order->id, 'PROCESSING', 3);
        $this->orderService->transitionStatus($order->id, 'READY_FOR_PICKUP', 3);
        $this->orderService->transitionStatus($order->id, 'COMPLETED', 3);

        Assert::throws(\InvalidArgumentException::class, function () use ($order): void {
            $this->orderService->transitionStatus($order->id, 'CANCELLED', 3);
        });
        Assert::throws(\LogicException::class, function () use ($order): void {
            $this->orderService->cancelOrder($order->id, 3);
        });
    }

    public function test_umkm_verification_fsm_and_product_price_bva(): void
    {
        $pendingUmkm = UmkmMerchant::create([
            'user_id' => 9,
            'business_name' => 'Olahan Lorjuk Berkah',
            'owner_name' => 'Ibu Siti',
            'category' => 'KULINER',
            'address' => 'Desa Tanjung',
            'phone_number' => '+6281234567899',
            'verification_status' => 'PENDING',
        ]);

        Assert::true($this->umkmService->updateVerificationStatus($pendingUmkm->id, 'VERIFIED'));
        Assert::same('VERIFIED', UmkmMerchant::find($pendingUmkm->id)->verification_status);

        // Cannot revert VERIFIED directly back to PENDING
        Assert::throws(\InvalidArgumentException::class, function () use ($pendingUmkm): void {
            $this->umkmService->updateVerificationStatus($pendingUmkm->id, 'PENDING');
        });

        // Product Price BVA: -1 rejected, 0 accepted
        Assert::throws(\InvalidArgumentException::class, function (): void {
            $this->productService->createProduct([
                'umkm_merchant_id' => 1,
                'category_id' => 1,
                'name' => 'Produk Minus',
                'price' => -1,
                'stock' => 10,
            ], 3);
        });

        $freeSample = $this->productService->createProduct([
            'umkm_merchant_id' => 1,
            'category_id' => 1,
            'name' => 'Tester Lorjuk Gratis',
            'price' => 0,
            'stock' => 5,
            'low_stock_threshold' => 5,
        ], 3);

        Assert::same(0.0, (float) $freeSample->price);
        Assert::same(1, $this->inventoryService->getLowStockAlerts(1)->count());
    }
}
