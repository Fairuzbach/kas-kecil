<div>
    <form wire:submit="save('pending_manager')" class="space-y-4">

        <div class="max-h-[70vh] overflow-y-auto px-1">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dibayar Kepada</label>
                    <input type="text" wire:model="title"
                        class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm">
                    @error('title')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis (Tipe Tiket)</label>
                    <select wire:model="type" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach ($types as $t)
                            <option value="{{ $t->value }}">{{ $t->label() }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Lampiran</label>
                <input type="file" wire:model="attachment"
                    class="mt-1 block w-full text-xs text-gray-500 border border-gray-300 rounded cursor-pointer">
                @error('attachment')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <hr class="my-4 border-gray-200">

            <div>
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-bold text-gray-700 text-sm">Rincian Barang</h3>
                    <button type="button" wire:click="addItem"
                        class="text-blue-600 hover:text-blue-800 text-xs font-bold">
                        + Tambah Baris
                    </button>
                </div>

                <table class="min-w-full border border-gray-200 mb-2">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-2 py-2 text-left">Item</th>
                            <th class="px-2 py-2 text-left w-1/3">COA</th>
                            <th class="px-2 py-2 text-left w-24">Rp</th>
                            <th class="px-1 py-2 w-8"></th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($items as $index => $item)
                            <tr class="border-t">
                                <td class="p-1">
                                    <input type="text" wire:model="items.{{ $index }}.item_name"
                                        class="w-full text-xs rounded border-gray-300 py-1" placeholder="Item...">
                                </td>
                                <td class="p-1">
                                    <select wire:model="items.{{ $index }}.coa_id"
                                        class="w-full text-xs rounded border-gray-300 py-1">
                                        <option value="">COA...</option>
                                        @foreach ($coas as $c)
                                            <option value="{{ $c->id }}">{{ $c->code }} -
                                                {{ $c->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-1">
                                    <input type="number" wire:model.live="items.{{ $index }}.amount"
                                        class="w-full text-xs rounded border-gray-300 py-1 text-right">
                                </td>
                                <td class="p-1 text-center">
                                    @if (count($items) > 1)
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                            class="text-red-500 font-bold hover:bg-red-50 rounded px-1">x</button>
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
                    <span class="text-red-500 text-xs block">* Cek kelengkapan data per baris</span>
                @break
            @endforeach

            <div class="text-right text-sm font-bold mt-2">
                Total: Rp {{ number_format($this->total, 0, ',', '.') }}
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
            <textarea wire:model="description" rows="2"
                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm"></textarea>
        </div>
    </div>
    <div class="mt-6 flex justify-between border-t pt-4">

        <button type="button" x-on:click="$dispatch('close-modal', 'create-request-modal')"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-sm font-bold">
            Batal
        </button>

        <div class="flex gap-2">
            <button type="button" wire:click="save('draft')"
                class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 text-sm font-bold shadow-sm flex items-center">
                <span wire:loading.remove wire:target="save('draft')">Simpan Draft</span>
                <span wire:loading wire:target="save('draft')">Menyimpan...</span>
            </button>

            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-bold shadow-sm flex items-center">
                <span wire:loading.remove wire:target="save('pending_manager')">Kirim Pengajuan</span>
                <span wire:loading wire:target="save('pending_manager')">Mengirim...</span>
            </button>
        </div>
    </div>

</form>
</div>
