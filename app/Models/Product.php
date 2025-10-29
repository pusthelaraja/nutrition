<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock_quantity',
        'manage_stock',
        'in_stock',
        'weight',
        'featured_image',
        'attributes',
        'is_active',
        'is_featured',
        'sort_order'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'manage_stock' => 'boolean',
        'in_stock' => 'boolean',
        'attributes' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function featuredImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_featured', true);
    }

    /**
     * Get the featured image path for easy access
     */
    public function getFeaturedImageAttribute()
    {
        $featuredImage = \App\Models\ProductImage::where('product_id', $this->id)
            ->where('is_featured', true)
            ->first();
        return $featuredImage ? $featuredImage->image_path : null;
    }

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        $featuredImage = \App\Models\ProductImage::where('product_id', $this->id)
            ->where('is_featured', true)
            ->first();
        return $featuredImage ? $featuredImage->image_url : null;
    }

    /**
     * Get the featured image path (for direct database field access)
     */
    public function getFeaturedImagePathAttribute()
    {
        $featuredImage = \App\Models\ProductImage::where('product_id', $this->id)
            ->where('is_featured', true)
            ->first();
        return $featuredImage ? $featuredImage->image_path : null;
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function inventoryReports()
    {
        return $this->hasMany(InventoryReport::class);
    }

    /**
     * Activity log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price', 'sale_price', 'stock_quantity', 'featured_image', 'is_active', 'is_featured'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('products');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Product {$eventName}";
    }
}
