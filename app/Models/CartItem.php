<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'product_variation_id',
        'quantity',
        'options',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'options' => 'json',
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product that owns the cart item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the product variation that owns the cart item.
     */
    public function productVariation()
    {
        return $this->belongsTo(ProductVariation::class);
    }

    /**
     * Get the cart item's price.
     *
     * @return float
     */
    public function getPriceAttribute()
    {
        if ($this->productVariation) {
            return $this->productVariation->current_price;
        }

        return $this->product->current_price;
    }

    /**
     * Get the cart item's subtotal.
     *
     * @return float
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Check if the cart item has a product variation.
     *
     * @return bool
     */
    public function hasVariation()
    {
        return $this->product_variation_id !== null;
    }

    /**
     * Check if the cart item is in stock.
     *
     * @return bool
     */
    public function isInStock()
    {
        if ($this->productVariation) {
            return $this->productVariation->isInStock();
        }

        return $this->product->isInStock();
    }

    /**
     * Check if the cart item has enough stock for the current quantity.
     *
     * @return bool
     */
    public function hasEnoughStock()
    {
        if ($this->productVariation) {
            return $this->productVariation->quantity >= $this->quantity;
        }

        return $this->product->quantity >= $this->quantity;
    }

    /**
     * Get the maximum available quantity for the cart item.
     *
     * @return int
     */
    public function getMaximumAvailableQuantity()
    {
        if ($this->productVariation) {
            return $this->productVariation->quantity;
        }

        return $this->product->quantity;
    }
}