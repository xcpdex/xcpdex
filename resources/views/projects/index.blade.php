@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<h1 class="mb-3">Projects <small class="lead">{{ $projects->count() }} Found</small></h1>
<div class="row">
@foreach($projects as $project)
  <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
    <div class="card">
      <a href="{{ url(route('projects.show', ['project' => $project->slug])) }}">
        <img class="card-img-top" src="{{ count($project->assets) ? $project->assets->sortByDesc('volume_total_usd')->first()->image_url : '' }}" width="100%" />
      </a>
      <div class="card-body">
        <h5 class="card-title">
          {{ $project->name }}
        </h5>
        <p class="card-text">{{ $project->assets->count() }} Assets</p>
      </div>
    </div>
  </div>
@endforeach
</div>
@endsection
