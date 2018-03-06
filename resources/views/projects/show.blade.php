@extends('layouts.app')

@section('title', $project->name)

@section('content')
<h1>{{ $project->name }}</h1>
<p class="lead">Explainer Text</p>
<div class="row">
@foreach($assets as $asset)
  <div class="col-sm-2">
    <a href="{{ url(route('assets.show', ['asset' => $asset->name])) }}"><img src="{{ $asset->image_url }}" width="100%" /></a>
  </div>
@endforeach
{!! $assets->links() !!}
</div>
@endsection
