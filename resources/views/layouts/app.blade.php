<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>@yield('title','Apex Privé')</title><meta name="description" content="A curated global marketplace for exceptional luxury.">@vite(['resources/css/app.css','resources/js/app.js'])</head><body>
<div class="announcement">Complimentary insured delivery <span>•</span> Private client service</div>
<header class="site-header">
<div class="nav-main">
    <button class="mobile-toggle" type="button" aria-label="Open navigation" aria-expanded="false">☰</button>
    <a class="brand nav-brand" href="{{route('store.index')}}">APEX<span>PRIVÉ</span></a>
    <a class="delivery nav-tile" href="#site-footer"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s7-6.2 7-13A7 7 0 1 0 5 8c0 6.8 7 13 7 13Zm0-10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/></svg><span><small>Deliver to</small><b>Canada</b></span></a>
    <form class="nav-search" action="{{route('store.index')}}" method="get">
        <label class="sr-only" for="nav-search">Search products</label>
        <select name="category" aria-label="Choose a category"><option value="">All</option><option value="home-living" @selected(request('category')==='home-living')>Home</option><option value="workspace" @selected(request('category')==='workspace')>Workspace</option><option value="carry" @selected(request('category')==='carry')>Carry</option></select>
        <input id="nav-search" name="search" value="{{request('search')}}" placeholder="Search designers and pieces" autocomplete="off">
        <button type="submit" aria-label="Search"><svg viewBox="0 0 24 24"><path d="m21 21-4.4-4.4m2.4-5.1a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z"/></svg></button>
    </form>
    <button class="language nav-tile" type="button" aria-label="Language"><span>🇨🇦</span> <b>EN</b><i>▾</i></button>
    <a class="nav-account nav-tile" href="{{route('checkout.create')}}"><small>Hello, sign in</small><b>Account &amp; Lists <i>▾</i></b></a>
    <a class="nav-orders nav-tile" href="{{route('cart.index')}}"><small>Returns</small><b>&amp; Orders</b></a>
    <a class="cart-link nav-tile" href="{{route('cart.index')}}"><span class="cart-icon"><span class="cart-count">{{array_sum(session('cart',[]))}}</span><svg viewBox="0 0 32 27" aria-hidden="true"><path d="M2 2h4l3.2 15.5h15.6L29 7H8M11 24h.1M24 24h.1"/></svg></span><strong>Cart</strong></a>
</div>
<nav class="nav-secondary" aria-label="Store departments">
    <button class="all-menu" type="button"><span>☰</span> All</button>
    <a href="{{route('brands.index')}}">Designers</a><a href="{{route('brands.index',['niche'=>'Fashion Houses'])}}">Fashion</a><a href="{{route('brands.index',['niche'=>'Watches'])}}">Watches</a><a href="{{route('brands.index',['niche'=>'Jewelry'])}}">Jewelry</a><a href="{{route('brands.index',['niche'=>'Beauty & Fragrance'])}}">Beauty</a><a href="{{route('brands.index',['niche'=>'Home & Design'])}}">Home &amp; Art</a>
    <a class="nav-promo" href="{{route('brands.index')}}">Explore 100+ maisons →</a>
</nav>
</header>
@if(session('success'))<div class="flash">{{session('success')}}</div>@endif
<main>@yield('content')</main><footer id="site-footer"><div><div class="brand inverse">APEX<span>PRIVÉ</span></div><p>Exceptional objects. Impeccable provenance.</p></div><div><b>Discover</b><a href="{{route('brands.index')}}">Designers A–Z</a><a href="{{route('store.index')}}">The collection</a></div><div><b>Client services</b><span>Authenticity guarantee</span><span>Insured delivery</span><span>Private sourcing</span></div><small>© {{date('Y')}} Apex Privé · Independent luxury marketplace</small></footer></body></html>
