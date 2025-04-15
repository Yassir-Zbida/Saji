<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShippingClass extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shippingClass) {
            if (empty($shippingClass->slug)) {
                $shippingClass->slug = Str::slug($shippingClass->name);
            }
        });

        static::updating(function ($shippingClass) {
            if ($shippingClass->isDirty('name') && !$shippingClass->isDirty('slug')) {
                $shippingClass->slug = Str::slug($shippingClass->name);
            }
        });
    }

    /**
     * Get the products for the shipping class.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the class rates for the shipping class.
     */
    public function classRates()
    {
        return $this->hasMany(ShippingClassRate::class);
    }
}