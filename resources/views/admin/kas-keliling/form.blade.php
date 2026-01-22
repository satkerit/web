<div class="space-y-6">
    <x-admin.input
        name="area_name"
        label="Nama Area"
        :value="old('area_name', $kasKeliling->area_name ?? '')"
        required
        placeholder="Contoh: Pasar Pagi, Kelurahan Sungailiat"
    />

    <x-admin.input
        name="contact_person"
        label="Contact Person"
        :value="old('contact_person', $kasKeliling->contact_person ?? '')"
        placeholder="Nama petugas"
    />

    <x-admin.input
        name="contact_phone"
        label="Nomor Telepon"
        :value="old('contact_phone', $kasKeliling->contact_phone ?? '')"
        placeholder="08xx-xxxx-xxxx"
    />

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-2">Layanan yang Ditawarkan</label>
        <div x-data="repeaterField(@js(old('services_offered', $kasKeliling->services_offered ?? [])))" class="space-y-2">
            <template x-for="(item, index) in items" :key="item.id">
                <div class="flex gap-2">
                    <input type="text" :name="'services_offered['+index+']'" x-model="item.value"
                           placeholder="Contoh: Setoran Tabungan, Pembayaran Angsuran"
                           class="flex-1 rounded-xl border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                    <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                            class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </template>
            <button type="button" @click="addItem()" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                + Tambah Layanan
            </button>
        </div>
    </div>

    <div class="flex items-center">
        <input type="checkbox" name="is_active" id="is_active" value="1" 
               {{ old('is_active', $kasKeliling->is_active ?? true) ? 'checked' : '' }}
               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
        <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
    </div>

    <div class="flex gap-3">
        <x-admin.button type="submit">
            Simpan
        </x-admin.button>
        <x-admin.button href="{{ route('admin.kas-keliling.index') }}" variant="secondary">
            Batal
        </x-admin.button>
    </div>
</div>
