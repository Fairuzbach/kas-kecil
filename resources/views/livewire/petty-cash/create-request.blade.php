<div class="max-w-4xl mx-auto py-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-xl font-bold mb-6 text-gray-800">Buat Pengajuan Kas Kecil</h2>

            @error('general')
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ $message }}
                </div>
            @enderror

            <form wire:submit="save" class="space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Judul Pengajuan</label>
                        <input type="text" wire:model="title"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            placeholder="Contoh: Beli Kertas A4">
                        @error('title')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Pengajuan</label>
                        <select wire:model="type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach ($types as $typeEnum)
                                <option value="{{ $typeEnum->value }}">{{ $typeEnum->label() }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            *Pagu (Skip Direktur), Invoice/Reimburse (Wajib Direktur)
                        </p>
                        @error('type')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nominal (Rp)</label>
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" wire:model="amount"
                                class="block w-full rounded-md border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="0">
                        </div>
                        @error('amount')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mata Anggaran (COA)</label>
                        <select wire:model="coa_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Pilih COA --</option>
                            @foreach ($coas as $coa)
                                <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}</option>
                            @endforeach
                        </select>
                        @error('coa_id')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Keterangan Detail</label>
                    <textarea wire:model="description" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Lampiran / Bukti (Opsional)</label>
                    <input type="file" wire:model="attachment"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <div wire:loading wire:target="attachment" class="text-xs text-blue-500 mt-1">Mengupload...</div>
                    @error('attachment')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-lg transition duration-150 ease-in-out">
                        <span wire:loading.remove>Kirim Pengajuan</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
