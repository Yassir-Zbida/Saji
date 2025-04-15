<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingClassRate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shipping_method_id',
        'shipping_class_id',
        'cost',
        'cost_type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cost' => 'decimal:2',
    ];

    /**
     * Get the shipping method that owns the class rate.
     */
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    /**
     * Get the shipping class that owns the class rate.
     */
    public function shippingClass()
    {
        return $this->belongsTo(ShippingClass::class);
    }
}