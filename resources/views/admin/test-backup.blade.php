@extends('layouts.admin')

@section('title', 'Test Backup')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-slate-900">Test Backup Controller</h1>

    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h2 class="text-lg font-semibold mb-4">Test Results</h2>

        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="font-medium">Message:</span>
                <span>{{ $data['message'] }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Database Host:</span>
                <span>{{ $data['database_config']['host'] ?? 'N/A' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Database Name:</span>
                <span>{{ $data['database_config']['database'] ?? 'N/A' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Storage Path:</span>
                <span>{{ $data['storage_path'] }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Directory Exists:</span>
                <span class="{{ $data['directory_exists'] ? 'text-green-600' : 'text-red-600' }}">
                    {{ $data['directory_exists'] ? 'Yes' : 'No' }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium">Directory Writable:</span>
                <span class="{{ $data['directory_writable'] ? 'text-green-600' : 'text-red-600' }}">
                    {{ $data['directory_writable'] ? 'Yes' : 'No' }}
                </span>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('admin.database-backup.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors">
                Go to Database Backup
            </a>
        </div>
    </div>
</div>
@endsection
