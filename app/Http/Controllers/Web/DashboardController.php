<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\UmkmMerchant;
use App\Models\FinancialTransaction;
use App\Services\ExecutiveAnalyticsService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected ExecutiveAnalyticsService $analyticsService;

    public function __construct(ExecutiveAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            $kpi = $this->analyticsService->getMacroKPIs();
            $recentOrders = Order::with(['merchant', 'user'])->latest()->take(5)->get();
            $recentUmkm = UmkmMerchant::latest()->take(5)->get();
            return view('admin.dashboard.super_admin', compact('kpi', 'recentOrders', 'recentUmkm'));
        }

        if ($user->hasRole('pengelola_wisata')) {
            $totalVisitors = Reservation::sum('visitor_count');
            $activeReservations = Reservation::where('status', 'CONFIRMED')->count();
            $recentReservations = Reservation::with('user')->latest()->take(5)->get();
            return view('admin.dashboard.pengelola', compact('totalVisitors', 'activeReservations', 'recentReservations'));
        }

        if ($user->hasRole('pelaku_umkm')) {
            $merchant = $user->umkm;
            if (!$merchant) {
                // If the user has the role but hasn't created a merchant profile yet, create or link a fallback
                $merchant = UmkmMerchant::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'business_name' => 'Gerai UMKM ' . $user->name,
                        'owner_name' => $user->name,
                        'category' => 'KULINER',
                        'address' => 'Sentra Kuliner Pantai Jumiang',
                        'phone_number' => $user->phone_number ?? '081234567890',
                        'verification_status' => 'VERIFIED',
                    ]
                );
            }

            $productsCount = Product::where('umkm_merchant_id', $merchant->id)->count();
            $lowStockProducts = Product::where('umkm_merchant_id', $merchant->id)->whereColumn('stock', '<=', 'low_stock_threshold')->get();
            $merchantOrders = Order::where('umkm_merchant_id', $merchant->id)->latest()->take(5)->get();
            $financialAccounts = $merchant->financialAccounts;
            $cashBalance = $financialAccounts->where('account_type', 'BUSINESS')->sum('current_balance');

            return view('admin.dashboard.umkm', compact('merchant', 'productsCount', 'lowStockProducts', 'merchantOrders', 'cashBalance'));
        }

        if ($user->hasRole('bendahara')) {
            $kpi = $this->analyticsService->getMacroKPIs();
            $recentTransactions = FinancialTransaction::with(['merchant', 'account', 'category'])->latest()->take(10)->get();
            return view('admin.dashboard.bendahara', compact('kpi', 'recentTransactions'));
        }

        if ($user->hasRole('pimpinan')) {
            $kpi = $this->analyticsService->getMacroKPIs();
            $trends = $this->analyticsService->getRevenueTrends();
            $sectors = $this->analyticsService->getSectorDistribution();
            return view('admin.dashboard.pimpinan', compact('kpi', 'trends', 'sectors'));
        }

        // Default: Wisatawan profile dashboard
        $myOrders = Order::with('items.product')->where('user_id', $user->id)->latest()->get();
        $myReservations = Reservation::where('user_id', $user->id)->latest()->get();
        return view('admin.dashboard.wisatawan', compact('myOrders', 'myReservations'));
    }
}
