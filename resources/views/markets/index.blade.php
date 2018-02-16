@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col">
        <ul>
            @foreach($markets as $market)
            <li><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->name }}</a></li>
            @endforeach
        </div>
        </ul>
    </div>
</div>
@endsection
