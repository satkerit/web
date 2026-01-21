@extends('layouts.admin')

@section('title', isset($news) ? 'Edit Berita' : 'Tambah Berita Baru')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" crossorigin="anonymous">
<style>
/* Modern Form Styling */
.form-container {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 2rem 1rem;
}

.form-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    overflow: visible;
    margin: 0 auto;
    max-width: 1600px;
    width: 100%;
}

.form-card form {
    width: 100%;
}

.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    text-align: center;
    position: relative;
}

.form-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.form-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    position: relative;
    z-index: 1;
}

.form-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0.5rem 0 0 0;
    position: relative;
    z-index: 1;
}

.form-body {
    padding: 0;
    width: 100%;
}

.form-body > .form-section {
    width: 100%;
}

.form-section {
    padding: 2.5rem 3rem;
    border-bottom: 1px solid #f0f0f0;
}

.form-section:last-child {
    border-bottom: none;
}

/* Full width content section */
.form-section.content-section {
    padding: 2.5rem 2rem;
}

.form-section.content-section .form-group {
    max-width: 100%;
    width: 100%;
}

.form-section.content-section .note-editor,
.form-section.content-section .note-editing-area,
.form-section.content-section .note-editable {
    width: 100% !important;
    max-width: 100% !important;
}

.section-title {
    display: flex;
    align-items: center;
    font-size: 1.3rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e2e8f0;
}

.section-icon {
    width: 24px;
    height: 24px;
    margin-right: 0.75rem;
    color: #667eea;
}

.form-grid {
    display: grid;
    gap: 1.5rem;
}

.form-grid-2 {
    grid-template-columns: 1fr 1fr;
}

.form-grid-3 {
    grid-template-columns: 1fr 1fr 1fr;
}

@media (max-width: 768px) {
    .form-grid-2,
    .form-grid-3 {
        grid-template-columns: 1fr;
    }
}

.form-group {
    position: relative;
    width: 100%;
}

/* Full width for content editor */
.content-section .form-group {
    width: 100%;
    max-width: 100%;
}

.content-section .form-group textarea,
.content-section .form-group .note-editor {
    width: 100% !important;
    max-width: 100% !important;
}

/* Ensure summernote textarea is full width */
#summernote {
    width: 100% !important;
    display: block;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-label.required::after {
    content: ' *';
    color: #ef4444;
}

.form-input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fafafa;
}

.form-input:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-textarea {
    min-height: 120px;
    resize: vertical;
}

.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

.form-help {
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.25rem;
}

.form-error {
    font-size: 0.875rem;
    color: #ef4444;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
}

.form-error svg {
    width: 16px;
    height: 16px;
    margin-right: 0.25rem;
}

/* Image Upload Styling */
.image-upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    background: #fafafa;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.image-upload-area:hover {
    border-color: #667eea;
    background: #f8faff;
}

.image-upload-area.has-image {
    border-color: #10b981;
    background: white;
    padding: 0;
}

.image-preview {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    aspect-ratio: 16/9;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-preview:hover .image-overlay {
    opacity: 1;
}

.image-upload-placeholder {
    color: #6b7280;
}

.image-upload-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 1rem;
    color: #9ca3af;
}

/* Gallery Styling */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.gallery-item {
    position: relative;
    aspect-ratio: 16/9;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #e5e7eb;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-remove {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: rgba(239, 68, 68, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-remove {
    opacity: 1;
}

.gallery-badge {
    position: absolute;
    top: 0.5rem;
    left: 0.5rem;
    background: #10b981;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 10;
}

/* Status Toggle */
.status-toggle {
    display: flex;
    align-items: center;
    padding: 1rem;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-radius: 12px;
    color: white;
    margin-bottom: 1rem;
}

.status-toggle input[type="checkbox"] {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.75rem;
    accent-color: white;
}

/* Action Buttons */
.form-actions {
    padding: 2rem;
    background: #f9fafb;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.btn {
    padding: 0.875rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
    background: #6b7280;
    color: white;
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-2px);
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
}

.btn svg {
    width: 20px;
    height: 20px;
    margin-right: 0.5rem;
}

