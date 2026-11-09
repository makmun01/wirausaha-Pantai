<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderProcessingService;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderProcessingService $orderService;

    public function __construct(OrderProcessingService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $user = Auth::user();
        $query = Order::with(['merchant', 'user', 'items.product', 'payment']);

        if ($user->hasRole('pelaku_umkm') && !$user->hasRole('super_admin')) {
            $merchantId = $user->umkm?->id ?? 0;
            $query->where('umkm_merchant_id', $merchantId);
        }

        $orders = $query->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:PENDING,PAID,PROCESSING,READY_FOR_PICKUP,COMPLETED,CANCELLED',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;

        if ($request->status === 'CANCELLED') {
            $this->orderService->cancelOrder($id, Auth::id());
        } elseif ($request->status === 'PAID' && $oldStatus === 'PENDING') {
            $this->orderService->confirmSimulationPayment($id, Auth::id());
        } else {
            $order->status = $request->status;
            $order->save();
        }

        AuditTrailService::log('ORDER_STATUS_UPDATE', 'Order', $id, ['status' => $oldStatus], ['status' => $request->status]);

        return back()->with('success', "Status pesanan #{$order->invoice_no} berhasil diperbarui menjadi {$request->status}!");
    }
}
