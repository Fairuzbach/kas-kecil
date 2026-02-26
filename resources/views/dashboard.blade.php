<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-lg sm:text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-xs text-gray-400 font-normal">Kelola pengajuan petty cash</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12 min-h-screen bg-gray-50" x-data
        x-on:request-created.window="$dispatch('close-modal', 'create-request-modal')">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Toolbar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <livewire:petty-cash.dashboard-controls />

                <button x-data x-on:click.prevent="$dispatch('open-modal', 'create-request-modal')"
                    class="inline-flex items-center justify-center gap-2 w-full sm:w-auto
                           bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800
                           text-white text-sm font-semibold
                           px-5 py-2.5 rounded-xl
                           shadow-md shadow-indigo-200
                           transition-all duration-150 active:scale-95">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Pengajuan Baru
                </button>
            </div>

            {{-- Tabel --}}
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <livewire:petty-cash.index-table />
                </div>
            </div>

        </div>
    </div>

    {{-- Modal --}}
    <x-modal name="create-request-modal" :show="$errors->any()" focusable maxWidth="5xl">
        <div class="p-4 max-h-[95vh] overflow-y-auto">

            <livewire:petty-cash.create-request />
        </div>
    </x-modal>

</x-app-layout>