/* Character Counter */
.char-counter {
    position: absolute;
    bottom: 0.5rem;
    right: 0.75rem;
    font-size: 0.75rem;
    color: #6b7280;
    background: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
}

.char-counter.warning {
    color: #f59e0b;
    border-color: #f59e0b;
}

.char-counter.danger {
    color: #ef4444;
    border-color: #ef4444;
}

/* Summernote Customization */
.note-editor {
    border: 2px solid #e5e7eb !important;
    border-radius: 12px !important;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    width: 100% !important;
    max-width: 100% !important;
    min-height: 800px !important;
}

.note-editor.note-frame {
    background: white;
    min-height: 800px !important;
}

.note-editor.note-frame.fullscreen {
    z-index: 9999;
}

.note-toolbar {
    background: #f9fafb !important;
    border-bottom: 1px solid #e5e7eb !important;
    padding: 0.75rem !important;
    width: 100% !important;
}

.note-btn-group {
    margin-right: 0.5rem !important;
}

.note-btn {
    background: white !important;
    border: 1px solid #e5e7eb !important;
    color: #374151 !important;
    padding: 0.375rem 0.75rem !important;
    border-radius: 0.375rem !important;
    font-size: 0.875rem !important;
    transition: all 0.2s !important;
}

.note-btn:hover {
    background: #f3f4f6 !important;
    border-color: #d1d5db !important;
}

.note-btn.active,
.note-btn:active {
    background: #667eea !important;
    border-color: #667eea !important;
    color: white !important;
}

.note-editing-area {
    background: white;
    width: 100% !important;
    min-height: 750px !important;
}

.note-editable {
    padding: 1.5rem !important;
    min-height: 700px !important;
    max-height: 1200px !important;
    overflow-y: auto !important;
    font-size: 1rem !important;
    line-height: 1.75 !important;
    color: #374151 !important;
}

/* Force minimum height for editor */
#summernote + .note-editor .note-editable {
    min-height: 700px !important;
}

.content-section .note-editor {
    min-height: 800px !important;
}

.content-section .note-editing-area {
    min-height: 750px !important;
}

.content-section .note-editable {
    min-height: 700px !important;
}

/* Custom scrollbar for editor */
.note-editable::-webkit-scrollbar {
    width: 10px;
}

.note-editable::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 5px;
}

.note-editable::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 5px;
}

.note-editable::-webkit-scrollbar-thumb:hover {
    background: #764ba2;
}

.note-editable:focus {
    outline: none;
}

.note-editable p {
    margin-bottom: 1rem;
}

.note-editable h2,
.note-editable h3,
.note-editable h4,
.note-editable h5 {
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
    color: #1f2937;
}

.note-editable h2 {
    font-size: 1.875rem;
}

.note-editable h3 {
    font-size: 1.5rem;
}

.note-editable h4 {
    font-size: 1.25rem;
}

.note-editable h5 {
    font-size: 1.125rem;
}

.note-editable ul,
.note-editable ol {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.note-editable li {
    margin-bottom: 0.5rem;
}

.note-editable blockquote {
    border-left: 4px solid #667eea;
    padding-left: 1rem;
    margin: 1rem 0;
    color: #6b7280;
    font-style: italic;
}

.note-editable img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1rem 0;
}

.note-editable table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

.note-editable table td,
.note-editable table th {
    border: 1px solid #e5e7eb;
    padding: 0.5rem;
}

.note-editable table th {
    background: #f9fafb;
    font-weight: 600;
}

.note-editable a {
    color: #667eea;
    text-decoration: underline;
}

.note-editable a:hover {
    color: #764ba2;
}

.note-statusbar {
    background: #f9fafb !important;
    border-top: 1px solid #e5e7eb !important;
    padding: 0.5rem 1rem !important;
}

.note-resizebar {
    background: #e5e7eb !important;
    height: 8px !important;
    cursor: ns-resize !important;
}

.note-resizebar:hover {
    background: #d1d5db !important;
}

/* Modal styling */
.note-modal .modal-dialog {
    max-width: 600px;
}

.note-modal .modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.note-modal .modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    padding: 1rem 1.5rem;
}

.note-modal .modal-body {
    padding: 1.5rem;
}

