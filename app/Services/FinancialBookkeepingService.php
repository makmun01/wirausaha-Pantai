<?php

namespace App\Services;

use App\Models\FinancialAccount;
use App\Models\FinancialTransaction;
use App\Models\FinancialCategory;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class FinancialBookkeepingService
{
    public function recordTransaction(array $data, int $userId): FinancialTransaction
    {
        return DB::transaction(function () use ($data, $userId) {
            $account = FinancialAccount::lockForUpdate()->findOrFail($data['financial_account_id']);

            // Security check: Ensure account belongs to the declared merchant
            if ($account->umkm_merchant_id != $data['umkm_merchant_id']) {
                throw new \SecurityException("Pelanggaran isolasi data: Rekening bukan milik merchant ini.");
            }

            $amount = (float) $data['amount'];
            if ($amount <= 0) {
                throw new \InvalidArgumentException("Nominal transaksi harus lebih besar dari 0.");
            }

            if ($data['transaction_type'] === 'INCOME') {
                $account->current_balance += $amount;
            } elseif ($data['transaction_type'] === 'EXPENSE') {
                $account->current_balance -= $amount;
            }
            $account->save();

            return FinancialTransaction::create([
                'umkm_merchant_id' => $data['umkm_merchant_id'],
                'financial_account_id' => $account->id,
                'category_id' => $data['category_id'],
                'order_id' => $data['order_id'] ?? null,
                'transaction_type' => $data['transaction_type'],
                'amount' => $amount,
                'transaction_date' => $data['transaction_date'] ?? date('Y-m-d'),
                'description' => $data['description'],
                'receipt_image' => $data['receipt_image'] ?? null,
                'created_by' => $userId,
            ]);
        });
    }

    public function recordSaleIncome(Order $order, int $userId): FinancialTransaction
    {
        $businessAccount = FinancialAccount::where('umkm_merchant_id', $order->umkm_merchant_id)
            ->where('account_type', 'BUSINESS')
            ->firstOrFail();

        $saleCategory = FinancialCategory::firstOrCreate(
            ['name' => 'Penjualan Online Marketplace'],
            ['type' => 'INCOME', 'is_system_default' => true]
        );

        return $this->recordTransaction([
            'umkm_merchant_id' => $order->umkm_merchant_id,
            'financial_account_id' => $businessAccount->id,
            'category_id' => $saleCategory->id,
            'order_id' => $order->id,
            'transaction_type' => 'INCOME',
            'amount' => $order->total_amount,
            'transaction_date' => date('Y-m-d'),
            'description' => "Penerimaan penjualan otomatis pesanan #{$order->invoice_no}",
        ], $userId);
    }

    public function getProfitAndLoss(int $merchantId, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = FinancialTransaction::where('umkm_merchant_id', $merchantId);

        if ($startDate && $endDate) {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        $transactions = $query->with('category')->get();

        $totalIncome = $transactions->where('transaction_type', 'INCOME')->sum('amount');
        $totalExpense = $transactions->where('transaction_type', 'EXPENSE')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'net_profit' => $netProfit,
            'is_profit' => $netProfit >= 0,
            'transactions' => $transactions,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }

    public function getMerchantAccounts(int $merchantId)
    {
        return FinancialAccount::where('umkm_merchant_id', $merchantId)->get();
    }
}
