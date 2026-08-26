<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create()
    {
        $cart = $this->cart();

        return $cart['items']->isEmpty() ? redirect()->route('cart.index') : view('checkout.create', compact('cart'));
    }

    public function store(CheckoutRequest $request)
    {
        $cart = $this->cart();
        abort_if($cart['items']->isEmpty(), 422, 'Your cart is empty.');
        $order = DB::transaction(function () use ($request, $cart) {
            foreach ($cart['items'] as $item) {
                $p = Product::lockForUpdate()->findOrFail($item['product']->id);
                abort_if($p->stock < $item['quantity'], 422, $p->name.' has insufficient stock.');
                $p->decrement('stock', $item['quantity']);
            }$o = Order::create($request->validated() + ['number' => (string) Str::uuid(), 'subtotal' => $cart['subtotal'], 'shipping' => $cart['shipping'], 'total' => $cart['total']]);
            foreach ($cart['items'] as $i) {
                $o->items()->create(['product_id' => $i['product']->id, 'product_name' => $i['product']->name, 'unit_price' => $i['product']->price, 'quantity' => $i['quantity'], 'line_total' => $i['line_total']]);
            }

return $o;
        });
        session()->forget('cart');

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        return view('checkout.success', ['order' => $order->load('items')]);
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