.note-modal .form-control {
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    padding: 0.5rem 0.75rem;
}

.note-modal .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Dropdown styling */
.note-dropdown-menu {
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    padding: 0.5rem;
}

.note-dropdown-item {
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s;
}

.note-dropdown-item:hover {
    background: #f3f4f6;
}

/* Color palette */
.note-color-palette {
    margin: 0.5rem;
}

.note-color-btn {
    border: 1px solid #e5e7eb;
    border-radius: 0.25rem;
}

.note-color-btn:hover {
    transform: scale(1.1);
}

/* Fullscreen mode */
.note-editor.fullscreen {
    position: fixed;
    top: 0;
    left: 0;
    width: 100% !important;
    height: 100% !important;
    z-index: 9999;
}

.note-editor.fullscreen .note-editable {
    min-height: calc(100vh - 200px) !important;
    max-height: none !important;
}

/* Loading state */
.note-editor.loading {
    opacity: 0.6;
    pointer-events: none;
}

.note-editor.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 40px;
    height: 40px;
    margin: -20px 0 0 -20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

/* Loading States */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Alert Messages */
.alert {
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.alert svg {
    width: 20px;
    height: 20px;
    margin-right: 0.75rem;
    flex-shrink: 0;
}

/* Progress Bar */
.progress-bar {
    width: 100%;
    height: 4px;
    background: #e5e7eb;
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 2rem;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea, #764ba2);
    transition: width 0.3s ease;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .form-container {
        padding: 1rem;
    }
    
    .form-card {
        margin: 0;
        border-radius: 12px;
        max-width: 100%;
        width: 100%;
    }
    
    .form-section {
        padding: 1.5rem;
        width: 100%;
    }
    
    .form-section.content-section {
        padding: 1.5rem 1rem;
    }
    
    .form-actions {
        padding: 1.5rem;
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .note-editable {
        min-height: 500px !important;
        max-height: 800px !important;
    }
    
    .note-editor {
        width: 100% !important;
    }
}

@media (max-width: 640px) {
    .form-header h1 {
        font-size: 2rem;
    }
    
    .form-section {
        padding: 1rem;
        width: 100%;
    }
    
    .form-section.content-section {
        padding: 1rem 0.5rem;
    }
    
    .gallery-grid {
        grid-template-columns: 1fr;
    }
    
    .note-editable {
        min-height: 400px !important;
        max-height: 700px !important;
        padding: 1rem !important;
    }
    
    .note-editor {
        width: 100% !important;
    }
    
    .note-toolbar {
        padding: 0.5rem !important;
    }
}
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="form-card">
        <!-- Header -->
        <div class="form-header">
            <h1>
                @if(isset($news))
                    <svg class="inline w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Berita
                @else
                    <svg class="inline w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Berita Baru
                @endif
            </h1>
            <p>{{ isset($news) ? 'Perbarui informasi berita dan artikel' : 'Buat konten berita dan artikel yang menarik' }}</p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress-fill" style="width: 0%" id="form-progress"></div>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
        <div class="form-section">
            <div class="alert alert-error">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form id="news-form" 
              action="{{ isset($news) ? route('admin.news.update', $news) : route('admin.news.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              data-edit-mode="{{ isset($news) ? 'true' : 'false' }}"
              data-news-id="{{ isset($news) ? $news->id : 'new' }}"
              data-upload-url="{{ route('admin.storage.upload-editor-image') }}">
            @csrf
            @if(isset($news)) @method('PUT') @endif

            <div class="form-body">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Informasi Dasar
                    </h2>

                    <div class="form-grid form-grid-2">
                        <div class="form-group">
                            <label class="form-label required">Judul Berita</label>
                            <input type="text" name="title" value="{{ old('title', $news->title ?? '') }}" 
                                   class="form-input" placeholder="Masukkan judul berita yang menarik..." 
                                   required maxlength="255" id="title-input">
                            <div class="form-help">Judul akan muncul sebagai headline utama</div>
                            @error('title')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug URL</label>
                            <input type="text" name="slug" value="{{ old('slug', $news->slug ?? '') }}" 
                                   class="form-input" placeholder="akan-dibuat-otomatis" readonly id="slug-input">
                            <div class="form-help">URL slug dibuat otomatis dari judul</div>
                            @error('slug')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-grid form-grid-3">
                        <div class="form-group">
                            <label class="form-label required">Kategori</label>
                            <select name="category" class="form-input form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <option value="Berita" {{ old('category', $news->category ?? '') == 'Berita' ? 'selected' : '' }}>📰 Berita</option>
                                <option value="Artikel" {{ old('category', $news->category ?? '') == 'Artikel' ? 'selected' : '' }}>📝 Artikel</option>
                                <option value="Pengumuman" {{ old('category', $news->category ?? '') == 'Pengumuman' ? 'selected' : '' }}>📢 Pengumuman</option>
                                <option value="Promo" {{ old('category', $news->category ?? '') == 'Promo' ? 'selected' : '' }}>🎉 Promo</option>
                                <option value="Event" {{ old('category', $news->category ?? '') == 'Event' ? 'selected' : '' }}>🎪 Event</option>
                            </select>
                            @error('category')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Penulis</label>
                            <input type="text" name="author" value="{{ old('author', $news->author ?? (auth()->check() ? auth()->user()->name : '')) }}" 
                                   class="form-input" placeholder="Nama penulis">
                            <div class="form-help">Default: {{ auth()->check() ? auth()->user()->name : 'Admin' }}</div>
                            @error('author')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tanggal Publikasi</label>
                            <input type="datetime-local" name="published_at" 
                                   value="{{ old('published_at', isset($news) && $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                                   class="form-input">
                            @error('published_at')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ringkasan</label>
                        <textarea name="excerpt" rows="3" class="form-input form-textarea" 
                                  placeholder="Ringkasan singkat untuk preview di halaman utama..." 
                                  maxlength="500">{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
                        <div class="form-help">Ringkasan akan ditampilkan di halaman daftar berita</div>
                        @error('excerpt')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Content Section -->
                <div class="form-section content-section">
                    <h2 class="section-title">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Konten Berita
                    </h2>

                    <div class="form-group">
                        <label class="form-label required">Isi Konten</label>
                        <div class="relative">
                            <textarea name="content" id="summernote" required data-initial-content="{{ old('content', isset($news) ? htmlspecialchars($news->content, ENT_QUOTES) : '') }}">{{ old('content', $news->content ?? '') }}</textarea>
                        </div>
                        <div class="form-help">
                            <div class="flex items-center gap-4 text-xs">
                                <span>💡 Tips: Gunakan Ctrl+V untuk paste gambar langsung</span>
                                <span>📝 Drag & drop gambar juga didukung</span>
                                <span id="word-count" class="ml-auto font-semibold">0 kata</span>
                            </div>
                        </div>
                        @error('content')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Optimasi SEO
                    </h2>

                    <div class="form-grid form-grid-2">
                        <div class="form-group" style="position: relative;">
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" rows="3" class="form-input form-textarea" 
                                      placeholder="Deskripsi singkat untuk hasil pencarian Google (150-160 karakter)..."
                                      maxlength="160" id="meta-description">{{ old('meta_description', $news->meta_description ?? '') }}</textarea>
                            <div class="char-counter" id="meta-counter">0/160</div>
                            <div class="form-help">Deskripsi yang akan muncul di hasil pencarian</div>
                            @error('meta_description')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tags</label>
                            <input type="text" name="tags" value="{{ old('tags', $news->tags ?? '') }}" 
                                   class="form-input" placeholder="tag1, tag2, tag3" id="tags-input">
                            <div class="form-help">Pisahkan dengan koma untuk multiple tags</div>
                            @error('tags')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Media Section -->
                <div class="form-section">
                    <h2 class="section-title">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Media & Gambar
                    </h2>

                    <div class="form-grid form-grid-2">
                        <!-- Featured Image -->
                        <div class="form-group">
                            <label class="form-label">Gambar Utama</label>
                            <div class="image-upload-area {{ isset($news) && $news->featured_image ? 'has-image' : '' }}" 
                                 onclick="document.getElementById('featured_image').click()">
                                @if(isset($news) && $news->featured_image)
                                    <div class="image-preview">
                                        <img src="{{ \App\Helpers\StorageHelper::url($news->featured_image) }}" alt="Featured Image" id="featured-preview">
                                        <div class="image-overlay">
                                            <button type="button" class="btn btn-secondary">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                                Ganti Gambar
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="image-upload-placeholder">
                                        <svg class="image-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <h3 style="margin: 0 0 0.5rem 0; font-weight: 600;">Upload Gambar Utama</h3>
                                        <p style="margin: 0; font-size: 0.875rem;">JPG, PNG, WebP (Max 2MB)</p>
                                        <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; opacity: 0.7;">Klik untuk memilih file</p>
                                    </div>
                                @endif
                            </div>
                            <input type="file" name="featured_image" id="featured_image" accept="image/*" style="display: none;" onchange="previewFeaturedImage(this)">
                            <div class="form-help">Gambar utama akan ditampilkan sebagai thumbnail</div>
                            @error('featured_image')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>

                        <!-- Gallery Images -->
                        <div class="form-group">
                            <label class="form-label">Galeri Gambar (Maksimal 7)</label>
                            
                            @if(isset($news) && $news->images->count() > 0)
                            <div class="gallery-grid" id="existing-gallery">
                                @foreach($news->images as $image)
                                <div class="gallery-item" data-image-id="{{ $image->id }}">
                                    <img src="{{ \App\Helpers\StorageHelper::url($image->image_path) }}" alt="Gallery Image">
                                    <button type="button" class="gallery-remove" onclick="if(confirm('Hapus gambar ini?')) document.getElementById('delete-image-{{ $image->id }}').submit();">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <div class="image-upload-area" onclick="document.getElementById('slide_images').click()" style="margin-top: 1rem;">
                                <div class="image-upload-placeholder">
                                    <svg class="image-upload-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    <h3 style="margin: 0 0 0.5rem 0; font-weight: 600;">Tambah ke Galeri</h3>
                                    <p style="margin: 0; font-size: 0.875rem;">
                                        @if(isset($news))
                                            Maksimal {{ 7 - $news->images->count() }} gambar lagi
                                        @else
                                            Maksimal 7 gambar
                                        @endif
                                    </p>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.75rem; opacity: 0.7;">Klik untuk memilih file</p>
                                </div>
                            </div>
                            
                            <!-- Preview area for new images -->
                            <div id="gallery-preview" class="gallery-grid" style="margin-top: 1rem; display: none;"></div>
                            
                            <input type="file" name="slide_images[]" id="slide_images" multiple accept="image/*" style="display: none;">

                            <div class="form-help">Gambar tambahan untuk galeri artikel (JPG, PNG, WebP - Max 2MB per file)</div>
                            @error('slide_images')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                            @error('slide_images.*')<div class="form-error"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Publication Settings -->
                <div class="form-section">
                    <h2 class="section-title">
                        <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10a2 2 0 002 2h4a2 2 0 002-2V11m-6 0h8m-8 0V7a2 2 0 012-2h4a2 2 0 012 2v4"/>
                        </svg>
                        Pengaturan Publikasi
                    </h2>

                    <div class="status-toggle">
                        <input type="checkbox" name="is_published" id="is_published" value="1" 
                               {{ old('is_published', $news->is_published ?? true) ? 'checked' : '' }}>
                        <div>
                            <strong>Publikasikan Sekarang</strong>
                            <div style="font-size: 0.875rem; opacity: 0.9;">Berita akan langsung tampil di website</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Batal
                </a>
                
                @if(isset($news))
                <a href="{{ route('news.show', $news->slug) }}" target="_blank" class="btn btn-secondary">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Preview
                </a>
                @endif
                
                <button type="submit" class="btn btn-primary" id="submit-btn">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($news) ? 'Simpan Perubahan' : 'Publikasikan Berita' }}
                </button>
            </div>
        </form>
    </div>
</div>

@if(isset($news))
@foreach($news->images as $image)
<form id="delete-image-{{ $image->id }}" action="{{ route('admin.news.delete-image', $image) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
@endforeach
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/news-form.js') }}"></script>
@endpush