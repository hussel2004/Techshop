<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock_quantity',
        'brand',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product');
    }

    public function getActivePromotionAttribute()
    {
        // Direct product promotions take priority
        $directPromo = $this->promotions()
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('discount_percentage', 'desc')
            ->first();

        if ($directPromo) {
            return $directPromo;
        }

        // Check category promotions
        if ($this->category) {
            $categoryPromo = $this->category->promotions()
                ->where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->orderBy('discount_percentage', 'desc')
                ->first();

            return $categoryPromo;
        }

        return null;
    }

    public function getDiscountedPriceAttribute()
    {
        $promotion = $this->active_promotion;

        if (!$promotion) {
            return $this->price;
        }

        $discount = ($this->price * $promotion->discount_percentage) / 100;
        return $this->price - $discount;
    }

    public function getHasActivePromotionAttribute(): bool
    {
        return $this->active_promotion !== null;
    }

    public function getPrimaryImageUrlAttribute()
    {
        $primaryImage = $this->primaryImage;
        if ($primaryImage) {
            return asset('storage/' . $primaryImage->image_path);
        }

        // Return placeholder if no image
        return asset('images/no-image.png');
    }
}
