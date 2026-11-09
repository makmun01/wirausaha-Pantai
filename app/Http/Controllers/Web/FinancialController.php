<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use App\Models\FinancialCategory;
use App\Models\FinancialAccount;
use App\Services\FinancialBookkeepingService;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    protected FinancialBookkeepingService $financialService;

    public function __construct(FinancialBookkeepingService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $merchant = $user->umkm;

        if (!$merchant && !$user->hasRole('super_admin') && !$user->hasRole('bendahara')) {
            abort(403, 'Anda belum memiliki profil gerai UMKM.');
        }

        $merchantId = $merchant ? $merchant->id : ($request->query('merchant_id') ?? 1);

        $accounts = FinancialAccount::where('umkm_merchant_id', $merchantId)->get();
        $categories = FinancialCategory::all();

        $transactions = FinancialTransaction::with(['account', 'category'])
            ->where('umkm_merchant_id', $merchantId)
            ->latest('transaction_date')
            ->paginate(15);

        return view('admin.financial.index', compact('merchant', 'accounts', 'categories', 'transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'financial_account_id' => 'required|exists:financial_accounts,id',
            'category_id' => 'required|exists:financial_categories,id',
            'transaction_type' => 'required|in:INCOME,EXPENSE',
            'amount' => 'required|numeric|min:1',
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $merchant = $user->umkm;
        $merchantId = $merchant ? $merchant->id : 1;

        $data = $request->all();
        $data['umkm_merchant_id'] = $merchantId;

        try {
            $transaction = $this->financialService->recordTransaction($data, Auth::id());
            AuditTrailService::log('FINANCIAL_ENTRY', 'FinancialTransaction', $transaction->id, null, ['type' => $transaction->transaction_type, 'amount' => $transaction->amount]);
            return back()->with('success', 'Transaksi keuangan berhasil dicatat ke buku kas.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function profitLoss(Request $request)
    {
        $user = Auth::user();
        $merchant = $user->umkm;
        $merchantId = $merchant ? $merchant->id : ($request->query('merchant_id') ?? 1);

        $startDate = $request->query('start_date', date('Y-m-01'));
        $endDate = $request->query('end_date', date('Y-m-t'));

        $report = $this->financialService->getProfitAndLoss($merchantId, $startDate, $endDate);
        return view('admin.financial.profit_loss', compact('report', 'merchant', 'startDate', 'endDate'));
    }
}
