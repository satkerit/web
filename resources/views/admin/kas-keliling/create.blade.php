@extends('layouts.admin')

@section('title', 'Tambah Jadwal Kas Keliling')

@section('content')
<x-admin.page-header title="Tambah Jadwal Kas Keliling" subtitle="Buat jadwal kas keliling baru">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.kas-keliling.index') }}" variant="secondary">
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

@if(session('error'))
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
    {{ session('error') }}
</div>
@endif

<x-admin.card>
    <form action="{{ route('admin.kas-keliling.store') }}" method="POST">
        @csrf
        @include('admin.kas-keliling.form')
    </form>
</x-admin.card>
@endsection
