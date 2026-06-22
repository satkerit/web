@csrf

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8"
     data-edit-mode="{{ isset($news) ? 'true' : 'false' }}"
     data-news-id="{{ $news->id ?? 'new' }}"
     data-upload-url="{{ route('admin.storage.upload-editor-image') }}">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Title & Slug -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Judul Berita <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $news->title ?? '') }}"
                       class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 @error('title') border-red-500 @enderror"
                       placeholder="Masukkan judul berita" required>
                @error('title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="slug" class="block text-sm font-medium text-slate-700 mb-2">Slug (URL)</label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-slate-300 bg-slate-50 text-slate-500 text-sm">
                        {{ url('berita') }}/
                    </span>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $news->slug ?? '') }}"
                           class="w-full rounded-r-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 rounded-l-none @error('slug') border-red-500 @enderror"
                           placeholder="url-berita-otomatis">
                </div>
                <p class="text-xs text-slate-500 mt-1">Biarkan kosong untuk generate otomatis dari judul.</p>
                @error('slug') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-slate-700 mb-2">Konten <span class="text-red-500">*</span></label>
                <textarea name="content" id="summernote" class="@error('content') border-red-500 @enderror">
                    @if(old('content'))
                        {{ old('content') }}
                    @elseif(isset($news) && $news->content)
                        {{ $news->content }}
                    @endif
                </textarea>
                @error('content') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="excerpt" class="block text-sm font-medium text-slate-700 mb-2">Kutipan Singkat (Excerpt)</label>
                <textarea name="excerpt" id="excerpt" rows="3"
                          class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200 @error('excerpt') border-red-500 @enderror"
                          placeholder="Ringkasan singkat untuk tampilan kartu...">{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
                @error('excerpt') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- SEO -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">SEO & Metadata</h3>
            <div class="mb-4">
                <label for="meta_description" class="block text-sm font-medium text-slate-700 mb-2">Meta Description</label>
                <textarea name="meta_description" id="meta_description" rows="2"
                          class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">{{ old('meta_description', $news->meta_description ?? '') }}</textarea>
            </div>
            <div>
                <label for="tags" class="block text-sm font-medium text-slate-700 mb-2">Tags (pisahkan dengan koma)</label>
                <input type="text" name="tags" id="tags" value="{{ old('tags', $news->tags ?? '') }}"
                       class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200"
                       placeholder="contoh: ekonomi, syariah, berita terkini">
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Publish Status -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Publikasi</h3>

            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                <select name="category" id="category" class="form-select w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200" required>
                    <option value="">Pilih Kategori</option>
                    @foreach(['Berita', 'Artikel', 'Pengumuman'] as $cat)
                        <option value="{{ $cat }}" {{ (old('category', $news->category ?? '') == $cat) ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="published_at" class="block text-sm font-medium text-slate-700 mb-2">Tanggal Publikasi</label>
                <input type="datetime-local" name="published_at" id="published_at"
                       value="{{ old('published_at', isset($news->published_at) ? $news->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
                       class="w-full rounded-lg border-slate-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_published" id="is_published" value="1"
                       class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                       {{ (old('is_published', $news->is_published ?? false)) ? 'checked' : '' }}>
                <label for="is_published" class="text-sm font-medium text-slate-700">Publikasikan Sekarang</label>
            </div>
        </div>

        <!-- Featured Image -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Gambar Utama</h3>

            <!-- Image Preview -->
            <div id="featured-image-preview-container" class="mb-4 {{ isset($news) && $news->featured_image ? '' : 'hidden' }}">
                <img id="featured-preview"
                     src="{{ isset($news) && $news->featured_image ? Storage::url($news->featured_image) : '' }}"
                     alt="Preview"
                     class="w-full h-48 object-cover rounded-lg border border-slate-200 shadow-sm">
                @if(isset($news) && $news->featured_image)
                    <p class="text-xs text-slate-500 mt-2 text-center">Gambar saat ini</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="featured_image_input" class="block text-sm font-medium text-slate-700 mb-2">
                    {{ isset($news) && $news->featured_image ? 'Ganti Gambar' : 'Upload Gambar' }} <span class="text-red-500">*</span>
                </label>
                <input type="file" name="featured_image" id="featured_image_input" accept="image/*"
                       class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-blue-100 transition cursor-pointer"
                       {{ isset($news) && $news->featured_image ? '' : 'required' }}>
                <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, WEBP. Max: {{ number_format(config('security.upload.max_size') / 1024, 0) }}MB.</p>
                @error('featured_image') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Gallery Images -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Galeri Tambahan</h3>

            @if(isset($news) && $news->images->count() > 0)
                <div class="grid grid-cols-2 gap-2 mb-4">
                    @foreach($news->images as $img)
                        <div class="relative group">
                            <img src="{{ Storage::url($img->image_path) }}" class="w-full h-20 object-cover rounded border border-slate-200">
                            <label class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer rounded">
                                <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="hidden peer">
                                <span class="text-white text-xs peer-checked:text-red-400">Hapus</span>
                                <div class="absolute top-1 right-1 bg-white rounded-full p-0.5 peer-checked:block hidden">
                                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
                <p class="text-xs text-slate-500 mb-4">Klik gambar untuk menandai hapus.</p>
            @endif

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Upload Galeri</label>
                <input type="file" name="slide_images[]" multiple accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-blue-100 transition">
                <p class="text-xs text-slate-500 mt-1">Bisa pilih banyak gambar sekaligus. Format: JPG, PNG, WEBP. Max: {{ number_format(config('security.upload.max_size') / 1024, 0) }}MB.</p>
                @error('slide_images.*') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>
</div>

<div class="mt-8 pt-6 border-t border-slate-200 flex justify-end gap-4">
    <a href="{{ route('admin.news.index') }}" class="px-6 py-2.5 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition">Batal</a>
    <button type="submit" class="px-6 py-2.5 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition shadow-lg shadow-blue-200">Simpan Berita</button>
</div>
