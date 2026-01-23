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

<x-admin.card>
    <form action="{{ route('admin.kas-keliling.store') }}" method="POST">
        @csrf
        @include('admin.kas-keliling.form')
    </form>
</x-admin.card>
@endsection
