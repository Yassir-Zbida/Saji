<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductAttribute extends Model
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
        'type',
        'is_required',
        'is_global',
        'is_filterable',
        'is_visible_on_product_page',
        'position',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_required' => 'boolean',
        'is_global' => 'boolean',
        'is_filterable' => 'boolean',
        'is_visible_on_product_page' => 'boolean',
        'position' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attribute) {
            if (empty($attribute->slug)) {
                $attribute->slug = Str::slug($attribute->name);
            }
        });

        static::updating(function ($attribute) {
            if ($attribute->isDirty('name') && !$attribute->isDirty('slug')) {
                $attribute->slug = Str::slug($attribute->name);
            }
        });
    }

    /**
     * Get the values for the attribute.
     */
    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    /**
     * Get the products for the attribute.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute_values')
            ->withPivot('attribute_value_id')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include global attributes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGlobal($query)
    {
        return $query->where('is_global', true);
    }

    /**
     * Scope a query to only include filterable attributes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterable($query)
    {
        return $query->where('is_filterable', true);
    }

    /**
     * Scope a query to only include visible attributes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible_on_product_page', true);
    }

    /**
     * Scope a query to order attributes by position.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }
}