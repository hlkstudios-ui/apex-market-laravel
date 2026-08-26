<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'brand'])->where('active', true)->when($request->filled('search'), fn ($q) => $q->where(fn ($x) => $x->where('name', 'like', '%'.$request->string('search').'%')->orWhere('description', 'like', '%'.$request->string('search').'%')->orWhereHas('brand', fn ($brand) => $brand->where('name', 'like', '%'.$request->string('search').'%'))))->when($request->filled('category'), fn ($q) => $q->whereHas('category', fn ($x) => $x->where('slug', $request->string('category'))))->when($request->input('sort') === 'price-low', fn ($q) => $q->orderBy('price'))->when($request->input('sort') === 'price-high', fn ($q) => $q->orderByDesc('price'))->when(! in_array($request->input('sort'), ['price-low', 'price-high']), fn ($q) => $q->latest())->paginate(9)->withQueryString();

        return view('store.index', ['products' => $products, 'categories' => Category::withCount('products')->get(), 'featuredBrands' => Brand::where('featured', true)->orderBy('name')->take(12)->get()]);
    }

    public function show(Product $product)
    {
        abort_unless($product->active, 404);

        return view('store.show', ['product' => $product->load(['category', 'brand']), 'related' => Product::with('brand')->where('category_id', $product->category_id)->whereKeyNot($product)->where('active', true)->take(3)->get()]);
    }
}
