<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function mark(Brand $brand)
    {
        $name = htmlspecialchars($brand->name, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $initials = collect(preg_split('/\s+/', $brand->name))->map(fn ($word) => mb_substr($word, 0, 1))->take(2)->join('');
        $initials = htmlspecialchars(mb_strtoupper($initials), ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $size = mb_strlen($brand->name) > 18 ? 25 : (mb_strlen($brand->name) > 11 ? 31 : 38);
        $svg = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 520 150" role="img" aria-label="{$name} wordmark">
          <rect width="520" height="150" fill="#f5f1e8"/>
          <circle cx="65" cy="75" r="42" fill="none" stroke="#aa8550" stroke-width="1"/>
          <text x="65" y="84" text-anchor="middle" font-family="Georgia, 'Times New Roman', serif" font-size="26" fill="#aa8550" letter-spacing="1">{$initials}</text>
          <line x1="122" y1="44" x2="122" y2="106" stroke="#d8d0c2"/>
          <text x="150" y="83" font-family="Georgia, 'Times New Roman', serif" font-size="{$size}" fill="#10100f" letter-spacing="1">{$name}</text>
          <text x="152" y="108" font-family="Arial, sans-serif" font-size="8" fill="#807a71" letter-spacing="3">MAISON</text>
        </svg>
        SVG;

        return response($svg, 200, ['Content-Type' => 'image/svg+xml', 'Cache-Control' => 'public, max-age=86400']);
    }

    public function index(Request $request)
    {
        $brands = Brand::query()
            ->when($request->filled('niche'), fn ($query) => $query->where('niche', $request->string('niche')))
            ->when($request->filled('letter'), fn ($query) => $query->where('name', 'like', $request->string('letter').'%'))
            ->orderBy('name')->get()->groupBy(fn (Brand $brand) => strtoupper(substr($brand->name, 0, 1)));

        $nicheCounts = Brand::query()->selectRaw('niche, count(*) as total')->groupBy('niche')->orderBy('niche')->pluck('total', 'niche');

        return view('brands.index', [
            'brands' => $brands,
            'niches' => $nicheCounts->keys(),
            'nicheCounts' => $nicheCounts,
            'total' => Brand::count(),
        ]);
    }
}
