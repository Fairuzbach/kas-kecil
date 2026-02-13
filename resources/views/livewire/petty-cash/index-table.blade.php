<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-4 sm:p-6 text-gray-900">

        {{-- Header Section --}}
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Riwayat Pengajuan</h3>
            <button wire:click="$refresh"
                class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                <span class="hidden sm:inline font-medium">Refresh Data</span>
            </button>
        </div>

        {{-- ========================================== --}}
        {{-- VIEW DESKTOP (TABEL BIASA) --}}
        {{-- ========================================== --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 font-bold">Tracking No</th>
                        <th class="px-6 py-3 font-bold">Department</th> {{-- 👈 KOLOM BARU --}}
                        <th class="px-6 py-3 font-bold">Judul</th>
                        <th class="px-6 py-3 font-bold">Nominal</th>
                        <th class="px-6 py-3 font-bold">Tipe</th>
                        <th class="px-6 py-3 font-bold">Status</th>
                        <th class="px-6 py-3 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $req)
                        <tr class="bg-white hover:bg-gray-50 transition-colors group">
                            {{-- Tracking Number --}}
                            <td class="px-6 py-4 font-mono font-medium text-gray-900 text-xs whitespace-nowrap">
                                {{ $req->tracking_number ?? '-' }}
                                <div class="text-[10px] text-gray-400 mt-1">{{ $req->created_at->format('d M Y') }}
                                </div>
                            </td>

                            {{-- Department (BARU) --}}
                            <td class="px-6 py-4">
                                @if ($req->department)
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-gray-800 text-xs">{{ $req->department->code }}</span>
                                        <span class="text-[10px] text-gray-500 truncate w-32"
                                            title="{{ $req->department->name }}">
                                            {{ Str::limit($req->department->name, 20) }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-xs">-</span>
                                @endif
                            </td>

                            {{-- Judul & User --}}
                            <td class="px-6 py-4">
                                <div
                                    class="font-bold text-gray-800 line-clamp-1 group-hover:text-indigo-600 transition-colors">
                                    {{ $req->title }}
                                </div>
                                <div class="flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    <span class="text-xs text-gray-500">{{ $req->user->name }}</span>
                                </div>
                            </td>

                            {{-- Nominal --}}
                            <td class="px-6 py-4 font-mono font-bold text-gray-700 text-right whitespace-nowrap">
                                Rp {{ number_format($req->amount, 0, ',', '.') }}
                            </td>

                            {{-- Tipe --}}
                            <td class="px-6 py-4">
                                <span
                                    class="px-2.5 py-1 text-[10px] rounded-full bg-gray-100 text-gray-600 border border-gray-200 uppercase font-bold tracking-wide">
                                    {{ $req->type->label() ?? $req->type }}
                                </span>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'draft' => 'gray',
                                        'pending_supervisor' => 'orange',
                                        'pending_manager' => 'yellow',
                                        'pending_director' => 'blue',
                                        'pending_finance' => 'indigo',
                                        'pending_hc' => 'pink',
                                        'paid' => 'emerald',
                                        'approved' => 'green',
                                        'rejected' => 'red',
                                    ];
                                    $color = $colors[$req->status->value] ?? 'gray';
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-200 whitespace-nowrap">
                                    <span class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-500 mr-1.5"></span>
                                    {{ $req->status->label() }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('petty-cash.show', $req->id) }}" wire:navigate
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition-all shadow-sm"
                                    title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div
                                        class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada data pengajuan</p>
                                    <p class="text-gray-400 text-xs mt-1">Silakan buat pengajuan baru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ========================================== --}}
        {{-- VIEW MOBILE (CARD LAYOUT) --}}
        {{-- ========================================== --}}
        <div class="md:hidden space-y-4">
            @forelse($requests as $req)
                <div
                    class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm active:scale-[0.99] transition-transform relative overflow-hidden">

                    {{-- Status Strip (Garis warna di kiri) --}}
                    @php $color = $colors[$req->status->value] ?? 'gray'; @endphp
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-{{ $color }}-500"></div>

                    <div class="pl-3"> {{-- Padding kiri utk kompensasi garis --}}

                        <div class="flex justify-between items-start mb-2">
                            {{-- Tracking & Dept --}}
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-[10px] font-mono font-bold text-gray-500 bg-gray-100 px-1.5 py-0.5 rounded">
                                    #{{ $req->tracking_number }}
                                </span>
                                @if ($req->department)
                                    <span
                                        class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded border border-indigo-100">
                                        {{ $req->department->code }}
                                    </span>
                                @endif
                            </div>

                            {{-- Status Badge Kecil --}}
                            <span
                                class="text-[10px] font-bold text-{{ $color }}-700 bg-{{ $color }}-50 px-2 py-0.5 rounded-full border border-{{ $color }}-100">
                                {{ $req->status->label() }}
                            </span>
                        </div>

                        {{-- Judul --}}
                        <h4 class="font-bold text-gray-900 text-sm mb-1 leading-snug">{{ $req->title }}</h4>

                        {{-- User & Tanggal --}}
                        <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ Str::limit($req->user->name, 15) }}
                            </span>
                            <span>•</span>
                            <span>{{ $req->created_at->format('d M H:i') }}</span>
                        </div>

                        {{-- Footer Card --}}
                        <div class="flex justify-between items-end border-t border-gray-100 pt-3">
                            <div>
                                <span
                                    class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">Nominal</span>
                                <span class="font-mono font-bold text-gray-800 text-sm">
                                    Rp {{ number_format($req->amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <a href="{{ route('petty-cash.show', $req->id) }}" wire:navigate
                                class="inline-flex items-center gap-1 bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm hover:bg-indigo-700 transition-colors">
                                Detail
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <p class="text-gray-500 text-sm font-medium">Belum ada data pengajuan</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    </div>
</div>
