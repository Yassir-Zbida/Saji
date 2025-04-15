<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'total_amount',
        'status',
        'pdf_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the order that owns the invoice.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope a query to only include invoices with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include paid invoices.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include unpaid invoices.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    /**
     * Scope a query to only include cancelled invoices.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include overdue invoices.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'unpaid')
            ->whereDate('due_date', '<', now());
    }

    /**
     * Check if the invoice is paid.
     *
     * @return bool
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    /**
     * Check if the invoice is unpaid.
     *
     * @return bool
     */
    public function isUnpaid()
    {
        return $this->status === 'unpaid';
    }

    /**
     * Check if the invoice is cancelled.
     *
     * @return bool
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if the invoice is overdue.
     *
     * @return bool
     */
    public function isOverdue()
    {
        return $this->status === 'unpaid' && $this->due_date->isPast();
    }

    /**
     * Get the PDF URL.
     *
     * @return string|null
     */
    public function getPdfUrlAttribute()
    {
        return $this->pdf_path ? asset('storage/' . $this->pdf_path) : null;
    }
}