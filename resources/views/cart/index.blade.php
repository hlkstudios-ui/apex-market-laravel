@extends('layouts.app')
@section('title','Your Cart — Apex Market')
@section('content')
<section class="page cart-page">
    <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{route('store.index')}}">Home</a><span>/</span><span>Shopping cart</span></nav>
    <div class="page-heading"><div><p class="eyebrow">Your selection</p><h1>Shopping cart</h1></div>@if(!$cart['items']->isEmpty())<span class="item-count">{{array_sum(session('cart',[]))}} {{Str::plural('item',array_sum(session('cart',[])))}}</span>@endif</div>
    @if($cart['items']->isEmpty())
        <div class="empty cart-empty"><div class="empty-icon">⌁</div><h2>Your cart is empty</h2><p>Looks like you haven't added anything yet.</p><a class="button" href="{{route('store.index')}}">Explore the collection</a></div>
    @else
        <div class="cart-layout">
            <div class="cart-panel">
                <div class="cart-panel-head"><span>Product</span><span>Quantity</span><span>Total</span></div>
                @foreach($cart['items'] as $item)
                <article class="cart-item">
                    <a class="cart-thumb" href="{{route('store.show',$item['product'])}}"><img src="{{$item['product']->image_url}}" alt="{{$item['product']->name}}"></a>
                    <div class="cart-product"><p>{{$item['product']->category->name}}</p><a href="{{route('store.show',$item['product'])}}"><h3>{{$item['product']->name}}</h3></a><span>${{number_format($item['product']->price/100,2)}} each</span><form method="post" action="{{route('cart.destroy',$item['product'])}}">@csrf @method('DELETE')<button class="text-button">Remove</button></form></div>
                    <form class="quantity-form" method="post" action="{{route('cart.update',$item['product'])}}">@csrf @method('PATCH')<label class="sr-only" for="quantity-{{$item['product']->id}}">Quantity</label><select id="quantity-{{$item['product']->id}}" name="quantity" onchange="this.form.submit()">@for($quantity=1;$quantity<=min(10,$item['product']->stock);$quantity++)<option value="{{$quantity}}" @selected($quantity===$item['quantity'])>{{$quantity}}</option>@endfor</select><noscript><button class="text-button">Update</button></noscript></form>
                    <strong class="line-total">${{number_format($item['line_total']/100,2)}}</strong>
                </article>
                @endforeach
                <a class="continue-link" href="{{route('store.index')}}">← Continue shopping</a>
            </div>
            <aside class="summary cart-summary">
                <h2>Order summary</h2>
                <div class="summary-lines"><p><span>Subtotal</span><b>${{number_format($cart['subtotal']/100,2)}}</b></p><p><span>Shipping</span><b class="shipping-value">{{$cart['shipping']?'$'.number_format($cart['shipping']/100,2):'Free'}}</b></p></div>
                @if($cart['shipping'])<div class="shipping-progress"><span><i style="width:{{min(100,$cart['subtotal']/100)}}%"></i></span><small>Add ${{number_format((10000-$cart['subtotal'])/100,2)}} more for free shipping</small></div>@else<div class="shipping-earned">✓ You've earned free shipping</div>@endif
                <div class="summary-total"><span><b>Total</b><small>Including applicable taxes</small></span><strong>${{number_format($cart['total']/100,2)}} <small>CAD</small></strong></div>
                <a class="button checkout-button" href="{{route('checkout.create')}}">Proceed to checkout <span>→</span></a>
                <div class="secure-note"><span>⌾</span><p><b>Secure checkout</b><small>Your information is protected.</small></p></div>
            </aside>
        </div>
    @endif
</section>
@endsection
