<div>
    @if($submitted)
    <!-- Success State -->
    <div class="text-center py-12" x-data x-init="$el.classList.add('animate-scale-in')">
        <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl shadow-emerald-500/30">
            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">Laporan Berhasil Dikirim!</h3>
        <p class="text-gray-600 mb-6">Terima kasih atas laporan Anda. Kami akan segera menindaklanjuti.</p>

        <div class="bg-emerald-50 rounded-2xl p-6 max-w-md mx-auto mb-8">
            <p class="text-sm text-emerald-600 mb-2">Nomor Tiket Anda:</p>
            <p class="text-2xl font-bold text-emerald-700 font-mono">{{ $ticketNumber }}</p>
            <p class="text-xs text-emerald-600 mt-2">Simpan nomor ini untuk melacak status laporan Anda</p>
        </div>

        <button wire:click="$set('submitted', false)" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Buat Laporan Baru
        </button>
    </div>
    @else
    <!-- Form -->
    <form wire:submit="submit" class="space-y-8">
        <!-- Anonymous Toggle -->
        <div class="bg-amber-50 rounded-2xl p-6 border border-amber-200">
            <label class="flex items-start cursor-pointer">
                <input type="checkbox" wire:model.live="is_anonymous" class="w-5 h-5 mt-0.5 text-amber-600 border-amber-300 rounded focus:ring-amber-500">
                <div class="ml-4">
                    <span class="font-semibold text-amber-800">Laporkan Secara Anonim</span>
                    <p class="text-sm text-amber-600 mt-1">Identitas Anda akan dirahasiakan. Namun, kami tidak dapat menghubungi Anda untuk informasi tambahan.</p>
                </div>
            </label>
        </div>

        <!-- Personal Info (if not anonymous) -->
        @if(!$is_anonymous)
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
                Informasi Pelapor
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.live="name" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('name') border-red-300 @enderror">
                    @error('name') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" wire:model.live="email" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('email') border-red-300 @enderror">
                    @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" wire:model.live="phone" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Identitas (KTP/SIM)</label>
                    <input type="text" wire:model.live="identity_number" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>
            </div>
        </div>
        @endif

        <!-- Complaint Details -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
                Detail Laporan
            </h3>
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Pelanggaran <span class="text-red-500">*</span></label>
                        <select wire:model.live="type" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('type') border-red-300 @enderror">
                            <option value="">Pilih Jenis</option>
                            <option value="fraud">Kecurangan (Fraud)</option>
                            <option value="violation">Pelanggaran Peraturan</option>
                            <option value="ethics">Pelanggaran Kode Etik</option>
                            <option value="abuse">Penyalahgunaan Wewenang</option>
                            <option value="safety">Keselamatan Kerja</option>
                            <option value="other">Lainnya</option>
                        </select>
                        @error('type') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Subjek Laporan <span class="text-red-500">*</span></label>
                        <input type="text" wire:model.live="subject" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all @error('subject') border-red-300 @enderror" placeholder="Ringkasan singkat laporan">
                        @error('subject') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                    <textarea wire:model.live="description" rows="6" maxlength="5000" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all resize-none @error('description') border-red-300 @enderror" placeholder="Jelaskan kronologi kejadian secara detail..."></textarea>
                    <div class="flex justify-between items-center mt-1">
                        @error('description') <p class="text-sm text-red-500">{{ $message }}</p> @else <p class="text-xs text-gray-400">Minimal 50 karakter</p> @enderror
                        <p class="text-xs {{ strlen($description) > 5000 ? 'text-red-500' : 'text-gray-400' }}">{{ strlen($description) }}/5000</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reported Person -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="w-8 h-8 bg-rose-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
                Pihak yang Dilaporkan (Opsional)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pihak Terlapor</label>
                    <input type="text" wire:model.live="reported_person" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Departemen/Unit</label>
                    <input type="text" wire:model.live="reported_department" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Kejadian</label>
                    <input type="date" wire:model.live="incident_date" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi Kejadian</label>
                    <input type="text" wire:model.live="incident_location" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all">
                </div>
            </div>
        </div>

        <!-- Attachments -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </span>
                Bukti Pendukung (Opsional)
            </h3>
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-emerald-400 transition-colors">
                <input type="file" wire:model.live="attachments" multiple class="hidden" id="attachments">
                <label for="attachments" class="cursor-pointer">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="text-gray-600 mb-1">Klik untuk upload atau drag & drop</p>
                    <p class="text-sm text-gray-400">PDF, DOC, JPG, PNG (Maks. 5MB per file)</p>
                </label>
            </div>
            @if($attachments)
            <div class="mt-4 space-y-2">
                @foreach($attachments as $index => $file)
                <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                    <span class="text-sm text-gray-600">{{ $file->getClientOriginalName() }}</span>
                    <button type="button" wire:click="$set('attachments.{{ $index }}', null)" class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @endforeach
            </div>
            @endif
            @error('attachments.*') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Terms -->
        <div class="bg-gray-50 rounded-2xl p-6">
            <label class="flex items-start cursor-pointer">
                <input type="checkbox" wire:model.live="agree_terms" class="w-5 h-5 mt-0.5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 @error('agree_terms') border-red-300 @enderror">
                <div class="ml-4">
                    <span class="text-gray-700">Saya menyatakan bahwa laporan ini dibuat dengan itikad baik dan informasi yang saya berikan adalah benar. Saya memahami bahwa laporan palsu dapat dikenakan sanksi hukum.</span>
                </div>
            </label>
            @error('agree_terms') <p class="mt-2 text-sm text-red-500">{{ $message }}</p> @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all duration-300 hover:scale-[1.02] disabled:opacity-50 btn-shine" wire:loading.attr="disabled">
            <span wire:loading.remove class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Kirim Laporan
            </span>
            <span wire:loading class="flex items-center">
                <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Mengirim...
            </span>
        </button>
    </form>
    @endif
</div>
