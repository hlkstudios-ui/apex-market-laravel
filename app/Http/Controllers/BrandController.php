<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function mark(Brand $brand)
    {
        $name = htmlspecialchars($brand->name, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $size = mb_strlen($brand->name) > 18 ? 28 : (mb_strlen($brand->name) > 11 ? 36 : 46);
        $svg = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 520 150" role="img" aria-label="{$name} wordmark">
          <rect width="520" height="150" fill="transparent"/>
          <text x="260" y="78" text-anchor="middle" font-family="Georgia, 'Times New Roman', serif" font-size="{$size}" fill="#10100f" letter-spacing="2">{$name}</text>
          <line x1="225" y1="102" x2="295" y2="102" stroke="#aa8550" stroke-width="1"/>
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
