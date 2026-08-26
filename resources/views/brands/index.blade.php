@extends('layouts.app')
@section('title','Designers A–Z — Apex Privé')
@section('content')
<section class="brand-directory">
    <header class="directory-hero"><p class="eyebrow">Icons of exceptional craft</p><h1>The Luxury <em>Index</em></h1><p>Explore storied maisons and contemporary visionaries selected for their heritage, artistry and enduring influence.</p></header>
    <div class="directory-stats" aria-label="Directory overview"><span><b>{{$total}}</b><small>Exceptional houses</small></span><span><b>{{$niches->count()}}</b><small>Curated worlds</small></span><span><b>Global</b><small>Legacy and vision</small></span></div>
    <div class="directory-layout">
        <aside class="directory-sidebar"><div><p>Browse by department</p><a class="{{request('niche')?'':'active'}}" href="{{route('brands.index')}}"><span>All maisons</span><b>{{$total}}</b></a>@foreach($niches as $niche)<a class="{{request('niche')===$niche?'active':''}}" href="{{route('brands.index',['niche'=>$niche])}}"><span>{{$niche}}</span><b>{{$nicheCounts[$niche]}}</b></a>@endforeach</div><p class="logo-note">Logos identify their respective trademark owners. Apex Privé is an independent marketplace.</p></aside>
        <div class="directory-content">
            <div class="directory-toolbar"><p><b>{{$brands->flatten()->count()}}</b> designers</p><div class="alphabet"><a href="{{route('brands.index',request()->except('letter'))}}">All</a>@foreach(range('A','Z') as $letter)<a class="{{request('letter')===$letter?'active':''}}" href="{{route('brands.index',array_merge(request()->except('letter'),['letter'=>$letter]))}}">{{$letter}}</a>@endforeach</div></div>
            <div class="brand-groups">@forelse($brands as $letter=>$group)<section id="letter-{{$letter}}"><h2>{{$letter}}</h2><div class="brand-logo-grid">@foreach($group as $brand)<a class="brand-logo-card" href="{{route('store.index',['search'=>$brand->name])}}"><span class="brand-index">{{str_pad($loop->iteration,2,'0',STR_PAD_LEFT)}}</span><div class="brand-mark"><img src="{{$brand->logo_url}}" alt="{{$brand->name}} wordmark" loading="lazy" onerror="this.onerror=null;this.src='{{route('brands.mark',$brand)}}?v=2'"></div><div class="brand-meta"><small>{{$brand->niche}}</small><span>Discover <i>↗</i></span></div></a>@endforeach</div></section>@empty<div class="empty"><h2>No maisons found</h2><p>Try another letter or department.</p></div>@endforelse</div>
        </div>
    </div>
</section>
@endsection
