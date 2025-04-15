<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZoneRegion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shipping_zone_id',
        'region_type',
        'region_code',
        'country_code',
    ];

    /**
     * Get the shipping zone that owns the region.
     */
    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class);
    }

    /**
     * Check if the region matches a given postcode.
     *
     * @param  string  $postcode
     * @return bool
     */
    public function matchesPostcode($postcode)
    {
        if ($this->region_type !== 'postcode') {
            return false;
        }

        $patterns = explode(',', $this->region_code);
        $postcode = strtoupper(trim($postcode));

        foreach ($patterns as $pattern) {
            $pattern = strtoupper(trim($pattern));

            // Check for wildcards
            if (strpos($pattern, '*') !== false) {
                $pattern = str_replace('*', '.*', $pattern);
                if (preg_match('/^' . $pattern . '$/', $postcode)) {
                    return true;
                }
            }
            // Check for ranges
            elseif (strpos($pattern, '-') !== false) {
                list($start, $end) = explode('-', $pattern, 2);
                if ($postcode >= trim($start) && $postcode <= trim($end)) {
                    return true;
                }
            }
            // Exact match
            elseif ($pattern === $postcode) {
                return true;
            }
        }

        return false;
    }
}