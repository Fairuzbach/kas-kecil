<div>
    <style>
        /* ─── Keyframes ──────────────────────────────────────────── */

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInOverlay {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -400px 0;
            }

            100% {
                background-position: 400px 0;
            }
        }

        @keyframes badgePop {
            0% {
                transform: scale(0.75);
                opacity: 0;
            }

            70% {
                transform: scale(1.08);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes pulseGlow {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.35);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
            }
        }

        @keyframes flashSuccess {
            0% {
                opacity: 0;
                transform: translateY(-8px) scaleY(0.95);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scaleY(1);
            }
        }

        /* ─── Page-level containers ──────────────────────────────── */
        .audit-header {
            animation: fadeInDown 0.45s cubic-bezier(.22, .68, 0, 1.2) both;
        }

        .audit-table {
            animation: fadeInUp 0.50s cubic-bezier(.22, .68, 0, 1.2) 0.10s both;
        }

        .audit-flash {
            animation: flashSuccess 0.35s ease-out both;
        }

        /* ─── Table rows: staggered fade-up ─────────────────────── */
        .audit-row {
            opacity: 0;
            animation: fadeInUp 0.38s cubic-bezier(.22, .68, 0, 1.2) forwards;
        }

        .audit-row:nth-child(1) {
            animation-delay: 0.08s;
        }

        .audit-row:nth-child(2) {
            animation-delay: 0.14s;
        }

        .audit-row:nth-child(3) {
            animation-delay: 0.20s;
        }

        .audit-row:nth-child(4) {
            animation-delay: 0.26s;
        }

        .audit-row:nth-child(5) {
            animation-delay: 0.32s;
        }

        .audit-row:nth-child(6) {
            animation-delay: 0.38s;
        }

        .audit-row:nth-child(7) {
            animation-delay: 0.44s;
        }

        .audit-row:nth-child(8) {
            animation-delay: 0.50s;
        }

        .audit-row:nth-child(9) {
            animation-delay: 0.56s;
        }

        .audit-row:nth-child(10) {
            animation-delay: 0.62s;
        }

        /* rows 11+ appear without extra delay */
        .audit-row:nth-child(n+11) {
            animation-delay: 0.65s;
        }

        /* ─── Badge animations ───────────────────────────────────── */
        .badge-pop {
            animation: badgePop 0.4s cubic-bezier(.22, .68, 0, 1.2) 0.3s both;
        }

        .badge-glow {
            animation: pulseGlow 2s ease-in-out infinite;
        }

        /* ─── Buttons ────────────────────────────────────────────── */
        .btn-preview,
        .btn-sync {
            transition: transform 0.15s ease, box-shadow 0.15s ease,
                background-color 0.15s ease, color 0.15s ease;
            will-change: transform;
        }

        .btn-preview:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, .25);
        }

        .btn-preview:active {
            transform: scale(0.95);
        }

        .btn-sync:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(79, 70, 229, .35);
        }

        .btn-sync:active {
            transform: scale(0.95);
        }

        /* ─── Slide-over panel ───────────────────────────────────── */
        .slide-over-overlay {
            animation: fadeInOverlay 0.25s ease both;
        }

        .slide-over-panel {
            animation: slideInRight 0.35s cubic-bezier(.22, .68, 0, 1.2) both;
        }

        /* ─── Shimmer loading bar (top of table while Livewire loads) */
        .livewire-loading-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 100%;
            z-index: 9999;
            background: linear-gradient(90deg,
                    transparent 0%, #6366f1 40%, #818cf8 60%, transparent 100%);
            background-size: 400px 100%;
            animation: shimmer 1.2s linear infinite;
        }
    </style>

    {{-- Livewire loading bar --}}
    <div wire:loading class="livewire-loading-bar"></div>

    <div class="bg-gray-50 min-h-screen py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Flash Message --}}
            @if (session()->has('success'))
                <div
                    class="audit-flash mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">✅</span>
                        <p class="text-sm text-green-700 font-bold">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="text-green-500 hover:text-green-700 transition-colors"
                        onclick="this.parentElement.remove()">✕</button>
                </div>
            @endif

            {{-- Header & Filter --}}
            <div
                class="audit-header bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tight">Audit & Rekonsiliasi Mingguan
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Verifikasi kesesuaian nominal input dengan bukti lampiran
                        sistem OCR.</p>
                </div>

                {{-- Area Filter & Action Buttons --}}
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <input type="date" wire:model.live="startDate"
                            class="border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-shadow duration-150 focus:shadow-md">
                        <span class="text-gray-400">s/d</span>
                        <input type="date" wire:model.live="endDate"
                            class="border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 transition-shadow duration-150 focus:shadow-md">
                    </div>

                    {{-- Tombol Sync Semua OCR dengan Loading Animation --}}
                    <div class="pl-2 border-l border-gray-200">
                        <button wire:click="syncAllToOcr" wire:loading.attr="disabled" wire:target="syncAllToOcr"
                            onclick="confirm('Yakin ingin menyinkronkan SEMUA data yang berselisih OCR di periode ini?\n\n💡 INFO: Jangan khawatir, sistem akan OTOMATIS MELEWATI data yang terdeteksi memiliki potongan PPh 23 (selisih 2%) agar pajak tidak hilang.') || event.stopImmediatePropagation()"
                            class="btn-sync inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 border border-transparent text-white rounded-lg hover:bg-indigo-700 text-sm font-bold shadow-sm disabled:opacity-70 disabled:cursor-not-allowed transition-all duration-200">

                            {{-- Icon Default (Sembunyi saat loading) --}}
                            <svg wire:loading.remove wire:target="syncAllToOcr" class="w-4 h-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>

                            {{-- Icon Spinner (Muncul hanya saat loading) --}}
                            <svg wire:loading wire:target="syncAllToOcr" class="w-4 h-4 animate-spin text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            {{-- Teks berubah saat loading --}}
                            <span wire:loading.remove wire:target="syncAllToOcr">Sync Semua OCR</span>
                            <span wire:loading wire:target="syncAllToOcr">Menyinkronkan...</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Main Table --}}
            <div class="audit-table bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-indigo-50 text-indigo-900 border-b border-indigo-100">
                            <tr>
                                <th class="px-6 py-4 font-bold">REF NO. / TANGGAL</th>
                                <th class="px-6 py-4 font-bold">PEMOHON</th>
                                <th class="px-6 py-4 font-bold">DEPT</th>
                                <th class="px-6 py-4 font-bold text-right border-l border-indigo-100">TOTAL INPUT (A)
                                </th>
                                <th class="px-6 py-4 font-bold text-right">TOTAL OCR (B)</th>
                                <th class="px-6 py-4 font-bold text-center border-l border-indigo-100">STATUS AUDIT</th>
                                <th class="px-6 py-4 font-bold text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($requests as $req)
                                @php
                                    $firstItem = $req->details->first();
                                    $ocrTotal = (float) ($firstItem->amount_ocr ?? 0);
                                    $currentTotal = (float) $req->amount;
                                    $selisih = abs($ocrTotal - $currentTotal);
                                    $isMatched = $ocrTotal > 0 && $selisih < 0.01;
                                    $hasNoOcr = $ocrTotal <= 0;
                                    $persentaseSelisih = $ocrTotal > 0 ? ($selisih / $ocrTotal) * 100 : 0;
                                    $kemungkinanPph = $persentaseSelisih >= 1.8 && $persentaseSelisih <= 2.2;

                                    $attachmentUrl = $req->attachment ? asset('storage/' . $req->attachment) : null;
                                @endphp

                                <tr class="audit-row hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-mono font-bold text-gray-900">#{{ $req->tracking_number }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $req->created_at->format('d M Y, H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-700">
                                        {{ $req->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($req->department)
                                            <span
                                                class="text-[10px] font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200 uppercase tracking-wider">
                                                {{ $req->department->code }}
                                            </span>
                                        @else
                                            <span class="text-[10px] text-gray-400 italic">-</span>
                                        @endif
                                    </td>
                                    {{-- A. Total Input Manual --}}
                                    <td class="px-6 py-4 text-right font-bold text-gray-900 border-l border-gray-100">
                                        Rp {{ number_format($currentTotal, 0, ',', '.') }}
                                    </td>

                                    {{-- B. Total Hasil OCR --}}
                                    <td class="px-6 py-4 text-right border-r border-gray-100">
                                        @if ($hasNoOcr)
                                            <span class="text-xs text-gray-400 italic">Tidak Terbaca</span>
                                        @else
                                            <span
                                                class="font-bold {{ $isMatched ? 'text-green-600' : 'text-red-600' }}">
                                                Rp {{ number_format($ocrTotal, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Status Indicator --}}
                                    <td class="px-6 py-4 text-center">
                                        @if ($hasNoOcr)
                                            <span
                                                class="badge-pop inline-flex items-center gap-1 bg-gray-100 text-gray-600 px-2.5 py-1 rounded border border-gray-200 text-xs font-bold">
                                                ⚠️ Cek Manual
                                            </span>
                                        @elseif ($isMatched)
                                            <span
                                                class="badge-pop inline-flex items-center gap-1 bg-green-50 text-green-700 px-2.5 py-1 rounded border border-green-200 text-xs font-bold">
                                                ✅ SINKRON
                                            </span>
                                        @else
                                            @if ($kemungkinanPph)
                                                <span
                                                    class="badge-pop inline-flex items-center gap-1 bg-orange-50 text-orange-700 px-2.5 py-1 rounded border border-orange-300 text-xs font-bold"
                                                    title="Kemungkinan potongan PPh 23">
                                                    💡 INFO PPh 2%
                                                </span>
                                                <div class="text-[10px] text-orange-600 mt-1 font-mono font-bold">
                                                    Selisih: Rp {{ number_format($selisih, 0, ',', '.') }}
                                                </div>
                                            @else
                                                <span
                                                    class="badge-pop badge-glow inline-flex items-center gap-1 bg-red-50 text-red-700 px-2.5 py-1 rounded border border-red-200 text-xs font-bold animate-pulse">
                                                    ❌ SELISIH
                                                </span>
                                                <div class="text-[10px] text-red-500 mt-1 font-mono">
                                                    (- Rp {{ number_format($selisih, 0, ',', '.') }})
                                                </div>
                                            @endif
                                        @endif
                                    </td>

                                    {{-- Aksi: Preview & Sync --}}
                                    <td class="px-6 py-4 text-center space-x-2 flex justify-center items-center">
                                        @php $attachmentPath = $req->attachment; @endphp

                                        @if ($attachmentPath)
                                            <button
                                                wire:click="openPreview('{{ asset('storage/' . $attachmentPath) }}')"
                                                class="btn-preview inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white border border-blue-200 rounded-lg text-xs font-bold shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Preview Nota
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Tidak ada nota</span>
                                        @endif

                                        @if (!$isMatched && !$hasNoOcr)
                                            <button wire:click="syncToOcr({{ $req->id }})"
                                                onclick="confirm('Yakin ingin mengubah nominal input menjadi Rp {{ number_format($ocrTotal, 0, ',', '.') }}?{{ $kemungkinanPph ? '\n\n⚠️ PERHATIAN: Sistem mendeteksi selisih ini sebagai potongan PPh 23. Jika Anda melanjutkan Sinkronisasi, potongan pajak yang sudah diinput user akan tertimpa dan hilang!' : '' }}') || event.stopImmediatePropagation()"
                                                class="btn-sync inline-flex items-center gap-1 px-3 py-1.5 {{ $kemungkinanPph ? 'bg-orange-500 hover:bg-orange-600' : 'bg-indigo-600 hover:bg-indigo-700' }} border border-transparent text-white rounded-lg text-xs font-bold shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                Sync OCR
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        <span class="text-4xl mb-3 block">📭</span>
                                        Tidak ada data pengajuan di minggu ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Slide-over modal (deduplicated & animated) --}}
            @if ($showPreviewModal)
                <div class="slide-over-overlay fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title"
                    role="dialog" aria-modal="true">
                    <div class="absolute inset-0 overflow-hidden">

                        {{-- Backdrop --}}
                        <div class="absolute inset-0 bg-gray-900 bg-opacity-60 transition-opacity cursor-pointer"
                            wire:click="closePreview"></div>

                        {{-- Panel --}}
                        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                            <div
                                class="slide-over-panel pointer-events-auto w-screen max-w-md md:max-w-2xl shadow-2xl bg-white flex flex-col">

                                {{-- Header --}}
                                <div
                                    class="flex items-center justify-between px-4 py-4 md:px-6 bg-indigo-50 border-b border-indigo-100 shadow-sm z-10">
                                    <div>
                                        <h2 class="text-lg font-bold text-indigo-900" id="slide-over-title">Pratinjau
                                            Lampiran Bukti</h2>
                                        <p class="text-xs text-indigo-600 mt-0.5">Bandingkan nominal di sini dengan
                                            tabel di sebelah kiri.</p>
                                    </div>
                                    <button type="button" wire:click="closePreview"
                                        class="rounded-lg bg-white p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 focus:outline-none shadow-sm transition-colors duration-150">
                                        <span class="sr-only">Tutup panel</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Content --}}
                                <div
                                    class="relative flex-1 p-6 bg-gray-200 overflow-y-auto flex justify-center items-start">
                                    <div wire:loading wire:target="openPreview"
                                        class="absolute inset-0 flex items-center justify-center bg-gray-200 z-10">
                                        <span class="text-gray-500 font-bold animate-pulse">Memuat Lampiran...</span>
                                    </div>

                                    @if (Str::endsWith(strtolower($previewUrl), ['.pdf']))
                                        <iframe src="{{ $previewUrl }}"
                                            class="w-full h-[85vh] rounded-lg shadow-md border-0 bg-white"></iframe>
                                    @else
                                        <img src="{{ $previewUrl }}" alt="Lampiran Bukti"
                                            class="max-w-full h-auto rounded-lg shadow-lg border-4 border-white object-contain">
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
