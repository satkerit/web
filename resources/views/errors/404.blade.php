@extends('errors.minimal')

@section('code', '404')
@section('title', 'Halaman Tidak Ditemukan')
@section('message', 'Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman telah dipindahkan atau dihapus.')

@section('icon-bg', 'bg-gradient-to-br from-amber-500 to-orange-500')
@section('icon-shadow', 'shadow-amber-500/30')

@section('icon')
<svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection
