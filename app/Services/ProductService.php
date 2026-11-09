<?php

namespace App\Services;

use App\Models\Product;
use App\Models\InventoryLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function getActiveCatalog(?int $categoryId = null, ?string $search = null)
    {
        $query = Product::with(['merchant', 'category'])
            ->where('is_active', true)
            ->whereHas('merchant', function ($q) {
                $q->where('verification_status', 'VERIFIED');
            });

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->latest()->get();
    }

    public function createProduct(array $data, int $userId): Product
    {
        // Enforce boundary check: price must be >= 0
        if ($data['price'] < 0) {
            throw new \InvalidArgumentException('Harga produk tidak boleh bernilai negatif.');
        }

        return DB::transaction(function () use ($data, $userId) {
            $product = Product::create([
                'umkm_merchant_id' => $data['umkm_merchant_id'],
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . uniqid(),
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'] ?? 0,
                'low_stock_threshold' => $data['low_stock_threshold'] ?? 5,
                'unit' => $data['unit'] ?? 'pcs',
                'is_active' => true,
            ]);

            // Initial stock entry if stock > 0
            if ($product->stock > 0) {
                InventoryLog::create([
                    'product_id' => $product->id,
                    'type' => 'IN',
                    'quantity' => $product->stock,
                    'previous_stock' => 0,
                    'current_stock' => $product->stock,
                    'reference_type' => 'INITIAL_STOCK',
                    'notes' => 'Stok awal penambahan produk',
                    'created_by' => $userId,
                ]);
            }

            return $product;
        });
    }
}
