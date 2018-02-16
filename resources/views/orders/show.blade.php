@extends('layouts.app')

@section('content')
<div>{{ $order->block_index }}</div>
<div>{{ $order->expire_index }}</div>
<div>{{ $order->tx_index }}</div>
<div>{{ $order->tx_hash }}</div>
<div>{{ $order->base_remaining_normalized }}</div>
<div>{{ $order->base_quantity_normalized }}</div>
<div>{{ $order->quote_remaining_normalized }}</div>
<div>{{ $order->quote_quantity_normalized }}</div>
<div>{{ $order->duration }}</div>
<div>{{ $order->status }}</div>
@endsection
