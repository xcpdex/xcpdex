@extends('layouts.app')

@section('title', $slug)

@section('content')
<address-orders source="{{ $slug }}"></address-orders>
@endsection
