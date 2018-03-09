@extends('layouts.app')

@section('title', 'Mempool')

@section('content')

<h1 class="mb-3">Mempool <small class="lead">Unconfirmed Transactions</small></h1>

<div class="table-responsive table-dex">
<table class="table table-striped table-sm">
    <thead>
        <tr>
            <th>First Seen</th>
            <th>Source</th>
            <th>Give Asset</th>
            <th>Get Asset</th>
            <th>Tx Hash</th>
            <th>Bindings</th>
        </tr>
    </thead>
    <tbody>
    @if ( count($txs) )
        @foreach( $txs as $tx )
            @if($tx['category'] === 'orders')
            <tr>
                <td>{{ str_replace('after', 'ago', \Carbon\Carbon::now()->diffForHumans(\Carbon\Carbon::createFromTimestamp($tx['timestamp'], 'America/New_York'))) }}</td>
                <td><a href="{{ url(route('addresses.show', ['slug' => json_decode($tx['bindings'])->source])) }}">{{ json_decode($tx['bindings'])->source }}</a></td>
                <td><a href="{{ url(route('assets.show', ['asset' => json_decode($tx['bindings'])->give_asset])) }}">{{ json_decode($tx['bindings'])->give_asset }}</a></td>
                <td><a href="{{ url(route('assets.show', ['asset' => json_decode($tx['bindings'])->get_asset])) }}">{{ json_decode($tx['bindings'])->get_asset }}</a></td>
                <td>{{ $tx['tx_hash'] }}</td>
                <td>{{ $tx['bindings'] }}</td>
            </tr>
            @endif
        @endforeach
    @endif
    </tbody>
</table>
</div>

</div>
@endsection