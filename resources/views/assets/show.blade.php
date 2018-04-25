@extends('layouts.app')

@section('title', $asset->display_name)

@section('description', $asset->display_name . ' - Trading Pairs, Market Prices & Dex Volume - ' . $asset->display_description ? strip_tags($asset->display_description) : 'Learn more on XCPDEX.com')

@section('sidebar')
@if(isset($asset->meta['template']))
  @include('partials.' . $asset->meta['template'], ['asset' => $asset])
  @include('partials.image-modal', ['asset' => $asset])
@endif
@endsection

@section('content')

  <div class="row">
    <div class="col-md-7">
      <h1>{{ $asset->display_name }}</h1>
      <p class="lead">{{ $asset->display_description ? strip_tags($asset->display_description) : 'Trading Pairs, Market Price & Dex Volume.' }}</p>
    </div>
    <div class="col-md-5">
      <div class="table-responsive">
        <table class="table table-sm table-bordered text-center">
          <tbody>
            <tr>
              <td><img src="{{ $asset->display_icon_url }}" height="44" /></td>
              <td title="All-Time">Volume <small>USD</small> <br /><b>${{ $asset->volume_total_usd > 1000 ? number_format($asset->volume_total_usd) : number_format($asset->volume_total_usd, 2) }}</b></td>
              <td title="All-Time">Orders <br /><b>{{ number_format($asset->orders_total) }}</b></td>
              <td title="All-Time">Matches <br /><b>{{ number_format($asset->order_matches_total) }}</b></td>
            </tr>
            <tr class="bg-light">
              @if(isset($asset->meta['burned']) && $asset->meta['burned'])
              <td colspan="4">Supply: <b title="{{ $asset->issuance_normalized - $asset->meta['burned'] }} {{ $asset->display_name }}">{{ number_format($asset->issuance_normalized - $asset->meta['burned']) }}</b> &nbsp; <small>Burned: <b title="{{ $asset->meta['burned'] }} {{ $asset->display_name }}">{{ number_format($asset->meta['burned']) }}</b></small></td>
              @else
              <td colspan="4">Supply: <b title="{{ $asset->issuance_normalized }} {{ $asset->display_name }}">{{ number_format($asset->issuance_normalized) }} {{ $asset->display_name }}</b></td>
              @endif
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if($asset->name === 'GEMZ')
  <div class="alert alert-warning" role="alert">
    <strong>Alert:</strong> GetGems Update October 27, 2016. (<a href="https://medium.com/@GetGems/getgems-update-october-27-2016-e8e3d28d38b9" target="_blank">Source</a>)
  </div>
  @elseif($asset->name === 'SJCX')
  <div class="alert alert-danger" role="alert">
    <strong>Alert:</strong> The Storj project migrated to an ERC-20 token. (<a href="https://blog.storj.io/post/158740607128/migration-from-counterparty-to-ethereum" target="_blank">Source 1</a>, <a href="https://docs.storj.io/docs/migrate-tokens-from-sjcx-to-storj" target="_blank">Source 2</a>)
  </div>
  @elseif($asset->name === 'WILLCOIN' || isset($asset->meta['template']) && $asset->meta['template'] === 'force-of-will')
  <div class="alert alert-warning" role="alert">
    <strong>Alert:</strong> Force of Will (FoW) has postponed their use of Counterparty. (<a href="https://medium.com/book-of-orbs/project-orb-update-apr-13th-4d6351420743" target="_blank">Source</a>)
  </div>
  @elseif(isset($asset->meta['template']) && $asset->meta['template'] === 'bitgirls')
  <div class="alert alert-warning" role="alert">
    <strong>Alert:</strong> The BitGirls project ended on March 31, 2017. (<a href="http://bitgirls.io/en/" target="_blank">Source</a>)
  </div>
  @endif

  @if($asset->image_url)
    <img src="{{ $asset->image_url }}" alt="{{ $asset->name }}" width="100%" height="auto" class="mb-3 d-block d-sm-none" />
  @endif

  <asset-markets asset="{{ $asset->slug }}" filter="{{ $request->input('filter', 'index') }}" order_by="{{ $request->input('order_by', 'quote_volume_usd_month') }}" direction="{{ $request->input('direction', 'desc') }}"></asset-markets>

@endsection