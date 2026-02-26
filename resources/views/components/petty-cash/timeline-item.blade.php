@props([
    'title',
    'status' => 'wait', // wait, active, done, rejected
    'date' => null,
    'actor' => null, // Nama user (Approver / Rejector)
    'note' => null, // Alasan penolakan
    'approveMethod' => null,
    'rejectMethod' => null, // Method untuk memanggil Modal
    'isLast' => false,
])

@php
    // KONFIGURASI TAMPILAN (Warna & Icon)
    $config = match ($status) {
        'done' => [
            'color' => 'bg-green-500',
            'line' => 'bg-green-200',
            'icon' =>
                '<svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>',
        ],
        'rejected' => [
            'color' => 'bg-red-500',
            'line' => 'bg-red-200',
            'icon' =>
                '<svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>',
        ],
        'active' => [
            'color' => 'bg-blue-500',
            'line' => 'bg-gray-200',
            'icon' =>
                '<svg class="h-5 w-5 text-white animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        ],
        default => [
            'color' => 'bg-gray-400',
            'line' => 'bg-gray-200',
            'icon' => '<span class="h-2.5 w-2.5 bg-white rounded-full"></span>', // Dot kecil untuk pending
        ],
    };
@endphp

<div class="relative pb-8">

    {{-- 1. GARIS PENGHUBUNG (VERTICAL LINE) --}}
    @unless ($isLast)
        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 {{ $config['line'] }}" aria-hidden="true"></span>
    @endunless

    <div class="relative flex space-x-3">

        {{-- 2. BULATAN IKON --}}
        <div>
            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ $config['color'] }}">
                {!! $config['icon'] !!}
            </span>
        </div>

        {{-- 3. KONTEN (TEXT & BOX) --}}
        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
            <div class="w-full">
                {{-- JUDUL --}}
                <p class="text-sm font-medium text-gray-900">
                    {{ $title }}
                </p>

                {{-- OPSI 1: STATUS REJECTED (Tampilkan Kotak Merah & Alasan) --}}
                @if ($status === 'rejected')
                    <div class="mt-2 rounded-md bg-red-50 p-3 border border-red-100">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-red-800">Ditolak</h3>
                                <div class="mt-1 text-sm text-red-700 italic">
                                    "{{ $note ?? 'Tidak ada alasan.' }}"
                                </div>
                                @if ($actor)
                                    <div class="mt-2 text-xs text-red-600 font-medium border-t border-red-200 pt-1">
                                        Oleh: {{ $actor }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- OPSI 2: STATUS ACTIVE (Butuh Approval - Tampilkan Tombol) --}}
                @elseif($status === 'active' && $approveMethod)
                    <div class="mt-2 flex gap-2">
                        <button wire:click="confirmReject"
                            class="px-3 py-1.5 text-xs font-bold text-red-700 bg-white border border-red-300 rounded hover:bg-red-50 transition shadow-sm">
                            Tolak
                        </button>
                        <button wire:click="{{ $approveMethod }}" wire:confirm="Setujui pengajuan?"
                            class="px-3 py-1.5 text-xs font-bold text-white bg-blue-600 rounded hover:bg-blue-700 transition shadow-sm">
                            Setujui
                        </button>
                    </div>

                    {{-- OPSI 3: STATUS DONE (Info User) --}}
                @elseif($status === 'done' && $actor)
                    <p class="text-sm text-gray-500">
                        Diajukan oleh <span class="font-medium text-gray-900">{{ $actor }}</span>
                    </p>

                    {{-- OPSI 4: DEFAULT (Pending) --}}
                @else
                    <p class="text-sm text-gray-500">Menunggu proses...</p>
                @endif
            </div>

            {{-- TANGGAL DI KANAN --}}
            @if ($date)
                <div class="whitespace-nowrap text-right text-sm text-gray-500">
                    <time>{{ $date }}</time>
                </div>
            @endif
        </div>
    </div>
</div>
