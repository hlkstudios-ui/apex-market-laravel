<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::query()
            ->when($request->filled('niche'), fn ($query) => $query->where('niche', $request->string('niche')))
            ->when($request->filled('letter'), fn ($query) => $query->where('name', 'like', $request->string('letter').'%'))
            ->orderBy('name')->get()->groupBy(fn (Brand $brand) => strtoupper(substr($brand->name, 0, 1)));

        return view('brands.index', [
            'brands' => $brands,
            'niches' => Brand::query()->distinct()->orderBy('niche')->pluck('niche'),
            'total' => Brand::count(),
        ]);
    }
}
