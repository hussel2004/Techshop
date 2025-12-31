<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'discount_percentage',
        'start_date',
        'end_date',
        'applies_to',
        'is_active'
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($promotion) {
            if (empty($promotion->slug)) {
                $promotion->slug = Str::slug($promotion->name);
            }
        });
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'promotion_category');
    }

    public function isActive(): bool
    {
        return $this->is_active &&
               now()->between($this->start_date, $this->end_date);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('start_date', '<=', now())
                     ->where('end_date', '>=', now());
    }
}
