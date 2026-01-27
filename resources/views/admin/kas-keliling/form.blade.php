<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Tanggal -->
        <div>
            <label for="schedule_date" class="block text-sm font-semibold text-gray-700 mb-2">
                Tanggal Jadwal <span class="text-red-500">*</span>
            </label>
            <input type="date" name="schedule_date" id="schedule_date" 
                   value="{{ old('schedule_date', isset($kasKeliling) && $kasKeliling->schedule_date ? $kasKeliling->schedule_date->format('Y-m-d') : '') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('schedule_date') border-red-500 @enderror" 
                   required>
            @error('schedule_date')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Lokasi -->
        <div>
            <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">
                Lokasi/Tujuan <span class="text-red-500">*</span>
            </label>
            <input type="text" name="location" id="location" 
                   value="{{ old('location', $kasKeliling->location ?? '') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('location') border-red-500 @enderror" 
                   required placeholder="Contoh: Pasar Pagi Sungailiat">
            @error('location')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Jam Mulai -->
        <div>
            <label for="start_time" class="block text-sm font-semibold text-gray-700 mb-2">
                Jam Mulai <span class="text-red-500">*</span>
            </label>
            <input type="time" name="start_time" id="start_time" 
                   value="{{ old('start_time', isset($kasKeliling) && $kasKeliling->start_time ? \Carbon\Carbon::parse($kasKeliling->start_time)->format('H:i') : '') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('start_time') border-red-500 @enderror" 
                   required>
            @error('start_time')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Jam Selesai -->
        <div>
            <label for="end_time" class="block text-sm font-semibold text-gray-700 mb-2">
                Jam Selesai <span class="text-red-500">*</span>
            </label>
            <input type="time" name="end_time" id="end_time" 
                   value="{{ old('end_time', isset($kasKeliling) && $kasKeliling->end_time ? \Carbon\Carbon::parse($kasKeliling->end_time)->format('H:i') : '') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('end_time') border-red-500 @enderror" 
                   required>
            @error('end_time')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Fasilitas -->
    <div>
        <label for="facility" class="block text-sm font-semibold text-gray-700 mb-2">
            Fasilitas yang Tersedia
        </label>
        <textarea name="facility" id="facility" rows="3" 
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none @error('facility') border-red-500 @enderror" 
                  placeholder="Contoh: Setoran Tabungan, Pembayaran Angsuran, Penarikan Tunai">{{ old('facility', $kasKeliling->facility ?? '') }}</textarea>
        @error('facility')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @else
            <p class="mt-1.5 text-xs text-gray-500">Pisahkan dengan koma (,) untuk multiple fasilitas</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Nama PIC -->
        <div>
            <label for="pic_name" class="block text-sm font-semibold text-gray-700 mb-2">
                Nama PIC (Person In Charge)
            </label>
            <input type="text" name="pic_name" id="pic_name" 
                   value="{{ old('pic_name', $kasKeliling->pic_name ?? '') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('pic_name') border-red-500 @enderror" 
                   placeholder="Nama petugas">
            @error('pic_name')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nomor PIC -->
        <div>
            <label for="pic_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                Nomor Telepon PIC
            </label>
            <input type="text" name="pic_phone" id="pic_phone" 
                   value="{{ old('pic_phone', $kasKeliling->pic_phone ?? '') }}"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('pic_phone') border-red-500 @enderror" 
                   placeholder="08xx-xxxx-xxxx">
            @error('pic_phone')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Catatan -->
    <div>
        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">
            Catatan Tambahan
        </label>
        <textarea name="notes" id="notes" rows="3" 
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none @error('notes') border-red-500 @enderror" 
                  placeholder="Catatan atau informasi tambahan (opsional)">{{ old('notes', $kasKeliling->notes ?? '') }}</textarea>
        @error('notes')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Status Aktif -->
    <div class="flex items-center">
        <input type="checkbox" name="is_active" id="is_active" value="1" 
               {{ old('is_active', isset($kasKeliling) ? $kasKeliling->is_active : true) ? 'checked' : '' }}
               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
        <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
    </div>

    <!-- Buttons -->
    <div class="flex gap-3 pt-4">
        <x-admin.button type="submit">
            {{ isset($kasKeliling) ? 'Perbarui' : 'Simpan' }}
        </x-admin.button>
        <x-admin.button href="{{ route('admin.kas-keliling.index') }}" variant="secondary">
            Batal
        </x-admin.button>
    </div>
</div>
