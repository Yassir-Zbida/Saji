<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'position',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'position' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the regions for the shipping zone.
     */
    public function regions()
    {
        return $this->hasMany(ShippingZoneRegion::class);
    }

    /**
     * Get the methods for the shipping zone.
     */
    public function methods()
    {
        return $this->hasMany(ShippingMethod::class);
    }

    /**
     * Scope a query to only include active shipping zones.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order shipping zones by position.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    /**
     * Check if the shipping zone has regions.
     *
     * @return bool
     */
    public function hasRegions()
    {
        return $this->regions()->count() > 0;
    }

    /**
     * Check if the shipping zone has methods.
     *
     * @return bool
     */
    public function hasMethods()
    {
        return $this->methods()->count() > 0;
    }

    /**
     * Check if the shipping zone applies to a given country.
     *
     * @param  string  $countryCode
     * @return bool
     */
    public function appliesToCountry($countryCode)
    {
        return $this->regions()
            ->where('region_type', 'country')
            ->where('region_code', $countryCode)
            ->exists();
    }

    /**
     * Check if the shipping zone applies to a given state.
     *
     * @param  string  $countryCode
     * @param  string  $stateCode
     * @return bool
     */
    public function appliesToState($countryCode, $stateCode)
    {
        return $this->regions()
            ->where('region_type', 'state')
            ->where('region_code', $stateCode)
            ->where('country_code', $countryCode)
            ->exists();
    }

    /**
     * Check if the shipping zone applies to a given postcode.
     *
     * @param  string  $countryCode
     * @param  string  $postcode
     * @return bool
     */
    public function appliesToPostcode($countryCode, $postcode)
    {
        $postcodeRegions = $this->regions()
            ->where('region_type', 'postcode')
            ->where('country_code', $countryCode)
            ->get();

        foreach ($postcodeRegions as $region) {
            if ($region->matchesPostcode($postcode)) {
                return true;
            }
        }

        return false;
    }
}