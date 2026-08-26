@extends('layouts.app')
@section('title','Designers A–Z — Apex Privé')
@section('content')
<section class="brand-directory">
    <header class="directory-hero"><p class="eyebrow">The house directory</p><h1>Designers A–Z</h1><p>Discover {{$total}} defining names across fashion, horology, jewelry, beauty, interiors and travel.</p><div class="directory-stats"><span><b>{{$total}}</b> maisons</span><span><b>{{$niches->count()}}</b> departments</span><span><b>A–Z</b> discovery</span></div></header>
    <div class="directory-layout">
        <aside class="directory-sidebar"><div><p>Browse by department</p><a class="{{request('niche')?'':'active'}}" href="{{route('brands.index')}}"><span>All maisons</span><b>{{$total}}</b></a>@foreach($niches as $niche)<a class="{{request('niche')===$niche?'active':''}}" href="{{route('brands.index',['niche'=>$niche])}}"><span>{{$niche}}</span><b>{{$nicheCounts[$niche]}}</b></a>@endforeach</div><p class="logo-note">Logos identify their respective trademark owners. Apex Privé is an independent marketplace.</p></aside>
        <div class="directory-content">
            <div class="directory-toolbar"><p><b>{{$brands->flatten()->count()}}</b> designers</p><div class="alphabet"><a href="{{route('brands.index',request()->except('letter'))}}">All</a>@foreach(range('A','Z') as $letter)<a class="{{request('letter')===$letter?'active':''}}" href="{{route('brands.index',array_merge(request()->except('letter'),['letter'=>$letter]))}}">{{$letter}}</a>@endforeach</div></div>
            <div class="brand-groups">@forelse($brands as $letter=>$group)<section id="letter-{{$letter}}"><h2>{{$letter}}</h2><div class="brand-logo-grid">@foreach($group as $brand)<a class="brand-logo-card" href="{{route('store.index',['search'=>$brand->name])}}"><div class="brand-mark"><span class="brand-fallback">{{strtoupper(substr($brand->name,0,2))}}</span><img src="{{$brand->logo_url}}" alt="{{$brand->name}} logo" loading="lazy" onerror="this.style.display='none'"></div><div><strong>{{$brand->name}}</strong><small>{{$brand->niche}}</small></div><span class="brand-arrow">↗</span></a>@endforeach</div></section>@empty<div class="empty"><h2>No maisons found</h2><p>Try another letter or department.</p></div>@endforelse</div>
        </div>
    </div>
</section>
@endsection
