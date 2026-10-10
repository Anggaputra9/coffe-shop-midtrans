@extends('admin.layouts.app')

@section('title', 'Tambah Menu')
@section('subtitle', 'Masukkan menu baru ke daftar.')

@section('content')
    @include('admin.products._form', ['product' => null])
@endsection
