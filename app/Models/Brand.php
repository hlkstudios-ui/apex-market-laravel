<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'niche', 'country', 'featured'];

    protected function casts(): array
    {
        return ['featured' => 'boolean'];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getLogoUrlAttribute(): string
    {
        if (is_file(public_path("brand-logos/{$this->slug}.svg"))) {
            return asset("brand-logos/{$this->slug}.svg");
        }

        return route('brands.mark', $this).'?v=2';
    }
}
