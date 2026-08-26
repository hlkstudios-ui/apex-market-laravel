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
        $verifiedSvgFiles = [
            'chanel' => 'Chanel logo.svg',
            'gucci' => 'Gucci logo.svg',
            'hermes' => 'Hermes wordmark.svg',
            'rolex' => 'Rolex wordmark logo.svg',
        ];

        if (isset($verifiedSvgFiles[$this->slug])) {
            return 'https://commons.wikimedia.org/wiki/Special:Redirect/file/'.rawurlencode($verifiedSvgFiles[$this->slug]);
        }

        return route('brands.mark', $this);
    }
}
