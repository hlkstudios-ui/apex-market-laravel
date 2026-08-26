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
        $domains = [
            'a-lange-sohne' => 'alange-soehne.com', 'alaia' => 'maison-alaia.com',
            'christian-dior' => 'dior.com', 'tiffany-co' => 'tiffany.com',
            'iwc-schaffhausen' => 'iwc.com', 'bang-olufsen' => 'bang-olufsen.com',
            'dolce-gabbana' => 'dolcegabbana.com', 'rolls-royce' => 'rolls-roycemotorcars.com',
            'aimé-leon-dore' => 'aimeleondore.com', 'hermès' => 'hermes.com',
            'churchs' => 'church-footwear.com', 'tod-s' => 'tods.com',
            'clè-de-peau-beautè' => 'cledepeaubeaute.com', 'frèdèric-malle' => 'fredericmalle.com',
            'maison-francis-kurkdjian' => 'franciskurkdjian.com', 'patek-philippe' => 'patek.com',
            'jaeger-lecoultre' => 'jaeger-lecoultre.com', 'vancleef-arpels' => 'vancleefarpels.com',
        ];
        $domain = $domains[$this->slug] ?? str_replace('-', '', $this->slug).'.com';

        return "https://icon.horse/icon/{$domain}";
    }
}
