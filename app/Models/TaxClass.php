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
        'country',
        'state',
        'postcode',
        'city',
        'rate',
        'name',
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
     * Check if the tax rate applies to a given location.
     *
     * @param  string  $country
     * @param  string|null  $state
     * @param  string|null  $postcode
     * @param  string|null  $city
     * @return bool
     */
    public function appliesToLocation($country, $state = null, $postcode = null, $city = null)
    {
        // Check country
        if ($this->country && $this->country !== $country) {
            return false;
        }

        // Check state
        if ($this->state && $state && $this->state !== $state) {
            return false;
        }

        // Check postcode
        if ($this->postcode && $postcode) {
            $postcodes = array_map('trim', explode(',', $this->postcode));
            $match = false;

            foreach ($postcodes as $pattern) {
                if (strpos($pattern, '*') !== false) {
                    $pattern = str_replace('*', '.*', $pattern);
                    if (preg_match('/^' . $pattern . '$/i', $postcode)) {
                        $match = true;
                        break;
                    }
                } elseif ($pattern === $postcode) {
                    $match = true;
                    break;
                }
            }

            if (!$match) {
                return false;
            }
        }

        // Check city
        if ($this->city && $city && strtolower($this->city) !== strtolower($city)) {
            return false;
        }

        return true;
    }

    /**
     * Calculate the tax amount for a given price.
     *
     * @param  float  $price
     * @return float
     */
    public function calculateTax($price)
    {
        return $price * ($this->rate / 100);
    }
}