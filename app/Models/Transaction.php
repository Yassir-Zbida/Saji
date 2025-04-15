<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_method',
        'amount',
        'currency',
        'status', // 'pending', 'completed', 'failed', 'refunded'
        'payment_gateway',
        'gateway_response',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
    ];

    /**
     * Get the order that owns the transaction.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope a query to only include successful transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include failed transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include pending transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include refunded transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    /**
     * Scope a query to only include transactions with a specific payment method.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $method
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Scope a query to only include transactions with a specific payment gateway.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $gateway
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPaymentGateway($query, $gateway)
    {
        return $query->where('payment_gateway', $gateway);
    }

    /**
     * Check if the transaction is successful.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the transaction is pending.
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the transaction has failed.
     *
     * @return bool
     */
    public function hasFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if the transaction has been refunded.
     *
     * @return bool
     */
    public function isRefunded()
    {
        return $this->status === 'refunded';
    }
}
