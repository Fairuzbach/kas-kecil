<div class="flex flex-col sm:flex-row sm:justify-end gap-4 w-full">
    {{-- SEARCH --}}
    <div class="w-full md:w-1/3">
        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Pencarian</label>
        <div class="relative">
            <input wire:model.live.debounce.300ms="search" type="text"
                class="w-full rounded-lg border-gray-300 text-sm pl-10 focus:ring-blue-500"
                placeholder="No. Tracking / Judul...">
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="flex flex-wrap gap-3 w-full md:w-auto justify-end">
        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Dept</label>
            <select wire:model.live="filterDept" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 py-2">
                <option value="all">Semua</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Status</label>
            <select wire:model.live="filterStatus" class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 py-2">
                <option value="all">Semua</option>
                @foreach (\App\Enums\PettyCashStatus::cases() as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>

        {{-- EXPORT --}}
        <div class="flex items-end">
            <button wire:click="exportExcel" wire:loading.attr="disabled"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-bold text-sm shadow-sm flex items-center gap-2 transition-all">
                <svg wire:loading.remove wire:target="exportExcel" class="w-4 h-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span wire:loading wire:target="exportExcel" class="animate-spin mr-1">⌛</span>
                XLSX
            </button>
        </div>
    </div>
</div>
