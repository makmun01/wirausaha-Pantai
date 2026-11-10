<?php

namespace App\Services;

use App\Models\UmkmMerchant;
use App\Models\FinancialAccount;
use Illuminate\Support\Facades\DB;

class UmkmService
{
    public const VALID_VERIFICATION_TRANSITIONS = [
        'PENDING' => ['VERIFIED', 'REJECTED'],
        'VERIFIED' => ['SUSPENDED'],
        'REJECTED' => ['PENDING', 'VERIFIED'],
        'SUSPENDED' => ['VERIFIED'],
    ];

    public function getVerifiedMerchants()
    {
        return UmkmMerchant::with(['products', 'user'])->where('verification_status', 'VERIFIED')->get();
    }

    public function getAllMerchants()
    {
        return UmkmMerchant::with(['user', 'products'])->latest()->get();
    }

    public function registerMerchant(array $data, int $userId): UmkmMerchant
    {
        return DB::transaction(function () use ($data, $userId) {
            $merchant = UmkmMerchant::create([
                'user_id' => $userId,
                'business_name' => $data['business_name'],
                'owner_name' => $data['owner_name'],
                'category' => $data['category'] ?? 'KULINER',
                'address' => $data['address'],
                'phone_number' => $data['phone_number'],
                'verification_status' => 'PENDING',
            ]);

            // Auto-create initial dedicated business & personal cash accounts
            FinancialAccount::create([
                'umkm_merchant_id' => $merchant->id,
                'account_name' => 'Kas Operasional Usaha ' . $merchant->business_name,
                'account_type' => 'BUSINESS',
                'initial_balance' => 0.00,
                'current_balance' => 0.00,
            ]);

            FinancialAccount::create([
                'umkm_merchant_id' => $merchant->id,
                'account_name' => 'Rekening Tabungan Pribadi ' . $merchant->owner_name,
                'account_type' => 'PERSONAL',
                'initial_balance' => 0.00,
                'current_balance' => 0.00,
            ]);

            return $merchant;
        });
    }

    public function isValidVerificationTransition(string $currentStatus, string $targetStatus): bool
    {
        $allowed = self::VALID_VERIFICATION_TRANSITIONS[$currentStatus] ?? [];
        return in_array($targetStatus, $allowed, true);
    }

    public function updateVerificationStatus(int $merchantId, string $status, ?string $reason = null): bool
    {
        $merchant = UmkmMerchant::findOrFail($merchantId);
        if ($merchant->verification_status !== $status && !$this->isValidVerificationTransition($merchant->verification_status, $status)) {
            throw new \InvalidArgumentException("Transisi status verifikasi UMKM tidak valid: {$merchant->verification_status} -> {$status}.");
        }
        $merchant->verification_status = $status;
        $merchant->rejection_reason = $reason;
        return $merchant->save();
    }
}
