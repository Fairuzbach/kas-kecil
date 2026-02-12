<div>
    <form wire:submit="save('pending_manager')" class="space-y-4">

        <div class="max-h-[70vh] overflow-y-auto px-1">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Kode Department dan Department Pemohon</label>
                <input type="text" wire:model="user_department" readonly
                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 text-sm shadow-sm cursor-not-allowed">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis (Tipe Tiket) <span
                            class="text-red-500">*</span></label>
                    <select wire:model.live="type"
                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach ($types as $t)
                            <option value="{{ $t->value }}">{{ $t->label() }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    @if ($type === 'pengobatan')
                        <label class="block text-sm font-bold text-teal-700">Cari Karyawan <span
                                class="text-red-500">*</span></label>

                        <div class="relative mt-1">

                            <input type="text" wire:model="title" readonly
                                placeholder="Hasil pilihan akan muncul disini..."
                                class="block w-full rounded-md border-teal-300 bg-gray-100 text-sm shadow-sm font-bold text-gray-700 mb-2 cursor-not-allowed">

                            <div class="relative">
                                <input type="text" wire:model.live.debounce.300ms="search_keyword"
                                    placeholder="🔍 Ketik Nama atau NIK (Min. 2 huruf)..."
                                    class="block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-8">

                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>

                                <div wire:loading wire:target="search_keyword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg class="animate-spin h-4 w-4 text-indigo-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </div>

                            @if (!empty($employee_result))
                                <ul
                                    class="absolute z-50 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg max-h-60 overflow-y-auto">
                                    @foreach ($employee_result as $emp)
                                        <li wire:click="selectEmployee('{{ $emp['name'] }}', '{{ $emp['nik'] }}', '{{ $emp['division_name'] }}')"
                                            class="px-4 py-2 hover:bg-teal-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors group">

                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <div
                                                        class="font-bold text-sm text-gray-800 group-hover:text-teal-700">
                                                        {{ $emp['name'] }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        NIK: {{ $emp['nik'] }}
                                                    </div>
                                                </div>


                                                <div
                                                    class="text-[10px] font-bold bg-gray-100 text-gray-600 px-2 py-1 rounded group-hover:bg-teal-100 group-hover:text-teal-800">
                                                    {{ $emp['division_name'] }}
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if (strlen($search_keyword) >= 2 && empty($employee_result))
                                <div
                                    class="absolute z-50 w-full bg-white border border-gray-300 rounded-md mt-1 shadow-lg p-2 text-center text-xs text-red-500">
                                    Data tidak ditemukan.
                                </div>
                            @endif

                        </div>
                        <p class="text-[10px] text-gray-500 mt-1">
                            *Data difilter berdasarkan divisi: {{ auth()->user()->department->name ?? '-' }}
                        </p>
                    @else
                        <label class="block text-sm font-medium text-gray-700">Dibayar Kepada / Keperluan <span
                                class="text-red-500">*</span></label>
                        <input type="text" wire:model="title" placeholder="Contoh: Toko Makmur Jaya"
                            class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm">
                    @endif

                    @error('title')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div
                class="mb-4 p-4 rounded-lg border {{ $type === 'pengobatan' ? 'bg-teal-50 border-teal-200' : 'bg-gray-50 border-gray-200' }}">

                {{-- Logic Tampilan Supervisor --}}
                @if ($type !== 'pengobatan')
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Supervisor Penyetuju</label>
                        @if (count($supervisors) > 0)
                            <select wire:model="selected_approver_id"
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full mt-1">
                                <option value="">-- Pilih Supervisor --</option>
                                @foreach ($supervisors as $spv)
                                    <option value="{{ $spv['id'] ?? $spv->id }}">
                                        {{ $spv['name'] ?? $spv->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('selected_approver_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        @else
                            <div
                                class="mt-1 p-3 bg-blue-50 border border-blue-200 rounded-md text-sm text-blue-700 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>
                                    Tidak ada Supervisor ditemukan. Pengajuan akan <strong>langsung dikirim ke
                                        Manager</strong>.
                                </span>
                            </div>
                        @endif
                    </div>

                @endif
                @if ($type === 'pengobatan')
                    <h4 class="font-bold text-sm text-teal-800 mb-3 flex items-center gap-2">
                        🏥 Dokumen Medis Wajib
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">1. Kwitansi RS/Klinik <span
                                    class="text-red-500">*</span></label>
                            <input type="file" wire:model="attachment_receipt" accept="image/*,application/pdf"
                                class="block w-full text-xs text-gray-500 border border-gray-300 rounded cursor-pointer bg-white">
                            @error('attachment_receipt')
                                <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">2. Resep Dokter <span
                                    class="text-red-500">*</span></label>
                            <input type="file" wire:model="attachment_prescription" accept="image/*,application/pdf"
                                class="block w-full text-xs text-gray-500 border border-gray-300 rounded cursor-pointer bg-white">
                            @error('attachment_prescription')
                                <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <p class="text-[10px] text-gray-500 mt-2">*Format: JPG, PNG, PDF. Max 2MB.</p>
                @else
                    <label class="block text-sm font-medium text-gray-700">Lampiran (Struk/Invoice)</label>
                    <input type="file" wire:model="attachment" accept="image/*,application/pdf"
                        class="mt-1 block w-full text-xs text-gray-500 border border-gray-300 rounded cursor-pointer bg-white">
                    @error('attachment')
                        <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                    @enderror
                @endif

                <div wire:loading wire:target="attachment, attachment_receipt, attachment_prescription"
                    class="text-xs text-blue-600 mt-2 font-bold animate-pulse">
                    Sedang mengupload dokumen... Harap tunggu.
                </div>
            </div>

            <hr class="my-4 border-gray-200">

            <div>
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-bold text-gray-700 text-sm">Rincian Biaya</h3>
                    <button type="button" wire:click="addItem"
                        class="text-blue-600 hover:text-blue-800 text-xs font-bold">
                        + Tambah Baris
                    </button>
                </div>

                <table class="min-w-full border border-gray-200 mb-2">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-2 py-2 text-left">Item / Deskripsi</th>
                            <th class="px-2 py-2 text-left w-1/3">COA (Akun)</th>
                            <th class="px-2 py-2 text-left w-24">Nominal (Rp)</th>
                            <th class="px-1 py-2 w-8"></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($items as $index => $item)
                            <tr class="border-t align-top">

                                <td class="p-1">
                                    <input type="text" wire:model="items.{{ $index }}.item_name"
                                        class="w-full text-xs rounded border-gray-300 py-1 focus:border-teal-500 focus:ring-teal-500 
                           @error('items.' . $index . '.item_name') border-red-500 @enderror"
                                        placeholder="Nama barang/jasa...">


                                    @error('items.' . $index . '.item_name')
                                        <div class="text-[10px] text-red-500 mt-1">{{ $message }}</div>
                                    @enderror
                                </td>

                                <td class="p-1">
                                    <select wire:model="items.{{ $index }}.coa_id"
                                        class="w-full text-xs rounded border-gray-300 py-1 focus:border-teal-500 focus:ring-teal-500
                           @error('items.' . $index . '.coa_id') border-red-500 @enderror">

                                        <option value="">
                                            -- Pilih COA {{ $type === 'pengobatan' ? '(Opsional)' : '' }} --
                                        </option>

                                        @foreach ($coas as $c)
                                            <option value="{{ $c->id }}">
                                                {{ $c->code }} - {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>


                                    @error('items.' . $index . '.coa_id')
                                        <div class="text-[10px] text-red-500 mt-1">{{ $message }}</div>
                                    @enderror
                                </td>

                                <td class="p-1">
                                    <input type="number" wire:model.live="items.{{ $index }}.amount"
                                        class="w-full text-xs rounded border-gray-300 py-1 text-right focus:border-teal-500 focus:ring-teal-500
                           @error('items.' . $index . '.amount') border-red-500 @enderror"
                                        placeholder="0">


                                    @error('items.' . $index . '.amount')
                                        <div class="text-[10px] text-red-500 mt-1">{{ $message }}</div>
                                    @enderror
                                </td>

                                <td class="p-1 text-center align-middle">
                                    @if (count($items) > 1)
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                            class="text-red-400 hover:text-red-600 hover:bg-red-50 p-1 rounded transition-colors"
                                            title="Hapus Baris">

                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>


                @error('items')
                    <span class="text-red-500 text-xs block">{{ $message }}</span>
                @enderror
                @foreach ($errors->get('items.*') as $message)
                    <span class="text-red-500 text-xs block">* Mohon lengkapi data barang dan nominal.</span>
                @break
            @endforeach

            <div class="text-right text-sm font-bold mt-2 bg-gray-50 p-2 rounded">
                Total Pengajuan: Rp {{ number_format($this->total, 0, ',', '.') }}
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Keterangan Tambahan</label>
            <textarea wire:model="description" rows="2" placeholder="Catatan opsional..."
                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm"></textarea>
        </div>
    </div>

    <div class="mt-6 flex justify-between border-t pt-4">
        <button type="button" x-on:click="$dispatch('close-modal', 'create-request-modal')"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-sm font-bold">
            Batal
        </button>

        <div class="flex gap-2">
            <button type="button" wire:click="save('draft')" wire:loading.attr="disabled"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-sm font-bold shadow-sm flex items-center disabled:opacity-50">
                <span wire:loading.remove wire:target="save('draft')">Simpan Draft</span>
                <span wire:loading wire:target="save('draft')">Menyimpan...</span>
            </button>

            <button type="submit" wire:loading.attr="disabled"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-bold shadow-sm flex items-center disabled:opacity-50">
                <span wire:loading.remove wire:target="save">Kirim Pengajuan</span>
                <span wire:loading wire:target="save">Mengirim...</span>
            </button>
        </div>
    </div>
</form>
</div>
