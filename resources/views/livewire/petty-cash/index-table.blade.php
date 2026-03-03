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
        {{-- VIEW DESKTOP (TABEL DAFTAR PENGAJUAN) --}}
        {{-- ========================================== --}}
        <div class="hidden md:block w-full overflow-x-auto">
            <table class="w-full border-collapse border border-gray-200 text-sm mb-6 text-left">
                <thead>
                    <tr class="bg-gray-100 font-bold border-b-2 border-gray-300">
                        <th class="border border-gray-200 px-4 py-3 w-16 text-center">NO.</th>
                        <th class="border border-gray-200 px-4 py-3">REF NO.</th>
                        <th class="border border-gray-200 px-4 py-3">TANGGAL</th>
                        <th class="border border-gray-200 px-4 py-3">PEMOHON</th>
                        <th class="border border-gray-200 px-4 py-3">DIBAYAR KEPADA</th>
                        <th class="border border-gray-200 px-4 py-3 text-right">TOTAL</th>
                        <th class="border border-gray-200 px-4 py-3 text-center">STATUS</th>
                        <th class="border border-gray-200 px-4 py-3 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $index => $req)
                        @php
                            // Tentukan warna status secara eksplisit agar terbaca Tailwind
                            $statusColor = match ($req->status->value) {
                                'draft' => 'bg-gray-100 text-gray-700 border-gray-300',
                                'pending_supervisor',
                                'pending_manager',
                                'pending_director'
                                    => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                'pending_finance',
                                'pending_finance_manager'
                                    => 'bg-blue-100 text-blue-800 border-blue-300',
                                'revision' => 'bg-orange-100 text-orange-800 border-orange-300',
                                'paid' => 'bg-green-100 text-green-800 border-green-300',
                                'rejected' => 'bg-red-100 text-red-800 border-red-300',
                                default => 'bg-gray-100 text-gray-700 border-gray-300',
                            };
                        @endphp
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                            <td class="border-x border-gray-200 px-4 py-3 text-center text-gray-500">
                                {{ $requests->firstItem() + $index }}.
                            </td>
                            <td class="border-x border-gray-200 px-4 py-3 font-mono font-bold text-gray-700">
                                #{{ $req->tracking_number }}
                            </td>
                            <td class="border-x border-gray-200 px-4 py-3 text-gray-600">
                                {{ $req->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                            </td>
                            <td class="border-x border-gray-200 px-4 py-3 font-medium text-gray-900">
                                {{ $req->user->name }}
                            </td>
                            <td class="border-x border-gray-200 px-4 py-3 text-gray-800 uppercase text-xs font-bold">
                                {{ Str::limit($req->title, 30) }}
                            </td>
                            <td class="border-x border-gray-200 px-4 py-3 text-right whitespace-nowrap">
                                <div class="flex flex-col items-end">
                                    <span class="font-bold text-gray-900">
                                        Rp {{ number_format($req->amount, 0, ',', '.') }}
                                    </span>

                                    {{-- Cek Selisih OCR --}}
                                    @php
                                        $ocrTotal = $req->details->sum('amount_ocr');
                                        $isMismatch = $ocrTotal > 0 && abs($ocrTotal - $req->amount) >= 0.01;
                                    @endphp

                                    @if ($isMismatch && in_array(auth()->user()->role, ['finance', 'manager', 'director']))
                                        <span
                                            class="text-[9px] text-red-700 bg-red-100 px-1.5 py-0.5 rounded border border-red-300 mt-1 shadow-sm font-bold animate-pulse"
                                            title="Scan Asli: Rp {{ number_format($ocrTotal, 0, ',', '.') }}">
                                            ⚠️ Tidak sesuai dengan lampiran
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="border-x border-gray-200 px-4 py-3 text-center">
                                {{-- Warna status dipanggil di sini --}}
                                <span
                                    class="text-[10px] font-bold px-2.5 py-1 rounded-full border whitespace-nowrap {{ $statusColor }}">
                                    {{ $req->status->label() }}
                                </span>
                            </td>
                            <td class="border-x border-gray-200 px-4 py-3 text-center">
                                <a href="{{ route('petty-cash.show', $req->id) }}" wire:navigate
                                    class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-600 border border-indigo-200 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm hover:bg-indigo-600 hover:text-white transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 bg-gray-50 border border-gray-200">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-10 h-10 mb-2 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-sm font-medium">Belum ada riwayat pengajuan.</p>
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
                @php
                    // Warna Strip Samping Kiri
                    $stripColor = match ($req->status->value) {
                        'draft' => 'bg-gray-500',
                        'pending_supervisor', 'pending_manager', 'pending_director' => 'bg-yellow-500',
                        'pending_finance', 'pending_finance_manager' => 'bg-blue-500',
                        'revision' => 'bg-orange-500',
                        'paid' => 'bg-green-500',
                        'rejected' => 'bg-red-500',
                        default => 'bg-gray-500',
                    };

                    // Warna Latar Badge
                    $badgeColor = match ($req->status->value) {
                        'draft' => 'bg-gray-100 text-gray-700 border-gray-300',
                        'pending_supervisor',
                        'pending_manager',
                        'pending_director'
                            => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'pending_finance', 'pending_finance_manager' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'revision' => 'bg-orange-100 text-orange-800 border-orange-300',
                        'paid' => 'bg-green-100 text-green-800 border-green-300',
                        'rejected' => 'bg-red-100 text-red-800 border-red-300',
                        default => 'bg-gray-100 text-gray-700 border-gray-300',
                    };
                @endphp
                <div
                    class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm active:scale-[0.99] transition-transform relative overflow-hidden">

                    {{-- Status Strip (Garis warna di kiri) --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $stripColor }}"></div>

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
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full border {{ $badgeColor }}">
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
                            <span>{{ $req->created_at->timezone('Asia/Jakarta')->format('d M H:i') }} WIB</span>
                        </div>

                        {{-- Footer Card --}}
                        <div class="flex justify-between items-end border-t border-gray-100 pt-3">
                            <div>
                                <span
                                    class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-0.5">Nominal</span>
                                <div class="flex flex-col">
                                    <span class="font-mono font-bold text-gray-800 text-sm">
                                        Rp {{ number_format($req->amount, 0, ',', '.') }}
                                    </span>

                                    {{-- Cek Selisih OCR (Mobile) --}}
                                    @php
                                        $ocrTotal = $req->details->sum('amount_ocr');
                                        $isMismatch = $ocrTotal > 0 && abs($ocrTotal - $req->amount) >= 0.01;
                                    @endphp

                                    @if ($isMismatch && in_array(auth()->user()->role, ['finance', 'manager', 'director']))
                                        <span
                                            class="text-[9px] text-red-700 bg-red-100 px-1.5 py-0.5 rounded border border-red-300 mt-0.5 inline-block w-max font-bold animate-pulse">
                                            ⚠️ Tidak sesuai dengan lampiran
                                        </span>
                                    @endif
                                </div>
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
