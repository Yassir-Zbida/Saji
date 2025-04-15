<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'type',
        'value',
        'minimum_spend',
        'maximum_discount',
        'usage_limit',
        'usage_count',
        'individual_use',
        'exclude_sale_items',
        'start_date',
        'end_date',
        'is_active',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:2',
        'minimum_spend' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'individual_use' => 'boolean',
        'exclude_sale_items' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the orders for the coupon.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope a query to only include active coupons.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include valid coupons.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->where(function ($query) {
                $query->whereNull('usage_limit')
                    ->orWhereRaw('usage_count < usage_limit');
            });
    }

    /**
     * Check if the coupon is valid.
     *
     * @return bool
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->start_date && $this->start_date->isFuture()) {
            return false;
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Check if the coupon is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->end_date && $this->end_date->isPast();
    }

    /**
     * Calculate the discount amount for a given subtotal.
     *
     * @param  float  $subtotal
     * @return float
     */
    public function calculateDiscount($subtotal)
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->minimum_spend && $subtotal < $this->minimum_spend) {
            return 0;
        }

        $discount = 0;

        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
        } elseif ($this->type === 'fixed_amount') {
            $discount = $this->value;
        }

        if ($this->maximum_discount && $discount > $this->maximum_discount) {
            $discount = $this->maximum_discount;
        }

        return $discount;
    }

    /**
     * Increment the usage count.
     *
     * @return void
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
    }
}