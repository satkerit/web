@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Tambah Berita Baru</h1>
        <p class="text-slate-500">Isi formulir di bawah ini untuk menambahkan berita baru.</p>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.news._form', ['news' => null])
    </form>
@endsection
