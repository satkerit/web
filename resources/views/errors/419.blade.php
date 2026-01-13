@extends('errors.minimal')

@section('code', '419')
@section('title', 'Sesi Kedaluwarsa')
@section('message', 'Sesi Anda telah berakhir. Silakan muat ulang halaman dan coba lagi untuk melanjutkan.')

@section('icon-bg', 'bg-gradient-to-br from-purple-500 to-violet-500')
@section('icon-shadow', 'shadow-purple-500/30')

@section('icon')
<svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection
