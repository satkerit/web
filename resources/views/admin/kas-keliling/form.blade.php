<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Tanggal -->
        <x-admin.input
            type="date"
            name="schedule_date"
            label="Tanggal Jadwal"
            :value="old('schedule_date', isset($kasKeliling) && $kasKeliling->schedule_date ? $kasKeliling->schedule_date->format('Y-m-d') : '')"
            :error="$errors->first('schedule_date')"
            required
        />

        <!-- Lokasi -->
        <x-admin.input
            name="location"
            label="Lokasi/Tujuan"
            :value="old('location', $kasKeliling->location ?? '')"
            :error="$errors->first('location')"
            required
            placeholder="Contoh: Pasar Pagi Sungailiat"
        />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Jam Mulai -->
        <x-admin.input
            type="time"
            name="start_time"
            label="Jam Mulai"
            :value="old('start_time', isset($kasKeliling) && $kasKeliling->start_time ? \Carbon\Carbon::parse($kasKeliling->start_time)->format('H:i') : '')"
            :error="$errors->first('start_time')"
            required
        />

        <!-- Jam Selesai -->
        <x-admin.input
            type="time"
            name="end_time"
            label="Jam Selesai"
            :value="old('end_time', isset($kasKeliling) && $kasKeliling->end_time ? \Carbon\Carbon::parse($kasKeliling->end_time)->format('H:i') : '')"
            :error="$errors->first('end_time')"
            required
        />
    </div>

    <!-- Fasilitas -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Fasilitas yang Tersedia
        </label>
        <textarea 
            name="facility" 
            rows="3" 
            class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 {{ $errors->has('facility') ? 'border-red-300 focus:border-red-500' : '' }}" 
            placeholder="Contoh: Setoran Tabungan, Pembayaran Angsuran, Penarikan Tunai"
        >{{ old('facility', $kasKeliling->facility ?? '') }}</textarea>
        @if($errors->has('facility'))
            <p class="mt-1 text-xs text-red-600">{{ $errors->first('facility') }}</p>
        @else
            <p class="mt-1 text-xs text-gray-500">Pisahkan dengan koma (,) untuk multiple fasilitas</p>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Nama PIC -->
        <x-admin.input
            name="pic_name"
            label="Nama PIC (Person In Charge)"
            :value="old('pic_name', $kasKeliling->pic_name ?? '')"
            :error="$errors->first('pic_name')"
            placeholder="Nama petugas"
        />

        <!-- Nomor PIC -->
        <x-admin.input
            name="pic_phone"
            label="Nomor Telepon PIC"
            :value="old('pic_phone', $kasKeliling->pic_phone ?? '')"
            :error="$errors->first('pic_phone')"
            placeholder="08xx-xxxx-xxxx"
        />
    </div>

    <!-- Catatan -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">
            Catatan Tambahan
        </label>
        <textarea 
            name="notes" 
            rows="3" 
            class="w-full rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 {{ $errors->has('notes') ? 'border-red-300 focus:border-red-500' : '' }}" 
            placeholder="Catatan atau informasi tambahan (opsional)"
        >{{ old('notes', $kasKeliling->notes ?? '') }}</textarea>
        @if($errors->has('notes'))
            <p class="mt-1 text-xs text-red-600">{{ $errors->first('notes') }}</p>
        @endif
    </div>

    <!-- Status Aktif -->
    <div class="flex items-center">
        <input 
            type="checkbox" 
            name="is_active" 
            id="is_active" 
            value="1" 
            {{ old('is_active', isset($kasKeliling) ? $kasKeliling->is_active : true) ? 'checked' : '' }}
            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
        >
        <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
    </div>

    <!-- Buttons -->
    <div class="flex gap-3 pt-4">
        <x-admin.button type="submit">
            Simpan
        </x-admin.button>
        <x-admin.button href="{{ route('admin.kas-keliling.index') }}" variant="secondary">
            Batal
        </x-admin.button>
    </div>
</div>
