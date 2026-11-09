<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'umkm_merchant_id',
        'financial_account_id',
        'category_id',
        'order_id',
        'transaction_type',
        'amount',
        'transaction_date',
        'description',
        'receipt_image',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'float',
        'transaction_date' => 'date',
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(UmkmMerchant::class, 'umkm_merchant_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(FinancialAccount::class, 'financial_account_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinancialCategory::class, 'category_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeForMerchant($query, $merchantId)
    {
        return $query->where('umkm_merchant_id', $merchantId);
    }

    public function scopeIncome($query)
    {
        return $query->where('transaction_type', 'INCOME');
    }

    public function scopeExpense($query)
    {
        return $query->where('transaction_type', 'EXPENSE');
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('transaction_date', [$startDate, $endDate]);
        }
        return $query;
    }
}
