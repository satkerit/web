@extends('layouts.admin')

@section('title', 'Edit Jadwal Kas Keliling')

@section('content')
<x-admin.page-header title="Edit Jadwal Kas Keliling" subtitle="Perbarui jadwal kas keliling">
    <x-slot:actions>
        <x-admin.button href="{{ route('admin.kas-keliling.index') }}" variant="secondary" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>'>
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
    <form action="{{ route('admin.kas-keliling.update', $kasKeliling) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.kas-keliling.form')
    </form>
</x-admin.card>
@endsection