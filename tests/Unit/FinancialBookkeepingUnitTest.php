<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\SecurityException;
use App\Models\FinancialAccount;
use App\Models\FinancialCategory;
use App\Services\FinancialBookkeepingService;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;
use Tests\Assert;

class FinancialBookkeepingUnitTest extends TestCase
{
    private FinancialBookkeepingService $service;

    protected function setUp(): void
    {
        Model::resetStore();
        $this->service = new FinancialBookkeepingService();

        FinancialAccount::create([
            'umkm_merchant_id' => 1,
            'account_name' => 'Kas Operasional Usaha Lorjuk',
            'account_type' => 'BUSINESS',
            'initial_balance' => 1000000.0,
            'current_balance' => 1000000.0,
        ]);

        FinancialAccount::create([
            'umkm_merchant_id' => 1,
            'account_name' => 'Tabungan Pribadi Bu Maryam',
            'account_type' => 'PERSONAL',
            'initial_balance' => 500000.0,
            'current_balance' => 500000.0,
        ]);

        FinancialAccount::create([
            'umkm_merchant_id' => 2,
            'account_name' => 'Kas Batik Pesisir Pak Hidayat',
            'account_type' => 'BUSINESS',
            'initial_balance' => 750000.0,
            'current_balance' => 750000.0,
        ]);

        FinancialCategory::create([
            'name' => 'Penjualan Harian',
            'type' => 'INCOME',
            'is_system_default' => true,
        ]);

        FinancialCategory::create([
            'name' => 'Bahan Baku Lorjuk',
            'type' => 'EXPENSE',
            'is_system_default' => true,
        ]);
    }

    public function test_path1_rejects_cross_merchant_account_access(): void
    {
        Assert::throws(SecurityException::class, function (): void {
            $this->service->recordTransaction([
                'umkm_merchant_id' => 1,
                'financial_account_id' => 3, // Milik merchant 2
                'category_id' => 1,
                'transaction_type' => 'INCOME',
                'amount' => 150000,
                'description' => 'Percobaan akses lintas tenant',
            ], 3);
        }, 'Akses rekening milik merchant lain wajib ditolak SecurityException.');
    }

    public function test_path2_rejects_zero_and_negative_amounts(): void
    {
        Assert::throws(\InvalidArgumentException::class, function (): void {
            $this->service->recordTransaction([
                'umkm_merchant_id' => 1,
                'financial_account_id' => 1,
                'category_id' => 1,
                'transaction_type' => 'INCOME',
                'amount' => 0,
                'description' => 'Nominal batas 0',
            ], 3);
        });

        Assert::throws(\InvalidArgumentException::class, function (): void {
            $this->service->recordTransaction([
                'umkm_merchant_id' => 1,
                'financial_account_id' => 1,
                'category_id' => 2,
                'transaction_type' => 'EXPENSE',
                'amount' => -25000,
                'description' => 'Nominal negatif',
            ], 3);
        });
    }

    public function test_path3_income_updates_only_business_account_balance(): void
    {
        $tx = $this->service->recordTransaction([
            'umkm_merchant_id' => 1,
            'financial_account_id' => 1,
            'category_id' => 1,
            'transaction_type' => 'INCOME',
            'amount' => 350000,
            'description' => 'Penjualan paket oleh-oleh',
        ], 3);

        $businessAccount = FinancialAccount::find(1);
        $personalAccount = FinancialAccount::find(2);

        Assert::same(350000.0, (float) $tx->amount);
        Assert::same(1350000.0, (float) $businessAccount->current_balance);
        Assert::same(500000.0, (float) $personalAccount->current_balance, 'Saldo pribadi tidak boleh tercampur mutasi usaha.');
    }

    public function test_path4_expense_and_profit_loss_calculation(): void
    {
        $this->service->recordTransaction([
            'umkm_merchant_id' => 1,
            'financial_account_id' => 1,
            'category_id' => 1,
            'transaction_type' => 'INCOME',
            'amount' => 600000,
            'description' => 'Omzet akhir pekan',
        ], 3);

        $this->service->recordTransaction([
            'umkm_merchant_id' => 1,
            'financial_account_id' => 1,
            'category_id' => 2,
            'transaction_type' => 'EXPENSE',
            'amount' => 225000,
            'description' => 'Belanja bahan lorjuk',
        ], 3);

        $businessAccount = FinancialAccount::find(1);
        $pnl = $this->service->getProfitAndLoss(1);

        Assert::same(1375000.0, (float) $businessAccount->current_balance);
        Assert::same(600000.0, (float) $pnl['total_income']);
        Assert::same(225000.0, (float) $pnl['total_expense']);
        Assert::same(375000.0, (float) $pnl['net_profit']);
        Assert::true($pnl['is_profit']);
    }
}
