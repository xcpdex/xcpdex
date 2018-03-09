@extends('layouts.app')

@section('title', $project->name)

@section('content')
<project-assets name="{{ $project->name }}" project="{{ $project->slug }}" filter="{{ $request->input('filter', 'index') }}" order_by="{{ $request->input('order_by', 'volume_total_usd') }}" direction="{{ $request->input('direction', 'desc') }}"></project-assets>
@endsection
