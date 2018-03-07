@extends('layouts.app')

@section('title', 'Mempool')

@section('content')

<h1 class="mb-3">Mempool <small class="lead">Unconfirmed Transactions</small></h1>

<div class="table-responsive">
<table class="table table-hover">
    <thead>
        <tr>
            <th>Timestamp</th>
            <th>Command</th>
            <th>Category</th>
            <th>Tx Hash</th>
            <th>Bindings</th>
        </tr>
    </thead>
    <tbody>
    @if ( count($txs) )
        @foreach( $txs as $tx )
            @if($tx['category'] === 'orders')
            <tr>
                <td>{{ $tx['timestamp'] }}</td>
                <td>{{ $tx['command'] }}</td>
                <td>{{ $tx['category'] }}</td>
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