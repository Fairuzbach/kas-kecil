{{-- ROOT ELEMENT (Wajib 1 tag terluar untuk Livewire) --}}
<div>
    <div x-data="{
        showModal: false,
        modalUrl: '',
        modalType: '',
        openPreview(url, type) {
            this.modalUrl = url;
            this.modalType = type;
            this.showModal = true;
        }
    }" class="max-w-5xl mx-auto py-8 px-4 sm:px-6">

        {{-- LOGIKA AKSES EDIT --}}
        @php
            // Hanya bernilai true JIKA user login adalah pembuat tiket DAN tiket sedang direvisi
            $canEdit = auth()->id() === $request->user_id && $request->status->value === 'revision';
        @endphp

        {{-- KERTAS BUKTI PENGELUARAN KAS --}}
        <div class="w-full bg-white p-8 sm:p-12 shadow-2xl relative border border-gray-100 overflow-hidden">

            {{-- BANNER REVISI --}}
            @if ($request->status->value === 'revision')
                <div class="mb-6 p-4 bg-orange-50 border-l-4 border-orange-500 rounded-r-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-orange-500 mt-0.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-3 w-full">
                            <h3 class="text-sm font-bold text-orange-800 uppercase tracking-wide">
                                Pengajuan Dikembalikan (Butuh Revisi)
                            </h3>
                            <div class="mt-1 text-sm text-orange-700">
                                <p><strong>Catatan dari {{ $request->rejector->name ?? 'Pemeriksa' }}:</strong></p>
                                <p class="italic mt-1 bg-white/50 p-2 rounded border border-orange-200">
                                    "{{ $request->rejection_note }}"
                                </p>
                            </div>
                            @if ($canEdit)
                                <div class="mt-2 text-xs text-orange-600 font-medium">
                                    Silakan edit kolom "Keterangan" dan "Jumlah" di bawah ini, lalu klik tombol "Ajukan
                                    Ulang".
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Header Nota --}}
            <div class="flex flex-col sm:flex-row justify-between items-start mb-6">
                <div class="font-bold italic text-lg sm:text-xl tracking-wide uppercase">
                    PT. JEMBO CABLE COMPANY Tbk,
                </div>

                <table class="border-collapse border border-black text-sm mt-4 sm:mt-0">
                    <tr>
                        <td class="border border-black px-4 py-2 font-bold bg-gray-50 w-28">Tanggal</td>
                        <td class="border border-black px-3 py-2 w-56 font-medium">
                            {{ $request->created_at->format('d-m-Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4 py-2 font-bold bg-gray-50">Ref No.</td>
                        <td class="border border-black px-3 py-2 font-mono text-gray-700">
                            {{ $request->tracking_number }}
                        </td>
                    </tr>
                </table>
            </div>

            <h2
                class="text-center font-bold text-xl sm:text-3xl mt-10 mb-8 uppercase tracking-wider underline decoration-2 underline-offset-8">
                Bukti Pengeluaran Kas
            </h2>

            <div class="mb-6 text-base font-bold flex items-end gap-2 border-b-2 border-black border-dashed pb-1">
                <span class="whitespace-nowrap uppercase">DIBAYAR KEPADA :</span>
                <span class="flex-1 text-blue-800 italic text-xl px-2 uppercase">
                    {{ $request->title }}
                </span>
            </div>

            {{-- TABEL RINCIAN (Style Nota) --}}
            <div class="w-full">
                <table class="w-full border-collapse border-2 border-black text-sm mb-6">
                    <thead>
                        <tr class="bg-gray-100 font-bold text-center border-b-2 border-black">
                            <th class="border border-black px-3 py-3 w-12 text-center">NO.</th>
                            <th class="border border-black px-3 py-3 text-left uppercase">KETERANGAN</th>
                            <th class="border border-black px-3 py-3 w-48 uppercase">AKUN NO.</th>
                            <th class="border border-black px-3 py-3 w-24 uppercase">DEPT.</th>
                            <th class="border border-black px-3 py-3 w-64 uppercase">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($request->details as $index => $item)
                            <tr class="border-b border-gray-300">
                                <td class="border-x border-black px-3 py-3 text-center text-lg font-medium">
                                    {{ $index + 1 }}.
                                </td>

                                {{-- KOLOM KETERANGAN (Bisa jadi Input) --}}
                                <td class="uppercase border-x border-black px-3 py-3 text-lg font-medium">
                                    @if ($canEdit)
                                        <input type="text"
                                            wire:model.live.debounce.300ms="items.{{ $index }}.item_name"
                                            class="w-full uppercase text-lg font-medium border-b-2 border-orange-300 focus:border-orange-500 focus:ring-0 px-1 py-1 bg-orange-50 outline-none transition"
                                            placeholder="Keterangan barang...">
                                    @else
                                        {{ $item->item_name }}
                                    @endif
                                </td>

                                <td class="border-x border-black px-3 py-3 text-center font-mono font-bold relative">
                                    @if (in_array(auth()->user()->role, ['cashier', 'finance']) && !in_array($request->status->value, ['paid', 'rejected']))
                                        {{-- Alpine Dropdown COA --}}
                                        <div x-data="{
                                            search: '',
                                            open: false,
                                            options: [
                                                @foreach ($departmentCoas as $coa)
                                                    { id: {{ $coa->id }}, label: '{{ $coa->code }} - {{ addslashes($coa->name) }}' }, @endforeach
                                            ],
                                            get filteredOptions() {
                                                if (this.search === '') return this.options;
                                                return this.options.filter(i => i.label.toLowerCase().includes(this.search.toLowerCase()));
                                            },
                                            selectOption(id) {
                                                $wire.updateCoa({{ $item->id }}, id);
                                                this.open = false;
                                                this.search = '';
                                            }
                                        }" class="relative w-full text-left font-sans">
                                            <button @click="open = !open" type="button"
                                                class="w-full text-[11px] font-mono border-b-2 border-indigo-300 px-2 py-1.5 bg-indigo-50/50 text-indigo-800 hover:bg-indigo-100 focus:outline-none flex justify-between items-center transition cursor-pointer"
                                                title="Klik untuk ubah COA">
                                                <span class="truncate pr-2">
                                                    {{ $item->coa ? $item->coa->code . ' - ' . $item->coa->name : '- Pilih COA -' }}
                                                </span>
                                                <svg class="w-3 h-3 opacity-50 flex-shrink-0" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <div x-show="open" @click.away="open = false" style="display: none;"
                                                class="absolute z-50 w-64 mt-1 bg-white border border-gray-300 rounded shadow-xl left-1/2 transform -translate-x-1/2 text-left">
                                                <div class="p-2 border-b border-gray-200 bg-gray-50">
                                                    <input type="text" x-model="search"
                                                        placeholder="Ketik nama atau kode COA..."
                                                        class="w-full text-xs border-gray-300 rounded px-2 py-1.5 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                                                </div>
                                                <ul class="max-h-48 overflow-y-auto text-xs font-mono">
                                                    <template x-for="option in filteredOptions" :key="option.id">
                                                        <li @click="selectOption(option.id)"
                                                            class="px-3 py-2 cursor-pointer hover:bg-indigo-50 hover:text-indigo-700 border-b border-gray-100 last:border-0"
                                                            x-text="option.label"></li>
                                                    </template>
                                                    <li x-show="filteredOptions.length === 0"
                                                        class="px-3 py-3 text-gray-500 text-center italic">COA tidak
                                                        ditemukan</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div wire:loading wire:target="updateCoa({{ $item->id }})"
                                            class="text-[9px] text-indigo-600 mt-1 animate-pulse italic">
                                            Menyimpan...
                                        </div>
                                    @else
                                        <span
                                            class="text-[11px]">{{ $item->coa ? $item->coa->code . ' - ' . $item->coa->name : '-' }}</span>
                                    @endif
                                </td>

                                <td class="border-x border-black px-3 py-3 text-center font-bold text-gray-600">
                                    {{ $request->department->code ?? 'GA' }}
                                </td>

                                {{-- KOLOM JUMLAH (Bisa jadi Input) --}}
                                <td class="border-x border-black px-3 py-3">
                                    @if ($canEdit)
                                        <div class="flex items-center text-lg font-bold">
                                            <span>Rp.</span>
                                            <input type="number"
                                                wire:model.live.debounce.300ms="items.{{ $index }}.amount"
                                                class="w-full text-right text-lg font-bold border-b-2 border-orange-300 focus:border-orange-500 focus:ring-0 px-1 py-1 bg-orange-50 outline-none transition ml-1"
                                                placeholder="0">
                                        </div>
                                    @else
                                        <div class="flex justify-between items-center text-lg font-bold">
                                            <span>Rp.</span>
                                            <span class="{{ $item->amount < 0 ? 'text-red-600' : '' }}">
                                                {{ number_format($item->amount, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @for ($i = 0; $i < 2; $i++)
                            <tr class="h-8">
                                <td class="border-x border-black"></td>
                                <td class="border-x border-black"></td>
                                <td class="border-x border-black"></td>
                                <td class="border-x border-black"></td>
                                <td class="border-x border-black"></td>
                            </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100 border-t-2 border-black">
                            <td colspan="4"
                                class="border border-black px-4 py-3 text-right font-bold tracking-widest text-xl uppercase">
                                Total</td>
                            <td class="border border-black px-3 py-3 font-bold bg-gray-200 text-xl">
                                <div class="flex justify-between items-center w-full">
                                    <span>Rp.</span>
                                    {{-- Menggunakan variabel total dinamis jika sedang edit --}}
                                    <span>{{ number_format($canEdit ? collect($items)->sum('amount') : $request->amount, 0, ',', '.') }}.-</span>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mb-12 text-base flex gap-2 items-start">
                <span class="font-bold whitespace-nowrap pt-1 uppercase">Terbilang :</span>
                <div
                    class="flex-1 min-h-[2.5rem] border-b-2 border-black border-dashed px-3 py-1 text-blue-800 font-medium capitalize leading-relaxed text-lg italic">
                    {{ $request->terbilang ?? '-' }} Rupiah
                </div>
            </div>

            {{-- TOMBOL AJUKAN ULANG (Khusus Pemohon Saat Revisi) --}}
            @if ($canEdit)
                <div
                    class="mt-8 mb-4 p-5 bg-orange-50 rounded-xl border-2 border-dashed border-orange-300 print:hidden">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="text-orange-900 font-bold uppercase tracking-tight">Ajukan Ulang Pengajuan</h4>
                            <p class="text-xs text-orange-700">Pastikan Anda sudah memperbaiki nominal atau keterangan
                                sebelum menekan tombol ini.</p>
                        </div>
                        <button wire:click="resubmit" wire:loading.attr="disabled"
                            class="px-6 py-2.5 bg-orange-600 text-white font-bold rounded-lg hover:bg-orange-700 shadow-md transition active:scale-95 flex items-center gap-2">
                            <span wire:loading.remove wire:target="resubmit">Perbarui & Ajukan Ulang</span>
                            <span wire:loading wire:target="resubmit">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            @endif

            {{-- LOGIKA TOMBOL APPROVAL --}}
            @php
                $isApprover = false;
                $method = '';
                $user = auth()->user();

                // AMBIL DATA EMPLOYEE MILIK USER YANG SEDANG LOGIN
                $userEmployee = \App\Models\Employee::where('nik', $user->nik)->first();
                $userLevel = $userEmployee ? strtolower($userEmployee->level) : '';

                if ($request->status->value === 'pending_supervisor' && auth()->id() == $request->approver_id) {
                    $isApprover = true;
                    $method = 'approveSupervisor';
                } elseif (
                    $request->status->value === 'pending_manager' &&
                    auth()->id() == $request->department->manager_id
                ) {
                    $isApprover = true;
                    $method = 'approveManager';
                } elseif (
                    $request->status->value === 'pending_director' &&
                    auth()->user()->role === 'director' &&
                    auth()->user()->director_group === $request->department->director_group
                ) {
                    $isApprover = true;
                    $method = 'approveDirector';
                } elseif (
                    $request->status->value === 'pending_finance' &&
                    $user->role === 'finance' &&
                    $userLevel !== 'manager'
                ) {
                    // STAFF FINANCE
                    $isApprover = true;
                    $method = 'verifyCoa';
                } elseif (
                    $request->status->value === 'pending_finance_manager' &&
                    $user->role === 'finance' &&
                    $userLevel === 'manager'
                ) {
                    // FINANCE MANAGER
                    $isApprover = true;
                    $method = 'approveFinance';
                }
            @endphp

            @if ($isApprover)
                <div
                    class="mt-8 mb-4 p-5 bg-indigo-50 rounded-xl border-2 border-dashed border-indigo-200 print:hidden">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h4 class="text-indigo-900 font-bold uppercase tracking-tight">
                                {{ $request->status->value === 'pending_finance' ? 'Verifikasi COA' : 'Konfirmasi Persetujuan' }}
                            </h4>
                            <p class="text-xs text-indigo-600">
                                {{ $request->status->value === 'pending_finance' ? 'Silakan periksa dan sesuaikan pilihan COA sebelum diteruskan ke Manager Finance.' : 'Silakan periksa kembali rincian di atas sebelum menyetujui.' }}
                            </p>
                        </div>
                        <div class="flex gap-3 w-full sm:w-auto">
                            @if (in_array($request->status->value, ['pending_director', 'pending_finance', 'pending_finance_manager']))
                                <button type="button" wire:click="confirmRevision"
                                    class="flex-1 sm:flex-none px-6 py-2.5 bg-white border-2 border-orange-500 text-orange-600 font-bold rounded-lg hover:bg-orange-50 transition shadow-sm active:scale-95">
                                    Minta Revisi
                                </button>
                            @endif
                            <button wire:click="confirmReject"
                                class="flex-1 sm:flex-none px-6 py-2.5 bg-white border-2 border-red-500 text-red-600 font-bold rounded-lg hover:bg-red-50 transition shadow-sm active:scale-95">
                                Tolak / Kembalikan
                            </button>
                            <button wire:click="$set('showAcceptModal', true)" wire:loading.attr="disabled"
                                class="flex-1 sm:flex-none px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 shadow-md transition active:scale-95 flex items-center justify-center gap-2">
                                <span wire:loading.remove wire:target="{{ $method }}"
                                    class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    @if ($request->status->value === 'pending_finance')
                                        Verifikasi COA
                                    @elseif ($request->status->value === 'pending_finance_manager')
                                        Cairkan Dana
                                    @else
                                        Setujui
                                    @endif
                                </span>
                                <span wire:loading wire:target="{{ $method }}">Memproses...</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Kolom Tanda Tangan --}}
            @php
                // 1. DATA MANAGER
                $mgrUser = $request->department->manager;
                $mgrName = $mgrUser->name ?? 'Manager';
                $mgrEmployee = $mgrUser ? \App\Models\Employee::where('nik', $mgrUser->nik)->first() : null;
                $mgrTitle = $mgrEmployee->job_title ?? 'GAM';

                // 2. DATA DIRECTOR
                $actualDirector = $request->director_id ? \App\Models\User::find($request->director_id) : null;
                $dirGroup = $request->department->director_group ?? null;
                $dirUser = \App\Models\User::where('role', 'director')->where('director_group', $dirGroup)->first();

                $dirName = $actualDirector ? $actualDirector->name : $dirUser->name ?? 'Director';
                $dirEmployee = $dirUser ? \App\Models\Employee::where('nik', $dirUser->nik)->first() : null;
                $dirTitle = $dirEmployee->job_title ?? 'Director';

                // 3. DATA FINANCE MANAGER (Yang Membayar)
                $finUser = \App\Models\User::where('role', 'finance')
                    ->get()
                    ->filter(function ($u) {
                        $emp = \App\Models\Employee::where('nik', $u->nik)->first();
                        return $emp && strtolower($emp->level) === 'manager';
                    })
                    ->first();
                $finName = $finUser ? $finUser->name : 'Finance Manager';
                $finEmployee = $finUser ? \App\Models\Employee::where('nik', $finUser->nik)->first() : null;
                $finTitle = $finEmployee->job_title ?? 'Finance Manager';
                // 4. DATA FINANCE STAFF (Yang Verifikasi COA)
                // Mencari user finance yang levelnya BUKAN manager
                $finStaffUser = \App\Models\User::where('role', 'finance')
                    ->get()
                    ->filter(function ($u) {
                        $emp = \App\Models\Employee::where('nik', $u->nik)->first();
                        return $emp && strtolower($emp->level) !== 'manager';
                    })
                    ->first();
                $finStaffName = $finStaffUser ? $finStaffUser->name : 'Finance Staff';
                $finStaffEmployee = $finStaffUser
                    ? \App\Models\Employee::where('nik', $finStaffUser->nik)->first()
                    : null;
                $finStaffTitle = $finStaffEmployee->job_title ?? 'Finance Staff';
                // Logika: Tanda tangan Verifikasi COA akan muncul jika tiket sudah melewati tahap 'pending_finance'
                $isCoaVerified = in_array($request->status->value, ['pending_finance_manager', 'paid']);
            @endphp

            {{-- Ubah grid-cols-5 menjadi grid-cols-6 --}}
            <div class="grid grid-cols-6 gap-0 text-center text-xs font-bold mt-16 mb-8 uppercase">

                {{-- 1. DIREKTUR --}}
                <div class="flex flex-col justify-between h-32 border border-black p-1">
                    <p>Disetujui Oleh,</p>
                    <div class="mt-auto">
                        <p class="text-[10px] font-bold">{{ $request->director_approved_at ? $dirName : '' }}</p>
                        <div class="border-b border-black border-dashed mx-2 mb-1"></div>
                        <p class="text-[9px] font-normal">{{ $dirTitle }}</p>
                    </div>
                </div>

                {{-- 2. MANAGER --}}
                <div class="flex flex-col justify-between h-32 border border-black border-l-0 p-1">
                    <p>Diperiksa Oleh,</p>
                    <div class="mt-auto">
                        <p class="text-[10px] font-bold">{{ $request->manager_approved_at ? $mgrName : '' }}</p>
                        <div class="border-b border-black border-dashed mx-2 mb-1"></div>
                        <p class="text-[9px] font-normal">{{ $mgrTitle }}</p>
                    </div>
                </div>

                {{-- 3. VERIFIKASI COA (KOLOM BARU) --}}
                <div class="flex flex-col justify-between h-32 border border-black border-l-0 p-1 bg-indigo-50/30">
                    <p class="text-[10px]">Verifikasi COA,</p>
                    <div class="mt-auto">
                        <p class="text-[10px] font-bold">{{ $isCoaVerified ? $finStaffName : '' }}</p>
                        <div class="border-b border-black border-dashed mx-2 mb-1"></div>
                        <p class="text-[9px] font-normal">{{ $finStaffTitle }}</p>
                    </div>
                </div>

                {{-- 4. FINANCE MANAGER --}}
                <div class="flex flex-col justify-between h-32 border border-black border-l-0 p-1">
                    <p>Dibayar Oleh,</p>
                    <div class="mt-auto">
                        <p class="text-[10px] font-bold">{{ $request->status->value === 'paid' ? $finName : '' }}</p>
                        <div class="border-b border-black border-dashed mx-2 mb-1"></div>
                        <p class="text-[9px] font-normal">{{ $finTitle }}</p>
                    </div>
                </div>

                {{-- 5. PEMOHON --}}
                <div class="flex flex-col justify-between h-32 border border-black border-l-0 p-1">
                    <p>Diajukan Oleh,</p>
                    <div class="mt-auto">
                        <p class="text-[10px] font-bold">{{ $request->user->name }}</p>
                        <div class="border-b border-black border-dashed mx-2 mb-1"></div>
                        <p class="text-[9px] font-normal">Pemohon</p>
                    </div>
                </div>

                {{-- 6. PENERIMA --}}
                <div class="flex flex-col justify-between h-32 border border-black border-l-0 p-1">
                    <p>Diterima Oleh,</p>
                    <div class="mt-auto">
                        <p class="text-[10px] font-bold">
                            {{ $request->status->value === 'paid' ? $request->title : '' }}</p>
                        <div class="border-b border-black border-dashed mx-2 mb-1"></div>
                        <p class="text-[9px] font-normal">Penerima Dana</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notifikasi Rejection (Jika Ada) --}}
        @if ($request->status->value === 'rejected')
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mt-6 rounded shadow-sm">
                <h3 class="text-red-800 font-bold">⚠️ Alasan Penolakan:</h3>
                <p class="text-red-700 italic">"{{ $request->rejection_note }}"</p>
            </div>
        @endif

        {{-- BUKTI LAMPIRAN --}}
        @if ($request->attachment)
            <div class="mt-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2 uppercase tracking-widest">
                    📎 Bukti Lampiran
                </h3>
                <div class="max-w-xs group cursor-pointer"
                    @click="openPreview('{{ asset('storage/' . $request->attachment) }}', '{{ in_array(pathinfo($request->attachment, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp']) ? 'image' : 'pdf' }}')">
                    <div
                        class="bg-white p-2 shadow-lg border border-gray-200 rounded-sm transform transition hover:-rotate-1">
                        <div
                            class="relative overflow-hidden aspect-[4/3] bg-gray-100 flex items-center justify-center">
                            @if (in_array(pathinfo($request->attachment, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'webp']))
                                <img src="{{ asset('storage/' . $request->attachment) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="text-center p-4">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase">Klik untuk Lihat
                                        PDF</span>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition">
                                <span
                                    class="bg-white/90 text-black text-[10px] font-black px-3 py-1 rounded shadow-sm opacity-0 group-hover:opacity-100">PREVIEW</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- MODAL PREVIEW, REVISION & REJECT --}}
        <x-petty-cash.modal-preview />

        {{-- Modal Revisi (Pastikan Anda sudah meletakkan kode Modal Revisi di file komponen ini ya!) --}}
        <x-petty-cash.modal-revision :showRevisionModal="$showRevisionModal" />

        <x-petty-cash.modal-reject-and-accept :showRejectModal="$showRejectModal" :showAcceptModal="$showAcceptModal" :method="$method" :request="$request" />

    </div>
</div>
