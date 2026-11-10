<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Product;
use App\Services\FinancialBookkeepingService;
use App\Services\OrderProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiV1Controller extends Controller
{
    public function destinations()
    {
        $destinations = Destination::with(['attractions', 'facilities'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $destinations,
            'meta' => [
                'is_demo_data' => true,
                'location' => 'Desa Tanjung, Kec. Pademawu, Kab. Pamekasan',
            ],
        ]);
    }

    public function products(Request $request)
    {
        $query = Product::with(['merchant', 'category'])->where('is_active', true);
        if ($request->filled('category_id')) {
            $query->where('category_id', (int) $request->query('category_id'));
        }
        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    public function productShow(int $id)
    {
        $product = Product::with(['merchant', 'category'])->find($id);
        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan.'], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $product,
        ]);
    }

    public function simulateOrder(Request $request, OrderProcessingService $orderService)
    {
        $validated = $request->validate([
            'umkm_merchant_id' => 'required|integer',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'nullable|in:SIMULATION_QRIS,SIMULATION_VA,CASH_ON_LOCATION',
        ]);

        try {
            $order = $orderService->createOrder($validated, (int) Auth::id());
            return response()->json([
                'status' => 'success',
                'data' => $order,
            ], 201);
        } catch (\RuntimeException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    public function simulatePayment(Request $request, OrderProcessingService $orderService)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer',
        ]);

        try {
            $order = $orderService->confirmSimulationPayment((int) $validated['order_id'], (int) Auth::id());
            return response()->json([
                'status' => 'success',
                'data' => $order,
            ]);
        } catch (\LogicException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    public function myFinances(FinancialBookkeepingService $financialService)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('pelaku_umkm')) {
            return response()->json(['status' => 'error', 'message' => 'Hanya Pelaku UMKM yang dapat mengakses buku kas usaha.'], 403);
        }

        $merchant = $user->umkm;
        if (!$merchant) {
            return response()->json(['status' => 'error', 'message' => 'Profil UMKM belum terdaftar.'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'accounts' => $financialService->getMerchantAccounts($merchant->id),
                'profit_loss' => $financialService->getProfitAndLoss($merchant->id),
            ],
        ]);
    }
}
