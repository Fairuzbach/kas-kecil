<div class="flex flex-col sm:flex-row sm:items-end gap-3 w-full">

    {{-- SEARCH --}}
    <div class="flex-1 min-w-0 group">
        <label
            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1 transition-colors group-focus-within:text-indigo-500">
            Pencarian
        </label>
        <div class="relative">
            <div
                class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400 transition-colors group-focus-within:text-indigo-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="w-full rounded-lg border-gray-300 text-sm pl-9 pr-4 py-2
                       focus:ring-indigo-500 focus:border-indigo-500 shadow-sm
                       transition-shadow duration-200 focus:shadow-md"
                placeholder="No. Tracking / Judul...">

            {{-- Clear button --}}
            <div wire:loading.remove wire:target="search" class="absolute inset-y-0 right-2 flex items-center">
                <button type="button" wire:click="$set('search', '')"
                    class="text-gray-300 hover:text-gray-500 transition-opacity duration-150
                           opacity-0 group-focus-within:opacity-100 hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Loading spinner di dalam input --}}
            <div wire:loading wire:target="search" class="absolute inset-y-0 right-3 flex items-center">
                <svg class="w-4 h-4 animate-spin text-indigo-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- FILTER DEPT --}}
    @if (auth()->user()->role === 'finance')
        <div class="shrink-0 group">
            <label
                class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1 transition-colors group-focus-within:text-indigo-500">
                Dept
            </label>
            <select wire:model.live="filterDept"
                class="rounded-lg border-gray-300 text-sm py-2 pl-3 pr-8 shadow-sm
                       focus:ring-indigo-500 focus:border-indigo-500
                       transition-shadow duration-200 focus:shadow-md cursor-pointer">
                <option value="all">Semua Dept</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    {{-- FILTER STATUS --}}
    <div class="shrink-0 group">
        <label
            class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1 transition-colors group-focus-within:text-indigo-500">
            Status
        </label>
        <select wire:model.live="filterStatus"
            class="rounded-lg border-gray-300 text-sm py-2 pl-3 pr-8 shadow-sm
                   focus:ring-indigo-500 focus:border-indigo-500
                   transition-shadow duration-200 focus:shadow-md cursor-pointer">
            <option value="all">Semua Status</option>
            @foreach (\App\Enums\PettyCashStatus::cases() as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>

    {{-- EXPORT --}}
    <div class="shrink-0">
        <label class="block text-xs font-bold text-transparent uppercase tracking-wide mb-1 select-none"></label>
        <button wire:click="exportExcel" wire:loading.attr="disabled" wire:target="exportExcel"
            class="group relative flex items-center gap-2 px-4 py-2 overflow-hidden
                   bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60
                   text-white text-sm font-bold rounded-lg shadow-sm
                   transition-all duration-200 hover:shadow-md hover:-translate-y-px active:translate-y-0 active:shadow-sm
                   whitespace-nowrap">

            {{-- Shimmer effect --}}
            <span
                class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-500 bg-white/10 skew-x-12"></span>

            <svg wire:loading.remove wire:target="exportExcel"
                class="w-4 h-4 shrink-0 transition-transform duration-200 group-hover:scale-110" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <svg wire:loading wire:target="exportExcel" class="w-4 h-4 animate-spin shrink-0" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            Export XLSX
        </button>
    </div>

</div>
