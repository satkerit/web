@extends('layouts.admin')

@section('title', 'Edit Berita')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Edit Berita</h1>
        <p class="text-slate-500">Perbarui informasi berita.</p>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.news._form', ['news' => $news])
    </form>
@endsection
