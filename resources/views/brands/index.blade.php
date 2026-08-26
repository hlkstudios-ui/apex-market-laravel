@extends('layouts.app')
@section('title','Designers A–Z — Apex Privé')
@section('content')
<section class="brand-directory"><div class="directory-hero"><p class="eyebrow">The house directory</p><h1>Designers A–Z</h1><p>Discover {{$total}} of the world's most significant names across fashion, watches, jewelry, beauty, interiors and travel.</p></div>
<form class="directory-filters"><select name="niche" onchange="this.form.submit()"><option value="">Every department</option>@foreach($niches as $niche)<option value="{{$niche}}" @selected(request('niche')===$niche)>{{$niche}}</option>@endforeach</select><div class="alphabet"><a href="{{route('brands.index',request()->except('letter'))}}">All</a>@foreach(range('A','Z') as $letter)<a class="{{request('letter')===$letter?'active':''}}" href="{{route('brands.index',array_merge(request()->except('letter'),['letter'=>$letter]))}}">{{$letter}}</a>@endforeach</div></form>
<div class="brand-groups">@forelse($brands as $letter=>$group)<section id="letter-{{$letter}}"><h2>{{$letter}}</h2><div>@foreach($group as $brand)<a href="{{route('store.index',['search'=>$brand->name])}}"><span>{{$brand->name}}</span><small>{{$brand->niche}}</small></a>@endforeach</div></section>@empty<div class="empty"><h2>No maisons found</h2><p>Try another letter or department.</p></div>@endforelse</div></section>
@endsection
