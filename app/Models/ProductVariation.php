<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'sale_price',
        'quantity',
        'is_active',
        'image',
        'weight',
        'length',
        'width',
        'height',
        'attribute_values',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'quantity' => 'integer',
        'is_active' => 'boolean',
        'attribute_values' => 'json',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    /**
     * Get the product that owns the variation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the order items for the variation.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the cart items for the variation.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Scope a query to only include active variations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include variations on sale.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price');
    }

    /**
     * Scope a query to only include variations in stock.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Get the variation's current price.
     *
     * @return float
     */
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Get the variation's discount percentage.
     *
     * @return float|null
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->price > 0) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }

        return null;
    }

    /**
     * Check if the variation is on sale.
     *
     * @return bool
     */
    public function isOnSale()
    {
        return $this->sale_price !== null;
    }

    /**
     * Check if the variation is in stock.
     *
     * @return bool
     */
    public function isInStock()
    {
        return $this->quantity > 0;
    }

    /**
     * Get the variation's image URL.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return $this->product->primary_image_url;
    }

    /**
     * Get the attribute values for the variation.
     *
     * @return array
     */
    public function getAttributeValuesCollection()
    {
        $result = [];
        
        if ($this->attribute_values) {
            foreach ($this->attribute_values as $attributeId => $valueId) {
                $attribute = ProductAttribute::find($attributeId);
                $value = AttributeValue::find($valueId);
                
                if ($attribute && $value) {
                    $result[$attribute->name] = $value->value;
                }
            }
        }
        
        return $result;
    }
}