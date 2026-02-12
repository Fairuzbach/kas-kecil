<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data x-on:request-created.window="$dispatch('close-modal', 'create-request-modal')">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex justify-end">
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-request-modal')"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 shadow transition font-bold flex items-center gap-2">
                    <span>+ Buat Pengajuan Baru</span>
                </button>
            </div>

            <livewire:petty-cash.index-table />

            <x-modal name="create-request-modal" :show="$errors->any()" focusable>

                <div class="p-6">


                    <livewire:petty-cash.create-request />
                </div>

            </x-modal>

        </div>
    </div>
</x-app-layout>
