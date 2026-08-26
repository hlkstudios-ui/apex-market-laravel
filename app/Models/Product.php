<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'compare_at_price', 'image_url', 'stock', 'featured', 'active'];

    protected function casts(): array
    {
        return ['featured' => 'boolean', 'active' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$'.number_format($this->price / 100, 2);
    }
}
