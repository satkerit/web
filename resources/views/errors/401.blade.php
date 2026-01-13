@extends('errors.minimal')

@section('code', '401')
@section('title', 'Tidak Terautentikasi')
@section('message', 'Anda perlu login terlebih dahulu untuk mengakses halaman ini. Silakan masuk dengan akun Anda.')

@section('icon-bg', 'bg-gradient-to-br from-blue-500 to-indigo-500')
@section('icon-shadow', 'shadow-blue-500/30')

@section('icon')
<svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
</svg>
@endsection
