<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AttributeValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'attribute_id',
        'value',
        'slug',
        'color_code',
        'image',
        'position',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'position' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attributeValue) {
            if (empty($attributeValue->slug)) {
                $attributeValue->slug = Str::slug($attributeValue->value);
            }
        });

        static::updating(function ($attributeValue) {
            if ($attributeValue->isDirty('value') && !$attributeValue->isDirty('slug')) {
                $attributeValue->slug = Str::slug($attributeValue->value);
            }
        });
    }

    /**
     * Get the attribute that owns the value.
     */
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }

    /**
     * Get the products for the attribute value.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute_values')
            ->withPivot('product_attribute_id')
            ->withTimestamps();
    }

    /**
     * Scope a query to order values by position.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    /**
     * Get the image URL.
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}