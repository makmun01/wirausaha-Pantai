<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\FinancialTransaction;
use App\Models\Product;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'transactions');
        $startDate = $request->query('start_date', date('Y-m-01'));
        $endDate = $request->query('end_date', date('Y-m-t'));

        $orders = Order::with(['merchant', 'user'])->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])->get();
        $financials = FinancialTransaction::with(['merchant', 'category'])->whereBetween('transaction_date', [$startDate, $endDate])->get();
        $reservations = Reservation::with(['user'])->whereBetween('visit_date', [$startDate, $endDate])->get();

        return view('admin.reports.index', compact('type', 'startDate', 'endDate', 'orders', 'financials', 'reservations'));
    }
}
