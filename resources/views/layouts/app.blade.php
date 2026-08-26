<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>@yield('title','Apex Privé')</title><meta name="description" content="A curated global marketplace for exceptional luxury.">@vite(['resources/css/app.css','resources/js/app.js'])</head><body>
<div class="house-service-bar" aria-label="Client services">
    <span>Private client service</span>
    <span>Complimentary insured delivery</span>
    <button type="button" aria-label="Language and region: Canada, English">Canada&nbsp; / &nbsp;EN</button>
</div>
<header class="site-header house-header">
    <div class="house-nav-main">
        <button class="mobile-toggle house-menu-toggle" type="button" aria-label="Open navigation" aria-controls="house-menu" aria-expanded="false">
            <span></span><span></span>
        </button>

        <form class="house-search" action="{{route('store.index')}}" method="get">
            <label class="sr-only" for="nav-search">Search the collection</label>
            <input id="nav-search" name="search" value="{{request('search')}}" placeholder="Search the collection" autocomplete="off">
            <button type="submit" aria-label="Search">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.2 4.2"/></svg>
            </button>
        </form>

        <a class="house-wordmark" href="{{route('store.index')}}" aria-label="Apex Privé home">
            <span>APEX <em>PRIVÉ</em></span>
            <small>The house of distinction</small>
        </a>

        <div class="house-actions" aria-label="Account and shopping">
            <a class="house-account" href="{{route('checkout.create')}}">Client account</a>
            <a class="house-orders" href="{{route('cart.index')}}">Orders</a>
            <a class="house-bag" href="{{route('cart.index')}}" aria-label="Shopping bag, {{array_sum(session('cart',[]))}} items">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5.5 8.5h13l-.7 12h-11.6l-.7-12Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg>
                <span>Bag</span><b>{{array_sum(session('cart',[]))}}</b>
            </a>
        </div>
    </div>

    <nav id="house-menu" class="nav-secondary house-menu" aria-label="Luxury departments">
        <a href="{{route('brands.index')}}">Designers A–Z</a>
        <a href="{{route('store.index')}}">The collection</a>
        <a href="{{route('brands.index',['niche'=>'Fashion Houses'])}}">Fashion</a>
        <a href="{{route('brands.index',['niche'=>'Watches'])}}">Watches</a>
        <a href="{{route('brands.index',['niche'=>'Jewelry'])}}">Jewelry</a>
        <a href="{{route('brands.index',['niche'=>'Beauty & Fragrance'])}}">Beauty</a>
        <a href="{{route('brands.index',['niche'=>'Home & Design'])}}">Home &amp; Design</a>
        <a class="house-private-link" href="#site-footer">Private sourcing</a>
    </nav>
</header>
@if(session('success'))<div class="flash">{{session('success')}}</div>@endif
<main>@yield('content')</main><footer id="site-footer"><div><div class="brand inverse">APEX<span>PRIVÉ</span></div><p>Exceptional objects. Impeccable provenance.</p></div><div><b>Discover</b><a href="{{route('brands.index')}}">Designers A–Z</a><a href="{{route('store.index')}}">The collection</a></div><div><b>Client services</b><span>Authenticity guarantee</span><span>Insured delivery</span><span>Private sourcing</span></div><small>© {{date('Y')}} Apex Privé · Independent luxury marketplace</small></footer></body></html>
