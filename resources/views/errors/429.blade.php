@extends('errors.minimal')

@section('code', '429')
@section('title', 'Terlalu Banyak Permintaan')
@section('message', 'Anda telah mengirim terlalu banyak permintaan. Mohon tunggu beberapa saat sebelum mencoba lagi.')

@section('icon-bg', 'bg-gradient-to-br from-yellow-500 to-amber-500')
@section('icon-shadow', 'shadow-yellow-500/30')

@section('icon')
<svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
</svg>
@endsection
