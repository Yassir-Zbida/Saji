<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tax_class_id',
        'name',
        'country',
        'state',
        'postal_code',
        'city',
        'rate',
        'priority',
        'compound',
        'shipping',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rate' => 'decimal:4',
        'priority' => 'integer',
        'compound' => 'boolean',
        'shipping' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the tax class that owns the tax rate.
     */
    public function taxClass()
    {
        return $this->belongsTo(TaxClass::class);
    }

    /**
     * Scope a query to only include active tax rates.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include tax rates for a specific country.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $country
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Scope a query to only include tax rates for a specific state.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $state
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForState($query, $state)
    {
        return $query->where('state', $state);
    }

    /**
     * Scope a query to only include tax rates for a specific postal code.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $postalCode
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPostalCode($query, $postalCode)
    {
        return $query->where('postal_code', $postalCode);
    }

    /**
     * Scope a query to only include tax rates for a specific city.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $city
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * Scope a query to only include tax rates that apply to shipping.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForShipping($query)
    {
        return $query->where('shipping', true);
    }

    /**
     * Scope a query to order tax rates by priority.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPriority($query)
    {
        return $query->orderBy('priority', 'asc');
    }

    /**
     * Calculate tax amount for a given price.
     *
     * @param  float  $price
     * @return float
     */
    public function calculateTax($price)
    {
        return $price * ($this->rate / 100);
    }
}
