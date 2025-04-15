<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
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
        'price',
        'sale_price',
        'sku',
        'quantity',
        'category_id',
        'image',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'shipping_class_id',
        'weight',
        'length',
        'width',
        'height',
        'tax_class_id',
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
        'is_featured' => 'boolean',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the variations for the product.
     */
    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the tags for the product.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get the attributes for the product.
     */
    public function attributes()
    {
        return $this->belongsToMany(ProductAttribute::class, 'product_attribute_values')
            ->withPivot('attribute_value_id')
            ->withTimestamps();
    }

    /**
     * Get the shipping class that owns the product.
     */
    public function shippingClass()
    {
        return $this->belongsTo(ShippingClass::class);
    }

    /**
     * Get the tax class that owns the product.
     */
    public function taxClass()
    {
        return $this->belongsTo(TaxClass::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the wishlist items for the product.
     */
    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    /**
     * Scope a query to only include active products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include products on sale.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price');
    }

    /**
     * Scope a query to only include products in stock.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Scope a query to only include products out of stock.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', 0);
    }

    /**
     * Scope a query to only include products with low stock.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $threshold
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->where('quantity', '>', 0)->where('quantity', '<=', $threshold);
    }

    /**
     * Get the product's current price.
     *
     * @return float
     */
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Get the product's discount percentage.
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
     * Get the product's average rating.
     *
     * @return float
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Check if the product is on sale.
     *
     * @return bool
     */
    public function isOnSale()
    {
        return $this->sale_price !== null;
    }

    /**
     * Check if the product is in stock.
     *
     * @return bool
     */
    public function isInStock()
    {
        return $this->quantity > 0;
    }

    /**
     * Check if the product has low stock.
     *
     * @param  int  $threshold
     * @return bool
     */
    public function hasLowStock($threshold = 5)
    {
        return $this->quantity > 0 && $this->quantity <= $threshold;
    }

    /**
     * Get the product's primary image URL.
     *
     * @return string
     */
    public function getPrimaryImageUrlAttribute()
    {
        $primaryImage = $this->images()->where('is_primary', true)->first();
        
        if ($primaryImage) {
            return asset('storage/' . $primaryImage->path);
        }
        
        return $this->image ? asset('storage/' . $this->image) : asset('images/placeholder.jpg');
    }

    /**
     * Get the product's gallery images.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getGalleryImagesAttribute()
    {
        return $this->images()->where('is_primary', false)->get();
    }
}