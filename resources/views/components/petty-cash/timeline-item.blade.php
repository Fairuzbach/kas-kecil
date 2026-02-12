@props([
    'title',
    'status' => 'wait', // wait, active, done, rejected
    'date' => null,
    'actor' => null, // Nama orang yang approve
    'approveMethod' => null, // Nama fungsi Livewire: 'approveManager'
    'rejectMethod' => null, // Nama fungsi Livewire: 'reject'
    'isLast' => false,
])

@php
    // Logika Warna (Bisa ditaruh disini agar file induk bersih)
    $styles = [
        'done' => [
            'icon_bg' => 'bg-green-500',
            'ring' => 'ring-green-50',
            'card' => 'bg-green-50 border-green-200',
            'badge' => 'bg-green-500',
            'text' => '✓ Selesai',
        ],
        'active' => [
            'icon_bg' => 'bg-blue-500',
            'ring' => 'ring-blue-50',
            'card' => 'bg-blue-50 border-blue-200',
            'badge' => 'bg-blue-500',
            'text' => '⏳ Menunggu',
        ],
        'rejected' => [
            'icon_bg' => 'bg-red-500',
            'ring' => 'ring-red-50',
            'card' => 'bg-red-50 border-red-200',
            'badge' => 'bg-red-500',
            'text' => '✕ Ditolak',
        ],
        'wait' => [
            'icon_bg' => 'bg-gray-300',
            'ring' => 'ring-gray-50',
            'card' => 'bg-gray-50 border-gray-200',
            'badge' => 'bg-gray-300 text-gray-600',
            'text' => '⚪ Pending',
        ],
    ];

    // Fallback jika status tidak dikenali
    $config = $styles[$status] ?? $styles['wait'];
@endphp

<div class="relative flex items-start mb-6 group">
    {{-- Garis Vertikal (Kecuali item terakhir) --}}
    @if (!$isLast)
        <div class="absolute left-4 top-8 bottom-[-24px] w-0.5 bg-gray-200 group-hover:bg-gray-300 transition-colors">
        </div>
    @endif

    {{-- IKON BULAT --}}
    <div
        class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full shadow-md ring-4 {{ $config['ring'] }} {{ $config['icon_bg'] }} flex-shrink-0 transition-all">
        @if ($status === 'done')
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        @elseif($status === 'rejected')
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        @elseif($status === 'active')
            <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
        @else
            <div class="w-2 h-2 bg-white rounded-full opacity-50"></div>
        @endif
    </div>

    {{-- KARTU KONTEN --}}
    <div class="ml-4 flex-1 {{ $config['card'] }} rounded-lg p-3 border transition-all hover:shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <h4 class="text-sm font-semibold text-gray-800">{{ $title }}</h4>
            <span class="px-2 py-0.5 {{ $config['badge'] }} text-white text-[10px] font-medium rounded-full">
                {{ $config['text'] }}
            </span>
        </div>

        {{-- LOGIKA TOMBOL ACTION (Tetap nyambung ke Livewire Induk) --}}
        @if ($status === 'active' && $approveMethod)
            <div class="flex gap-2 mt-2">
                <button wire:click="{{ $rejectMethod }}" wire:confirm="Tolak pengajuan ini?"
                    class="flex-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition">
                    Tolak
                </button>
                <button wire:click="{{ $approveMethod }}" wire:confirm="Setujui pengajuan ini?"
                    class="flex-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded transition">
                    Setuju
                </button>
            </div>
        @elseif($status === 'done' && $actor)
            <p class="text-xs text-gray-600 mt-1">
                Oleh: <span class="font-semibold">{{ $actor }}</span>
                @if ($date)
                    <span class="text-gray-400">({{ $date }})</span>
                @endif
            </p>
        @endif

        {{-- Slot untuk konten custom (misal tombol Finance) --}}
        {{ $slot }}
    </div>
</div>
