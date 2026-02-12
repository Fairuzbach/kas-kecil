{{-- Ini hanya UI Modal, logic state-nya tetap di parent (AlpineJS) --}}
<div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
    x-transition.opacity>

    <div class="fixed inset-0 bg-gray-900 bg-opacity-75" @click="showModal = false"></div>

    <div class="relative bg-white rounded-lg shadow-xl overflow-hidden w-full max-w-5xl h-[80vh] flex flex-col"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100">

        <div class="flex justify-between items-center px-4 py-3 border-b bg-gray-50">
            <h3 class="text-sm font-bold text-gray-700">Preview</h3>
            <button @click="showModal = false" class="text-gray-400 hover:text-red-500">✕</button>
        </div>

        <div class="p-4 bg-gray-100 flex-1 flex items-center justify-center overflow-auto">
            <template x-if="modalType === 'image'">
                <img :src="modalUrl" class="max-w-full max-h-full object-contain shadow-md">
            </template>
            <template x-if="modalType === 'pdf'">
                <iframe :src="modalUrl" class="w-full h-full border-0"></iframe>
            </template>
        </div>
    </div>
</div>
