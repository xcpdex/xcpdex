@extends('layouts.app')

@section('title', 'Block #' . $block->block_index)

@section('content')
<h1 class="mb-3">Block #{{ $block->block_index }}</h1>
<div class="table-responsive">
  <table class="table table-sm table-bordered">
    <tbody>
      <tr>
        <th>Hash</th>
        <td>{{ $block->block_hash }}</td>
      </tr>
      <tr>
        <th>Previous</th>
        <td>{{ $block->previous_block_hash }}</td>
      </tr>
      <tr>
        <th>Difficulty</th>
        <td>{{ $block->difficulty }}</td>
      </tr>
      <tr>
        <th>Timestamp</th>
        <td>{{ $block->mined_at }} EST</td>
      </tr>
    </tbody>
  </table>
</div>
@endsection