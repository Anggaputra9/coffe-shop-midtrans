@extends('admin.layouts.app')

@section('title', 'Edit Menu')
@section('subtitle', 'Perbarui informasi menu.')

@section('content')
    @include('admin.products._form', ['product' => $product])
@endsection
