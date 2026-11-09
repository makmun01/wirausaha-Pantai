<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ExecutiveAnalyticsService;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\UmkmMerchant;

class EisController extends Controller
{
    protected ExecutiveAnalyticsService $analyticsService;

    public function __construct(ExecutiveAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $kpi = $this->analyticsService->getMacroKPIs();
        $trends = $this->analyticsService->getRevenueTrends();
        $sectors = $this->analyticsService->getSectorDistribution();

        $merchants = UmkmMerchant::withCount('products')->withSum('orders', 'total_amount')->get();

        return view('admin.eis.index', compact('kpi', 'trends', 'sectors', 'merchants'));
    }
}
