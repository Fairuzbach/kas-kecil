<div>
    <form wire:submit="save('pending_manager')" class="space-y-8 pb-10">
        {{-- 1. HEADER SECTION --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 -m-6 mb-6 p-6 rounded-t-xl shadow-lg">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">Pengajuan Petty Cash</h2>
                    <p class="text-blue-100 text-sm">Lengkapi form di bawah untuk mengajukan permohonan dana</p>
                </div>
            </div>
        </div>
        {{-- INFO DEPARTMENT --}}
        <div class="bg-white rounded-xl p-5 border-l-4 border-blue-500 shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Department Pemohon</label>
                <div class="font-bold text-gray-800 text-lg">{{ $user_department }}</div>
            </div>
        </div>
        {{-- 2. GRID UTAMA (KIRI & KANAN) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            {{-- >>> KOLOM KIRI: FORM INPUT DASAR <<< --}}
            <div class="space-y-6">
                {{-- JENIS TIKET --}}
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                        <span
                            class="w-6 h-6 bg-blue-100 text-blue-600 rounded flex items-center justify-center text-xs">1</span>
                        Jenis Pengajuan <span class="text-red-600">*</span>
                    </label>
                    <select wire:model.live="type"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition text-sm py-2.5">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach (\App\Enums\PettyCashType::cases() as $type)
                            <option value="{{ $type->value }}" {{ $type->value === 'pengobatan' ? 'disabled' : '' }}>
                                {{ strtoupper($type->name) }} {{ $type->value === 'pengobatan' ? '(Nonaktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                {{-- INPUT DINAMIS: CARI KARYAWAN / DIBAYAR KEPADA --}}
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                        <span
                            class="w-6 h-6 bg-blue-100 text-blue-600 rounded flex items-center justify-center text-xs">2</span>
                        {{ $type === 'pengobatan' ? 'Cari Karyawan' : 'Dibayar Kepada / Keperluan' }} <span
                            class="text-red-600">*</span>
                    </label>

                    @if ($type === 'pengobatan')
                        {{-- MODE PENGOBATAN: SEARCH --}}
                        <div class="relative">
                            <input type="text" wire:model="title" readonly
                                class="block w-full rounded-lg bg-gray-50 border-gray-300 text-gray-500 cursor-not-allowed text-sm py-2.5 mb-2"
                                placeholder="Nama karyawan akan muncul disini...">

                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="search_keyword"
                                    placeholder="🔍 Ketik Nama/NIK..."
                                    class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm py-2.5 pl-10">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                {{-- Loading Search --}}
                                <div wire:loading wire:target="search_keyword"
                                    class="absolute inset-y-0 right-3 flex items-center">
                                    <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            {{-- HASIL PENCARIAN --}}
                            @if (!empty($employee_result))
                                <div
                                    class="absolute z-10 w-full bg-white border border-gray-200 rounded-lg mt-1 shadow-xl max-h-60 overflow-y-auto">
                                    @foreach ($employee_result as $emp)
                                        <div wire:click="selectEmployee('{{ $emp['name'] }}', '{{ $emp['nik'] }}', '{{ $emp['division_name'] }}')"
                                            class="px-4 py-3 hover:bg-blue-50 cursor-pointer border-b last:border-0 group">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <div
                                                        class="font-bold text-gray-800 text-sm group-hover:text-blue-600">
                                                        {{ $emp['name'] }}</div>
                                                    <div class="text-xs text-gray-500">NIK: {{ $emp['nik'] }}</div>
                                                </div>
                                                <span
                                                    class="text-[10px] bg-gray-100 px-2 py-1 rounded-full">{{ $emp['division_name'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif(strlen($search_keyword) >= 2)
                                <div
                                    class="absolute z-10 w-full bg-white border border-red-200 rounded-lg mt-1 shadow p-3 text-center text-red-500 text-sm">
                                    Data tidak ditemukan</div>
                            @endif
                        </div>
                    @else
                        {{-- MODE UMUM: INPUT TEXT BIASA --}}
                        <input type="text" wire:model="title" placeholder="Contoh: Toko Makmur Jaya"
                            class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm py-2.5">
                    @endif
                    @error('title')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- >>> KOLOM KANAN: SUPERVISOR & UPLOAD <<< --}}
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 h-full">

                @if ($type === 'pengobatan')
                    {{-- UPLOAD KHUSUS PENGOBATAN --}}
                    <div class="space-y-4">
                        <h4
                            class="font-bold text-sm text-red-700 flex items-center gap-2 border-b border-red-200 pb-2 mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Dokumen Medis Wajib
                        </h4>

                        {{-- 1. KWITANSI --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 mb-1 block">Kwitansi RS/Klinik <span
                                    class="text-red-500">*</span></label>
                            <input type="file" wire:model="attachment_receipt" accept="image/*,.pdf"
                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @error('attachment_receipt')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- 2. RESEP --}}
                        <div>
                            <label class="text-xs font-bold text-gray-700 mb-1 block">Resep Dokter <span
                                    class="text-red-500">*</span></label>
                            <input type="file" wire:model="attachment_prescription" accept="image/*,.pdf"
                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @error('attachment_prescription')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @else
                    {{-- INPUT SUPERVISOR (UNTUK UMUM) --}}
                    <div class="space-y-4">
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                            <span
                                class="w-6 h-6 bg-yellow-100 text-yellow-600 rounded flex items-center justify-center text-xs">3</span>
                            Supervisor Penyetuju
                        </label>

                        @if (count($supervisors) > 0)
                            <select wire:model="selected_approver_id"
                                class="block w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200 text-sm py-2.5">
                                <option value="">-- Pilih Supervisor --</option>
                                @foreach ($supervisors as $spv)
                                    <option value="{{ $spv['id'] ?? $spv->id }}">{{ $spv['name'] ?? $spv->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('selected_approver_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        @else
                            <div
                                class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-xs text-yellow-800 flex gap-2">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Tidak ada Supervisor. Pengajuan akan langsung ke Manager.</span>
                            </div>
                        @endif

                        {{-- UPLOAD LAMPIRAN UMUM --}}
                        <div class="pt-4 border-t border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Lampiran (Struk/Invoice) <span
                                    class="text-red-600">*</span></label>

                            <div class="relative">
                                <input type="file" wire:model="attachment" accept="image/*,.pdf"
                                    class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border rounded-lg p-2 bg-white">

                                {{-- Loading Indicator --}}
                                <div wire:loading wire:target="attachment" class="absolute top-2 right-2">
                                    <span
                                        class="text-xs text-blue-600 font-bold bg-white px-2 py-1 rounded shadow animate-pulse">⏳
                                        Uploading...</span>
                                </div>
                            </div>

                            {{-- Preview File --}}
                            @if ($attachment && !is_string($attachment))
                                <div class="mt-2 flex items-center gap-3 bg-white p-2 rounded border border-blue-100">
                                    @if (in_array($attachment->extension(), ['jpg', 'jpeg', 'png', 'webp']))
                                        <img src="{{ $attachment->temporaryUrl() }}"
                                            class="h-10 w-10 object-cover rounded">
                                    @else
                                        <div
                                            class="h-10 w-10 flex items-center justify-center bg-red-100 text-red-500 rounded font-bold text-xs">
                                            PDF</div>
                                    @endif
                                    <div class="text-xs text-gray-600 overflow-hidden">
                                        <p class="font-bold truncate">{{ $attachment->getClientOriginalName() }}</p>
                                        <p>{{ round($attachment->getSize() / 1024) }} KB</p>
                                    </div>
                                </div>
                            @endif
                            @error('attachment')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif
            </div>

        </div>
        {{-- 3. SECTION FULL WIDTH (RINCIAN BIAYA) --}}
        {{-- RINCIAN BIAYA --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-b border-gray-200">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                    Rincian Biaya
                </h3>
                <button type="button" wire:click="addItem"
                    class="text-blue-600 hover:text-blue-800 text-sm font-bold flex items-center gap-1 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Tambah Baris
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Item / Deskripsi
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-1/3">COA (Akun)
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase w-32">Nominal (Rp)
                            </th>
                            <th class="px-2 py-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($items as $index => $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <input type="text" wire:model="items.{{ $index }}.item_name"
                                        class="w-full text-sm rounded border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-200"
                                        placeholder="Nama barang...">
                                    @error('items.' . $index . '.item_name')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </td>
                                <td class="px-4 py-3">
                                    <select wire:model="items.{{ $index }}.coa_id"
                                        class="w-full text-sm rounded border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-200">
                                        <option value="">-- Pilih COA --</option>
                                        @foreach ($coas as $c)
                                            <option value="{{ $c->id }}">{{ $c->code }} -
                                                {{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('items.' . $index . '.coa_id')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" wire:model.live="items.{{ $index }}.amount"
                                        class="w-full text-sm rounded border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-200 text-right"
                                        placeholder="0">
                                    @error('items.' . $index . '.amount')
                                        <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </td>
                                <td class="px-2 py-3 text-center">
                                    @if (count($items) > 1)
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                            class="text-gray-400 hover:text-red-500 transition"><svg class="w-5 h-5"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- TOTAL FOOTER --}}
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-200">
                <div class="text-sm font-bold text-gray-600">Total Pengajuan:</div>
                <div class="text-2xl font-extrabold text-blue-700">Rp {{ number_format($this->total, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- KETERANGAN TAMBAHAN --}}
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan Tambahan <span
                    class="font-normal text-gray-400">(Opsional)</span></label>
            <textarea wire:model="description" rows="2"
                class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 text-sm"
                placeholder="Catatan..."></textarea>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
            <button type="button" x-on:click="$dispatch('close-modal')"
                class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg font-bold hover:bg-gray-50 transition">Batal</button>

            <button type="button" wire:click="save('draft')" wire:loading.attr="disabled"
                class="px-6 py-2.5 bg-yellow-500 text-white rounded-lg font-bold hover:bg-yellow-600 transition disabled:opacity-50">
                <span wire:loading.remove wire:target="save('draft')">Simpan Draft</span>
                <span wire:loading wire:target="save('draft')">Menyimpan...</span>
            </button>

            <button type="submit" wire:loading.attr="disabled"
                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition shadow-lg disabled:opacity-50">
                <span wire:loading.remove wire:target="save">Kirim Pengajuan</span>
                <span wire:loading wire:target="save">Mengirim...</span>
            </button>
        </div>

    </form>
</div>
