<?php

namespace App\Services;

use App\Models\Product;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\DB;

class InventoryLedgerService
{
    public function verifyStockAvailability(array $items): bool
    {
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product || $product->stock < $item['quantity']) {
                return false;
            }
        }
        return true;
    }

    public function recordStockMutation(int $productId, string $type, int $quantity, string $refType, ?int $refId, string $notes, int $userId): InventoryLog
    {
        return DB::transaction(function () use ($productId, $type, $quantity, $refType, $refId, $notes, $userId) {
            $product = Product::lockForUpdate()->findOrFail($productId);
            $previousStock = $product->stock;

            if ($type === 'IN') {
                $newStock = $previousStock + $quantity;
            } elseif ($type === 'OUT') {
                if ($previousStock < $quantity) {
                    throw new \RuntimeException("Stok tidak mencukupi untuk pengurangan (tersedia: {$previousStock}, diminta: {$quantity}).");
                }
                $newStock = $previousStock - $quantity;
            } else { // ADJUSTMENT
                $newStock = $quantity;
            }

            $product->stock = $newStock;
            $product->save();

            return InventoryLog::create([
                'product_id' => $productId,
                'type' => $type,
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'current_stock' => $newStock,
                'reference_type' => $refType,
                'reference_id' => $refId,
                'notes' => $notes,
                'created_by' => $userId,
            ]);
        });
    }

    public function getLowStockAlerts(int $merchantId)
    {
        return Product::where('umkm_merchant_id', $merchantId)
            ->whereColumn('stock', '<=', 'low_stock_threshold')
            ->get();
    }
}
