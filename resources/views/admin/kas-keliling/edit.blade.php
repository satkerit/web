@extends('layouts.admin')

@section('title', 'Edit Jadwal Kas Keliling')

@section('content')
<x-admin.page-header title="Edit Jadwal Kas Keliling" subtitle="Perbarui jadwal kas keliling">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.kas-keliling.index') }}" variant="secondary">
            Kembali
        </x-admin.button>
    </x-slot:actions>
</x-admin.page-header>

<x-admin.card>
    <form action="{{ route('admin.kas-keliling.update', $kasKeliling) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.kas-keliling.form')
    </form>
</x-admin.card>
@endsection
