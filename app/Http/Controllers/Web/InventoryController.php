<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Services\InventoryLedgerService;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    protected InventoryLedgerService $inventoryService;

    public function __construct(InventoryLedgerService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $user = Auth::user();
        $query = InventoryLog::with(['product.merchant', 'user']);

        if ($user->hasRole('pelaku_umkm') && !$user->hasRole('super_admin')) {
            $merchantId = $user->umkm?->id ?? 0;
            $query->whereHas('product', fn($q) => $q->where('umkm_merchant_id', $merchantId));
        }

        $logs = $query->latest('id')->paginate(20);
        return view('admin.inventory.index', compact('logs'));
    }

    public function storeAdjustment(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:IN,OUT,ADJUSTMENT',
            'quantity' => 'required|integer|min:1',
            'notes' => 'required|string|max:255',
        ]);

        try {
            $this->inventoryService->recordStockMutation(
                $request->product_id,
                $request->type,
                $request->quantity,
                'MANUAL_ADJUSTMENT',
                null,
                $request->notes,
                Auth::id()
            );

            AuditTrailService::log('STOCK_ADJUSTMENT', 'Product', $request->product_id, null, ['type' => $request->type, 'qty' => $request->quantity]);

            return back()->with('success', 'Mutasi stok berhasil dicatat ke buku persediaan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
