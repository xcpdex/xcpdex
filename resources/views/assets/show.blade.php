@extends('layouts.app')

@section('title', $asset->long_name ? $asset->long_name : $asset->name)

@section('content')

  <div class="row">
    <div class="col-md-7">
      <h1>{{ $asset->long_name ? $asset->long_name : $asset->name }}</h1>
      <p class="lead">{{ $asset->description }}</p>
    </div>
    <div class="col-md-5">
      <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
          <tbody>
            <tr>
              <td>Markets: <br /><b>{{ $markets->count() }}</b></td>
              <td>Locked: <br /><b class="{{ $asset->locked ? 'text-success' : 'text-danger' }}">{{ $asset->locked ? 'YES' : 'NO' }}</b></td>
              <td>Divisible: <br /><b class="{{ $asset->divisible ? 'text-success' : 'text-danger' }}">{{ $asset->divisible ? 'YES' : 'NO' }}</b></td>
            </tr>
            <tr class="active">
              <td colspan="3">Issuance: <b>{{ $asset->divisible ? $asset->issuance_normalized : number_format($asset->issuance_normalized) }}</b></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>#</th>
        <th>Market</th>
        <th>Orders</th>
        <th>Matches</th>
      </tr>
    </thead>
    <tbody>
      @foreach($markets as $market)
      <tr>
        <th>{{ $loop->iteration }}</th>
        <td><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->name }}</a></td>
        <td><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->orders_count }}</a></td>
        <td><a href="{{ url(route('markets.show', ['market' => $market->slug])) }}">{{ $market->order_matches_count }}</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection