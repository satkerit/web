@extends('errors.minimal')

@section('code', '403')
@section('title', 'Akses Ditolak')
@section('message', 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.')

@section('icon-bg', 'bg-gradient-to-br from-rose-500 to-red-500')
@section('icon-shadow', 'shadow-rose-500/30')

@section('icon')
<svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
</svg>
@endsection
