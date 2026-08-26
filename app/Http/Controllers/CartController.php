<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index', ['cart' => $this->cart()]);
    }

    public function store(Request $r, Product $product)
    {
        $d = $r->validate(['quantity' => ['nullable', 'integer', 'min:1', 'max:10']]);
        $qty = $d['quantity'] ?? 1;
        abort_if(! $product->active || $product->stock < $qty, 422, 'Quantity unavailable.');
        $cart = session('cart', []);
        $cart[$product->id] = min(($cart[$product->id] ?? 0) + $qty, $product->stock, 10);
        session(['cart' => $cart]);

        return back()->with('success', $product->name.' added to your cart.');
    }

    public function update(Request $r, Product $product)
    {
        $d = $r->validate(['quantity' => ['required', 'integer', 'min:0', 'max:10']]);
        $cart = session('cart', []);
        if ($d['quantity'] === 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = min($d['quantity'], $product->stock);
        }session(['cart' => $cart]);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Product $product)
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Item removed.');
    }

    private function cart(): array
    {
        $raw = session('cart', []);
        $items = Product::whereIn('id', array_keys($raw))->get()->map(fn ($p) => ['product' => $p, 'quantity' => $raw[$p->id], 'line_total' => $p->price * $raw[$p->id]]);
        $subtotal = $items->sum('line_total');
        $shipping = $subtotal >= 10000 || $subtotal === 0 ? 0 : 1200;

        return compact('items', 'subtotal', 'shipping') + ['total' => $subtotal + $shipping];
    }
}
