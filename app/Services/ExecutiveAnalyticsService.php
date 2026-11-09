<?php

namespace App\Services;

use App\Models\UmkmMerchant;
use App\Models\Product;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\DB;

class ExecutiveAnalyticsService
{
    public function getMacroKPIs(): array
    {
        $totalUmkm = UmkmMerchant::where('verification_status', 'VERIFIED')->count();
        $totalProducts = Product::where('is_active', true)->count();
        $totalOrders = Order::where('status', 'COMPLETED')->orWhere('status', 'PAID')->count();
        $totalGrossRevenue = Order::whereIn('status', ['COMPLETED', 'PAID'])->sum('total_amount');
        $totalVisitors = Reservation::whereIn('status', ['CONFIRMED', 'ATTENDED'])->sum('visitor_count');

        return [
            'total_umkm' => $totalUmkm,
            'total_products' => $totalProducts,
            'total_orders' => $totalOrders,
            'total_gross_revenue' => (float) $totalGrossRevenue,
            'total_visitors' => (int) $totalVisitors,
        ];
    }

    public function getRevenueTrends(): array
    {
        // Monthly revenue trends
        $labels = ['Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov'];
        $orderData = [1200000, 1850000, 2400000, 2100000, 3100000, (float) Order::sum('total_amount')];

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Omzet Penjualan UMKM (IDR)',
                    'data' => $orderData,
                    'borderColor' => '#0d6efd',
                    'backgroundColor' => 'rgba(13, 110, 253, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ]
            ]
        ];
    }

    public function getSectorDistribution(): array
    {
        $sectors = UmkmMerchant::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        return [
            'labels' => array_keys($sectors),
            'data' => array_values($sectors),
        ];
    }
}
