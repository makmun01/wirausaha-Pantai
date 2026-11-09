<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialAccount extends Model
{
    protected $fillable = [
        'umkm_merchant_id',
        'account_name',
        'account_type',
        'initial_balance',
        'current_balance',
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(UmkmMerchant::class, 'umkm_merchant_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function scopeBusiness($query)
    {
        return $query->where('account_type', 'BUSINESS');
    }

    public function scopePersonal($query)
    {
        return $query->where('account_type', 'PERSONAL');
    }
}
