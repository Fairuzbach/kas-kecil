<div x-data="{
    showModal: false,
    modalUrl: '',
    modalType: '',
    openPreview(url, type) {
        this.modalUrl = url;
        this.modalType = type;
        this.showModal = true;
    }
}">
    <div class="max-w-6xl mx-auto py-8">

        {{-- GRID UTAMA (Membagi Halaman Jadi 2 Kolom: Kiri 2/3, Kanan 1/3) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ==================================================== --}}
            {{-- KOLOM KIRI: HEADER, RINCIAN, & LAMPIRAN --}}
            {{-- ==================================================== --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- A. HEADER SECTION --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $request->title }}</h1>
                            <div class="text-sm text-gray-500 mt-2 flex flex-wrap gap-x-6 gap-y-2">
                                <span class="flex items-center gap-1">
                                    🏷️ <span
                                        class="font-mono font-bold text-gray-700 tracking-wide">{{ $request->tracking_number }}</span>
                                </span>
                                <span class="flex items-center gap-1">
                                    👤 <span class="font-semibold">{{ $request->user->name }}</span>
                                </span>
                                <span class="flex items-center gap-1">
                                    🏢 <span
                                        class="font-bold text-indigo-700">{{ $request->department->name ?? 'No Dept' }}</span>
                                </span>
                            </div>
                            <div class="mt-4 flex gap-2">
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    Jenis: {{ $request->type->label() }}
                                </span>
                            </div>
                        </div>

                        <div class="text-right">
                            <span
                                class="px-4 py-2 rounded-lg text-sm font-bold bg-gray-100 border border-gray-200 block md:inline-block text-center">
                                {{ $request->status->label() }}
                            </span>
                            <p class="text-xs text-gray-400 mt-2">Diajukan:
                                {{ $request->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                {{-- B. TABEL RINCIAN --}}
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                        📄 Rincian Pengajuan
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">Deskripsi Item</th>
                                    <th class="px-4 py-3">COA / Akun</th>
                                    <th class="px-4 py-3 text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($request->details as $item)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $item->item_name }}</td>
                                        <td class="px-4 py-3 text-gray-500">
                                            @if ($item->coa)
                                                <div class="flex flex-col">
                                                    <span
                                                        class="font-bold text-xs text-gray-700">{{ $item->coa->code }}</span>
                                                    <span
                                                        class="text-[10px] text-gray-400">{{ Str::limit($item->coa->name, 20) }}</span>
                                                </div>
                                            @else
                                                <span
                                                    class="text-xs text-gray-400 italic bg-gray-100 px-2 py-0.5 rounded">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono text-gray-700">
                                            Rp {{ number_format($item->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 font-bold text-gray-900">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-right">Total:</td>
                                    <td class="px-4 py-3 text-right text-indigo-600 text-base">
                                        Rp {{ number_format($request->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if ($request->description)
                        <div class="mt-4 bg-yellow-50 p-3 rounded border border-yellow-100 text-sm text-yellow-800">
                            <strong>Catatan:</strong> {{ $request->description }}
                        </div>
                    @endif
                </div>

                {{-- C. BUKTI LAMPIRAN --}}
                @if ($request->attachment || $request->extra_attachment)
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-6 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                            📎 Bukti Lampiran
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ([
        'attachment' => $request->type->value === 'pengobatan' ? '📄 Foto Kwitansi' : '📄 Lampiran Utama',
        'extra_attachment' => '💊 Foto Resep Dokter',
    ] as $field => $label)
                                @if ($request->$field)
                                    @php
                                        $file = $request->$field;
                                        $url = asset('storage/' . $file);
                                        $ext = pathinfo($file, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($ext), [
                                            'jpg',
                                            'jpeg',
                                            'png',
                                            'gif',
                                            'webp',
                                            'bmp',
                                        ]);
                                        $type = $isImage ? 'image' : 'pdf';
                                    @endphp

                                    <div class="border rounded-lg p-4 bg-gray-50 flex flex-col h-full">
                                        <p
                                            class="text-sm font-bold mb-3 {{ $field === 'extra_attachment' ? 'text-pink-700' : 'text-indigo-700' }}">
                                            {{ $label }}
                                        </p>

                                        <div class="flex-1 flex items-center justify-center bg-gray-200 rounded overflow-hidden relative group cursor-pointer"
                                            @click="openPreview('{{ $url }}', '{{ $type }}')">

                                            @if ($isImage)
                                                <img src="{{ $url }}"
                                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                                <div
                                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 flex items-center justify-center transition-all duration-300">
                                                    <span
                                                        class="opacity-0 group-hover:opacity-100 bg-white text-gray-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all">
                                                        🔍 Perbesar
                                                    </span>
                                                </div>
                                            @else
                                                <div
                                                    class="flex flex-col items-center justify-center h-48 w-full group-hover:bg-gray-300 transition text-gray-500">
                                                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                        </path>
                                                    </svg>
                                                    <span class="text-sm font-semibold uppercase">{{ $ext }}
                                                        FILE</span>
                                                    <span class="text-[10px] text-gray-400 mt-1">Klik untuk
                                                        preview</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-3 text-center">
                                            <button @click="openPreview('{{ $url }}', '{{ $type }}')"
                                                class="inline-flex items-center gap-1 px-4 py-2 bg-white border border-gray-300 rounded-md text-xs font-bold text-gray-700 hover:bg-gray-100 shadow-sm w-full justify-center transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Lihat Detail
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- ==================================================== --}}
            {{-- KOLOM KANAN: TIMELINE STATUS --}}
            {{-- ==================================================== --}}
            <div class="bg-white rounded-xl shadow-lg p-5 sticky top-4 h-fit">
                <h3 class="text-xl font-bold mb-6 text-gray-800">Status Persetujuan</h3>

                <div class="relative pl-2">
                    {{-- 1. CREATED --}}
                    <x-petty-cash.timeline-item title="Pengajuan Dibuat" status="done"
                        actor="{{ $request->user->name }}" date="{{ $request->created_at->format('d M H:i') }}" />

                    {{-- 2. SUPERVISOR --}}
                    @if ($request->type->value === 'pengobatan' && $request->user->supervisor_id)
                        @php
                            $spvState = $request->supervisor_approved_at
                                ? 'done'
                                : ($request->status->value === 'pending_supervisor'
                                    ? 'active'
                                    : 'wait');
                        @endphp
                        <x-petty-cash.timeline-item title="Supervisor" :status="$spvState"
                            actor="{{ $request->approver->name ?? 'Supervisor' }}" approveMethod="approveSupervisor"
                            rejectMethod="reject" />
                    @endif

                    {{-- 3. MANAGER --}}
                    @php
                        $mgrState = $request->manager_approved_at
                            ? 'done'
                            : ($request->status->value === 'pending_manager'
                                ? 'active'
                                : 'wait');
                        $canMgrApprove = $mgrState === 'active' && auth()->user()->role === 'manager';
                    @endphp
                    <x-petty-cash.timeline-item title="Manager Dept" :status="$mgrState" :approveMethod="$canMgrApprove ? 'approveManager' : null"
                        :rejectMethod="$canMgrApprove ? 'reject' : null" />

                    {{-- 4. FINANCE --}}
                    @php
                        $finState =
                            $request->status->value === 'paid'
                                ? 'done'
                                : ($request->status->value === 'pending_finance'
                                    ? 'active'
                                    : 'wait');
                    @endphp
                    <x-petty-cash.timeline-item title="Finance" :status="$finState" :isLast="true">
                        @if ($finState === 'active' && auth()->user()->role === 'finance')
                            <button wire:click="approveFinance" wire:confirm="Yakin ingin mencairkan dana?"
                                class="w-full mt-2 bg-emerald-500 text-white text-xs py-2 rounded hover:bg-emerald-600 transition shadow-sm font-bold flex items-center justify-center gap-2">
                                💰 Cairkan Dana
                            </button>
                        @endif
                    </x-petty-cash.timeline-item>
                </div>
            </div>

        </div> {{-- End Grid Utama --}}
    </div>

    {{-- PANGGIL MODAL PREVIEW --}}
    <x-petty-cash.modal-preview />
</div>
