<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlists extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'variation_id',
        'notes',
    ];

    /**
     * Get the user that owns the wishlist item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that owns the wishlist item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variation that owns the wishlist item.
     */
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    /**
     * Check if the wishlist item has a product variation.
     *
     * @return bool
     */
    public function hasVariation()
    {
        return $this->variation_id !== null;
    }

    /**
     * Check if the wishlist item is in stock.
     *
     * @return bool
     */
    public function isInStock()
    {
        if ($this->hasVariation() && $this->variation) {
            return $this->variation->isInStock();
        }

        return $this->product->isInStock();
    }

    /**
     * Get the wishlist item's price.
     *
     * @return float
     */
    public function getPriceAttribute()
    {
        if ($this->hasVariation() && $this->variation) {
            return $this->variation->current_price;
        }

        return $this->product->current_price;
    }
}