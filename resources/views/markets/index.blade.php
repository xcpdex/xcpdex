@extends('layouts.app')

@section('title', 'Markets')

@section('content')
<markets filter="{{ $request->input('filter', 'index') }}" order_by="{{ $request->input('order_by', 'quote_volume_usd_month') }}" direction="{{ $request->input('direction', 'desc') }}"></markets>
@endsection
