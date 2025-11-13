<?php

namespace Modules\Wallet\Entities;

use Modules\Support\Money;
use Modules\Support\Eloquent\Model;
use Modules\Vendor\Entities\Vendor;

class Wallet extends Model
{
    protected $fillable = [
        'vendor_id',
        'balance',
        'pending_balance',
        'total_earned',
        'total_withdrawn',
        'total_commission',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class)->latest();
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->latest();
    }

    // Accessors
    public function getBalanceAttribute($balance)
    {
        return Money::inDefaultCurrency($balance);
    }

    public function getPendingBalanceAttribute($pendingBalance)
    {
        return Money::inDefaultCurrency($pendingBalance);
    }

    public function getTotalEarnedAttribute($totalEarned)
    {
        return Money::inDefaultCurrency($totalEarned);
    }

    public function getTotalWithdrawnAttribute($totalWithdrawn)
    {
        return Money::inDefaultCurrency($totalWithdrawn);
    }

    public function getTotalCommissionAttribute($totalCommission)
    {
        return Money::inDefaultCurrency($totalCommission);
    }

    // Methods
    public function credit($amount, $type, $description = null, $reference = null)
    {
        $balanceBefore = $this->attributes['balance'];
        $this->increment('balance', $amount);
        $this->increment('total_earned', $amount);

        return $this->transactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore + $amount,
            'transaction_type' => $type,
            'description' => $description,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->id : null,
        ]);
    }

    public function debit($amount, $type, $description = null, $reference = null)
    {
        if ($this->attributes['balance'] < $amount) {
            throw new \Exception('Yetersiz bakiye');
        }

        $balanceBefore = $this->attributes['balance'];
        $this->decrement('balance', $amount);

        return $this->transactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore - $amount,
            'transaction_type' => $type,
            'description' => $description,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference ? $reference->id : null,
        ]);
    }

    public function addPending($amount)
    {
        $this->increment('pending_balance', $amount);
    }

    public function releasePending($amount)
    {
        $this->decrement('pending_balance', $amount);
        $this->increment('balance', $amount);
    }
}

