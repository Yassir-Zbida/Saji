<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'address_type', // 'shipping', 'billing'
        'first_name',
        'last_name',
        'company',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'email',
        'is_default',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders that use this address as shipping address.
     */
    public function shippingOrders()
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }

    /**
     * Get the orders that use this address as billing address.
     */
    public function billingOrders()
    {
        return $this->hasMany(Order::class, 'billing_address_id');
    }

    /**
     * Get the full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the formatted address.
     *
     * @return string
     */
    public function getFormattedAddressAttribute()
    {
        $address = $this->address_line_1;
        
        if ($this->address_line_2) {
            $address .= ", {$this->address_line_2}";
        }
        
        $address .= ", {$this->city}, {$this->state} {$this->postal_code}, {$this->country}";
        
        return $address;
    }

    /**
     * Scope a query to only include shipping addresses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShipping($query)
    {
        return $query->where('address_type', 'shipping');
    }

    /**
     * Scope a query to only include billing addresses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBilling($query)
    {
        return $query->where('address_type', 'billing');
    }

    /**
     * Scope a query to only include default addresses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
