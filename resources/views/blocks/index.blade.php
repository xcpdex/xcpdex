@extends('layouts.app')

@section('title', 'Blocks')

@section('content')
<h1 class="mb-3">Blocks <small class="lead">{{ $blocks->total() }} Found</small></h1>
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead class="text-left">
      <tr>
        <th>#</th>
        <th>Orders</th>
        <th>Matches</th>
        <th>Mined At</th>
      </tr>
    </thead>
    <tbody>
      @foreach($blocks as $block)
      <tr>
        <td><a href="{{ url(route('blocks.show', ['block_index' => $block->block_index])) }}">{{ $block->block_index }}</a></td>
        <td>{{ $block->orders_count }}</td>
        <td>{{ $block->order_matches_count }}</td>
        <td>{{ $block->mined_at }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
{!! $blocks->links('pagination::bootstrap-4') !!}
@endsection
