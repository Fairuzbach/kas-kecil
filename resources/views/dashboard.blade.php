<x-app-layout>
    <x-slot name="header">
        {{-- Ubah text-xl jadi text-lg di mobile agar pas --}}
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    {{-- Tambahkan px-4 untuk padding mobile --}}
    <div class="py-6 sm:py-12" x-data x-on:request-created.window="$dispatch('close-modal', 'create-request-modal')">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- 
                RESPONSIVE BUTTON WRAPPER 
                Mobile: Flex Column (atas bawah)
                Desktop: Flex Row (menyamping) & Justify End
            --}}
            <div class="flex flex-col sm:flex-row sm:justify-end gap-4">
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-request-modal')"
                    class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-3 sm:py-2 rounded-lg hover:bg-indigo-700 shadow-lg sm:shadow transition font-bold flex items-center justify-center gap-2 active:scale-95">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Buat Pengajuan Baru</span>
                </button>

            </div>

            {{-- Table Wrapper agar bisa scroll horizontal jika tabel lebar --}}
            <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
                <livewire:petty-cash.index-table />
            </div>

            <x-modal name="create-request-modal" :show="$errors->any()" focusable>
                {{-- Padding lebih kecil di mobile (p-4) biar muat banyak --}}
                <div class="p-4 sm:p-6 max-h-[85vh] overflow-y-auto">
                    <livewire:petty-cash.create-request />
                </div>
            </x-modal>

        </div>
    </div>
</x-app-layout>
