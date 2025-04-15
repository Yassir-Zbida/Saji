<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shipping_zone_id',
        'name',
        'method_type',
        'cost',
        'minimum_order_amount',
        'maximum_order_amount',
        'is_taxable',
        'position',
        'is_active',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cost' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'maximum_order_amount' => 'decimal:2',
        'is_taxable' => 'boolean',
        'position' => 'integer',
        'is_active' => 'boolean',
        'settings' => 'json',
    ];

    /**
     * Get the shipping zone that owns the method.
     */
    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class);
    }

    /**
     * Get the class rates for the shipping method.
     */
    public function classRates()
    {
        return $this->hasMany(ShippingClassRate::class);
    }

    /**
     * Scope a query to only include active shipping methods.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order shipping methods by position.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    /**
     * Calculate the shipping cost for a given order.
     *
     * @param  float  $orderAmount
     * @param  array  $items
     * @return float
     */
    public function calculateCost($orderAmount, $items = [])
    {
        // Check minimum and maximum order amounts
        if ($this->minimum_order_amount && $orderAmount < $this->minimum_order_amount) {
            return null;
        }

        if ($this->maximum_order_amount && $orderAmount > $this->maximum_order_amount) {
            return null;
        }

        // Free shipping
        if ($this->method_type === 'free_shipping') {
            return 0;
        }

        // Flat rate
        if ($this->method_type === 'flat_rate') {
            return $this->cost;
        }

        // Class based rates
        if ($this->method_type === 'class_based' && !empty($items)) {
            $cost = 0;
            $classRates = $this->classRates()->get()->keyBy('shipping_class_id');

            foreach ($items as $item) {
                $product = $item['product'] ?? null;
                $quantity = $item['quantity'] ?? 1;

                if ($product && $product->shipping_class_id) {
                    $classRate = $classRates->get($product->shipping_class_id);

                    if ($classRate) {
                        if ($classRate->cost_type === 'per_item') {
                            $cost += $classRate->cost * $quantity;
                        } else {
                            $cost += $classRate->cost;
                        }
                    } else {
                        $cost += $this->cost;
                    }
                } else {
                    $cost += $this->cost;
                }
            }

            return $cost;
        }

        return $this->cost;
    }

    /**
     * Get a setting value.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function getSetting($key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Set a setting value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setSetting($key, $value)
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->settings = $settings;

        return $this;
    }
}