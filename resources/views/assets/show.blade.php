@extends('layouts.app')

@section('title', $asset->long_name ? $asset->long_name : $asset->name)

@section('sidebar')
@if(isset($asset->meta['template']))
  @include('partials.' . $asset->meta['template'], ['asset' => $asset])
  @include('partials.image-modal', ['asset' => $asset])
@endif
@endsection

@section('content')

  <div class="row">
    <div class="col-md-7">
      <h1>{{ $asset->long_name ? $asset->long_name : $asset->name }}</h1>
      <p class="lead">{{ strip_tags($asset->display_description) }}</p>
    </div>
    <div class="col-md-5">
      <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
          <tbody>
            <tr>
              <td><img src="{{ $asset->display_icon_url }}" height="44" /></td>
              <td>Volume <small>USD</small> <br /><b>{{ number_format($total_volume) }}</b></td>
              <td>Orders <br /><b>{{ number_format($total_orders) }}</b></td>
              <td>Matches <br /><b>{{ number_format($total_matches) }}</b></td>
            </tr>
            <tr class="bg-light">
              @if(isset($asset->meta['burned']) && $asset->meta['burned'])
              <td colspan="4">Supply: <b title="{{ $asset->issuance_normalized - $asset->meta['burned'] }} {{ $asset->name }}">{{ number_format($asset->issuance_normalized - $asset->meta['burned']) }}</b> &nbsp; <small>Burned: <b title="{{ $asset->meta['burned'] }} {{ $asset->name }}">{{ number_format($asset->meta['burned']) }}</b></small></td>
              @else
              <td colspan="4">Supply: <b title="{{ $asset->issuance_normalized }} {{ $asset->name }}">{{ number_format($asset->issuance_normalized) }} {{ $asset->name }}</b></td>
              @endif
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <asset-markets asset="{{ $asset->name }}" filter="{{ $request->input('filter', 'index') }}" order_by="{{ $request->input('order_by', 'quote_market_cap_usd') }}" direction="{{ $request->input('direction', 'desc') }}"></asset-markets>

@endsection