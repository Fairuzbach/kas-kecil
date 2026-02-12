<div class="max-w-6xl mx-auto py-8">
    {{-- HEADER SECTION --}}
    <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6 border-l-4 border-indigo-500">
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
                        🏢 <span class="font-bold text-indigo-700">
                            {{ $request->department->name ?? 'No Dept' }}
                        </span>
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
                <p class="text-xs text-gray-400 mt-2">Diajukan: {{ $request->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">


        <div class="lg:col-span-2 space-y-6">


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
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $item->item_name }}
                                    </td>
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


            @if ($request->attachment || $request->extra_attachment)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-6 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                        📎 Bukti Lampiran (Kwitansi & Resep)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- 1. FOTO KWITANSI --}}
                        @if ($request->attachment)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <p class="text-sm font-bold mb-2 text-indigo-700">
                                    {{ $request->type->value === 'pengobatan' ? '📄 Foto Kwitansi' : '📄 Lampiran Utama' }}
                                </p>

                                <a href="{{ asset('storage/' . $request->attachment) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $request->attachment) }}" alt="Kwitansi"
                                        class="w-full h-48 object-cover rounded shadow-sm hover:opacity-80 transition border"
                                        onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=File+Bukan+Gambar';">
                                </a>

                                <div class="mt-2 text-center">
                                    <a href="{{ asset('storage/' . $request->attachment) }}" target="_blank"
                                        class="inline-block px-3 py-1 bg-white border border-gray-300 rounded text-xs font-bold text-gray-700 hover:bg-gray-100">
                                        🔍 Lihat Ukuran Penuh
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- 2. FOTO RESEP (Khusus Pengobatan) --}}
                        @if ($request->extra_attachment)
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <p class="text-sm font-bold mb-2 text-pink-700">💊 Foto Resep Dokter</p>

                                {{-- DEBUGGER --}}
                                {{-- <p class="text-xs text-gray-400 mb-2 break-all">File: {{ $request->extra_attachment }}
                                </p> --}}

                                <a href="{{ asset('storage/' . $request->extra_attachment) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $request->extra_attachment) }}" alt="Resep"
                                        class="w-full h-48 object-cover rounded shadow-sm hover:opacity-80 transition border"
                                        onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=File+Bukan+Gambar';">
                                </a>

                                <div class="mt-2 text-center">
                                    <a href="{{ asset('storage/' . $request->extra_attachment) }}" target="_blank"
                                        class="inline-block px-3 py-1 bg-white border border-gray-300 rounded text-xs font-bold text-gray-700 hover:bg-gray-100">
                                        🔍 Lihat Ukuran Penuh
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- KOLOM KANAN (TIMELINE) - LIVEWIRE COMPATIBLE VERSION --}}
        <div class="bg-white rounded-xl shadow-lg p-5 md:p-6 sticky top-4">
            {{-- Header --}}
            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-800 mb-1 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Status Persetujuan
                </h3>
                <p class="text-xs text-gray-500">Tracking progress real-time</p>
            </div>

            {{-- Timeline Container --}}
            <div class="relative">
                {{-- Vertical Line --}}
                <div
                    class="absolute left-4 top-0 bottom-0 w-0.5 bg-gradient-to-b from-gray-300 via-gray-200 to-gray-300">
                </div>

                {{-- 1. CREATED --}}
                <div class="relative flex items-start mb-6 group">
                    <div
                        class="relative z-10 flex items-center justify-center w-8 h-8 bg-green-500 rounded-full shadow-md ring-4 ring-green-50 flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>

                    <div
                        class="ml-4 flex-1 bg-green-50 rounded-lg p-3 border border-green-200 hover:shadow-sm transition-all duration-200">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="text-sm font-semibold text-gray-800">Pengajuan Dibuat</h4>
                            <span class="px-2 py-0.5 bg-green-500 text-white text-[10px] font-medium rounded-full">✓
                                Selesai</span>
                        </div>
                        <div class="flex flex-col gap-1 text-xs text-gray-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ $request->user->name }}
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $request->created_at->format('d M H:i') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- 2. SUPERVISOR (Khusus Pengobatan) --}}
                @if ($request->type->value === 'pengobatan' && $request->user->supervisor_id)
                    @php
                        $spvStatus = $request->supervisor_approved_at
                            ? 'done'
                            : ($request->status->value === 'pending_supervisor'
                                ? 'active'
                                : 'wait');
                        $config = [
                            'done' => [
                                'bg' => 'bg-green-50 border-green-200',
                                'icon' => 'bg-green-500',
                                'ring' => 'ring-green-50',
                                'badge' => 'bg-green-500 text-white',
                                'label' => '✓ Disetujui',
                            ],
                            'active' => [
                                'bg' => 'bg-orange-50 border-orange-200',
                                'icon' => 'bg-orange-500',
                                'ring' => 'ring-orange-50',
                                'badge' => 'bg-orange-500 text-white',
                                'label' => '⏳ Menunggu',
                            ],
                            'wait' => [
                                'bg' => 'bg-gray-50 border-gray-200',
                                'icon' => 'bg-gray-300',
                                'ring' => 'ring-gray-50',
                                'badge' => 'bg-gray-300 text-gray-600',
                                'label' => '⚪ Pending',
                            ],
                        ][$spvStatus];
                    @endphp

                    <div class="relative flex items-start mb-6 group">
                        <div
                            class="relative z-10 flex items-center justify-center w-8 h-8 {{ $config['icon'] }} rounded-full shadow-md ring-4 {{ $config['ring'] }} flex-shrink-0 transition-all duration-300">
                            @if ($spvStatus === 'done')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif($spvStatus === 'active')
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            @else
                                <div class="w-2 h-2 bg-white rounded-full opacity-50"></div>
                            @endif
                        </div>

                        <div
                            class="ml-4 flex-1 {{ $config['bg'] }} rounded-lg p-3 border hover:shadow-sm transition-all duration-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-semibold text-gray-800">Supervisor</h4>
                                <span
                                    class="px-2 py-0.5 {{ $config['badge'] }} text-[10px] font-medium rounded-full">{{ $config['label'] }}</span>
                            </div>

                            @if (auth()->id() === $request->approver_id)
                                <div class="flex gap-2">
                                    <button wire:click="reject" wire:confirm="Yakin ingin menolak pengajuan ini?"
                                        class="flex-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors duration-200 flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Tolak
                                    </button>


                                    <button wire:click="approveSupervisor"
                                        wire:confirm="Yakin menyetujui pengajuan ini?"
                                        class="flex-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded transition-colors duration-200 flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setuju
                                    </button>
                                </div>
                            @elseif($spvStatus === 'done')
                                <p class="text-xs text-gray-600">
                                    ✅ Disetujui oleh {{ $request->approver->name ?? 'Supervisor' }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- 3. MANAGER --}}
                @php
                    $mgrStatus = $request->manager_approved_at
                        ? 'done'
                        : ($request->status->value === 'pending_manager'
                            ? 'active'
                            : 'wait');
                    $config = [
                        'done' => [
                            'bg' => 'bg-green-50 border-green-200',
                            'icon' => 'bg-green-500',
                            'ring' => 'ring-green-50',
                            'badge' => 'bg-green-500 text-white',
                            'label' => '✓ Disetujui',
                        ],
                        'active' => [
                            'bg' => 'bg-yellow-50 border-yellow-200',
                            'icon' => 'bg-yellow-500',
                            'ring' => 'ring-yellow-50',
                            'badge' => 'bg-yellow-500 text-white',
                            'label' => '⏳ Menunggu',
                        ],
                        'wait' => [
                            'bg' => 'bg-gray-50 border-gray-200',
                            'icon' => 'bg-gray-300',
                            'ring' => 'ring-gray-50',
                            'badge' => 'bg-gray-300 text-gray-600',
                            'label' => '⚪ Pending',
                        ],
                    ][$mgrStatus];
                @endphp

                <div class="relative flex items-start mb-6 group">
                    <div
                        class="relative z-10 flex items-center justify-center w-8 h-8 {{ $config['icon'] }} rounded-full shadow-md ring-4 {{ $config['ring'] }} flex-shrink-0 transition-all duration-300">
                        @if ($mgrStatus === 'done')
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                        @elseif($mgrStatus === 'active')
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                        @else
                            <div class="w-2 h-2 bg-white rounded-full opacity-50"></div>
                        @endif
                    </div>

                    <div
                        class="ml-4 flex-1 {{ $config['bg'] }} rounded-lg p-3 border hover:shadow-sm transition-all duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-semibold text-gray-800">Approval Manager Dept</h4>
                            <span
                                class="px-2 py-0.5 {{ $config['badge'] }} text-[10px] font-medium rounded-full">{{ $config['label'] }}</span>
                        </div>

                        @if (
                            $mgrStatus === 'active' &&
                                auth()->user()->role === 'manager' &&
                                auth()->user()->department_id === $request->department_id)
                            <div class="flex gap-2">
                                <button wire:click="reject" wire:confirm="Yakin ingin menolak pengajuan ini?"
                                    class="flex-1 px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors duration-200 flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Tolak
                                </button>
                                <button wire:click="approveManager" wire:confirm="Setujui pengajuan ini?"
                                    class="flex-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-medium rounded transition-colors duration-200 flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Setuju
                                </button>
                            </div>
                        @elseif($mgrStatus === 'done')
                            <p class="text-xs text-gray-600">Telah disetujui Manager</p>
                        @endif
                    </div>
                </div>

                {{-- 4. KLINIK (Khusus Pengobatan) --}}
                @if ($request->type->value === 'pengobatan')
                    @php
                        $isKlinikDone = in_array($request->status->value, ['pending_hc', 'pending_finance', 'paid']);
                        $isKlinikActive = $request->status->value === 'pending_klinik';
                        $klinikStatus = $isKlinikDone ? 'done' : ($isKlinikActive ? 'active' : 'wait');
                        $config = [
                            'done' => [
                                'bg' => 'bg-green-50 border-green-200',
                                'icon' => 'bg-green-500',
                                'ring' => 'ring-green-50',
                                'badge' => 'bg-green-500 text-white',
                                'label' => '✓ Terverifikasi',
                            ],
                            'active' => [
                                'bg' => 'bg-teal-50 border-teal-200',
                                'icon' => 'bg-teal-500',
                                'ring' => 'ring-teal-50',
                                'badge' => 'bg-teal-500 text-white',
                                'label' => '🏥 Verifikasi',
                            ],
                            'wait' => [
                                'bg' => 'bg-gray-50 border-gray-200',
                                'icon' => 'bg-gray-300',
                                'ring' => 'ring-gray-50',
                                'badge' => 'bg-gray-300 text-gray-600',
                                'label' => '⚪ Pending',
                            ],
                        ][$klinikStatus];
                    @endphp

                    <div class="relative flex items-start mb-6 group">
                        <div
                            class="relative z-10 flex items-center justify-center w-8 h-8 {{ $config['icon'] }} rounded-full shadow-md ring-4 {{ $config['ring'] }} flex-shrink-0 transition-all duration-300">
                            @if ($isKlinikDone)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif($isKlinikActive)
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            @else
                                <div class="w-2 h-2 bg-white rounded-full opacity-50"></div>
                            @endif
                        </div>

                        <div
                            class="ml-4 flex-1 {{ $config['bg'] }} rounded-lg p-3 border hover:shadow-sm transition-all duration-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-semibold text-gray-800">Verifikasi Klinik</h4>
                                <span
                                    class="px-2 py-0.5 {{ $config['badge'] }} text-[10px] font-medium rounded-full">{{ $config['label'] }}</span>
                            </div>

                            @if ($isKlinikActive && auth()->user()->role === 'klinik')
                                <button
                                    class="w-full px-3 py-1.5 bg-teal-500 hover:bg-teal-600 text-white text-xs font-medium rounded transition-colors duration-200 flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Validasi Medis
                                </button>
                            @elseif($isKlinikDone)
                                <p class="text-xs text-gray-600">Dokumen medis terverifikasi</p>
                            @endif
                        </div>
                    </div>

                    {{-- 5. HUMAN CAPITAL --}}
                    @php
                        $isHCDone = in_array($request->status->value, ['pending_finance', 'paid']);
                        $isHCActive = $request->status->value === 'pending_hc';
                        $hcStatus = $isHCDone ? 'done' : ($isHCActive ? 'active' : 'wait');
                        $config = [
                            'done' => [
                                'bg' => 'bg-green-50 border-green-200',
                                'icon' => 'bg-green-500',
                                'ring' => 'ring-green-50',
                                'badge' => 'bg-green-500 text-white',
                                'label' => '✓ Terverifikasi',
                            ],
                            'active' => [
                                'bg' => 'bg-pink-50 border-pink-200',
                                'icon' => 'bg-pink-500',
                                'ring' => 'ring-pink-50',
                                'badge' => 'bg-pink-500 text-white',
                                'label' => '👤 Verifikasi',
                            ],
                            'wait' => [
                                'bg' => 'bg-gray-50 border-gray-200',
                                'icon' => 'bg-gray-300',
                                'ring' => 'ring-gray-50',
                                'badge' => 'bg-gray-300 text-gray-600',
                                'label' => '⚪ Pending',
                            ],
                        ][$hcStatus];
                    @endphp

                    <div class="relative flex items-start mb-6 group">
                        <div
                            class="relative z-10 flex items-center justify-center w-8 h-8 {{ $config['icon'] }} rounded-full shadow-md ring-4 {{ $config['ring'] }} flex-shrink-0 transition-all duration-300">
                            @if ($isHCDone)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif($isHCActive)
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            @else
                                <div class="w-2 h-2 bg-white rounded-full opacity-50"></div>
                            @endif
                        </div>

                        <div
                            class="ml-4 flex-1 {{ $config['bg'] }} rounded-lg p-3 border hover:shadow-sm transition-all duration-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-semibold text-gray-800">Verifikasi Human Capital</h4>
                                <span
                                    class="px-2 py-0.5 {{ $config['badge'] }} text-[10px] font-medium rounded-full">{{ $config['label'] }}</span>
                            </div>

                            @if ($isHCActive && auth()->user()->role === 'hc')
                                <button
                                    class="w-full px-3 py-1.5 bg-pink-500 hover:bg-pink-600 text-white text-xs font-medium rounded transition-colors duration-200 flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Validasi Plafon
                                </button>
                            @elseif($isHCDone)
                                <p class="text-xs text-gray-600">Plafon terverifikasi HC</p>
                            @endif
                        </div>
                    </div>
                @endif
                @if ($request->type->value !== 'pengobatan')
                    @php
                        $dirStatus = 'wait';

                        if ($request->director_approved_at) {
                            $dirStatus = 'done';
                        } elseif ($request->status->value === 'rejected') {
                            $dirStatus = 'rejected';
                        } elseif ($request->status->value === 'pending_director') {
                            $dirStatus = 'active';
                        }

                        // 2. CONFIG STYLE
                        $config = [
                            'done' => [
                                'bg' => 'bg-green-50 border-green-200',
                                'icon' => 'bg-green-500',
                                'ring' => 'ring-green-50',
                                'badge' => 'bg-green-500 text-white',
                                'label' => '✓ Disetujui',
                            ],
                            'active' => [
                                'bg' => 'bg-blue-50 border-blue-200',
                                'icon' => 'bg-blue-500',
                                'ring' => 'ring-blue-50',
                                'badge' => 'bg-blue-500 text-white',
                                'label' => '👔 Menunggu Direktur',
                            ],
                            'rejected' => [
                                'bg' => 'bg-red-50 border-red-200',
                                'icon' => 'bg-red-500',
                                'ring' => 'ring-red-50',
                                'badge' => 'bg-red-500 text-white',
                                'label' => '✕ Ditolak',
                            ],
                            'wait' => [
                                'bg' => 'bg-gray-50 border-gray-200',
                                'icon' => 'bg-gray-300',
                                'ring' => 'ring-gray-50',
                                'badge' => 'bg-gray-300 text-gray-600',
                                'label' => '⚪ Pending',
                            ],
                        ][$dirStatus];
                    @endphp

                    <div class="relative flex items-start mb-6 group">
                        {{-- ICON --}}
                        <div
                            class="relative z-10 flex items-center justify-center w-8 h-8 {{ $config['icon'] }} rounded-full shadow-md ring-4 {{ $config['ring'] }} flex-shrink-0 transition-all duration-300">
                            @if ($dirStatus === 'done')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif($dirStatus === 'rejected')
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            @elseif($dirStatus === 'active')
                                <div class="w-2 h-2 bg-white rounded-full animate-ping"></div>
                            @else
                                <div class="w-2 h-2 bg-white rounded-full opacity-50"></div>
                            @endif
                        </div>

                        {{-- CARD CONTENT --}}
                        <div
                            class="ml-4 flex-1 {{ $config['bg'] }} rounded-lg p-3 border hover:shadow-sm transition-all duration-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-semibold text-gray-800">Approval Direktur</h4>
                                <span
                                    class="px-2 py-0.5 {{ $config['badge'] }} text-[10px] font-medium rounded-full">{{ $config['label'] }}</span>
                            </div>

                            {{-- TOMBOL ACTION --}}
                            @if ($dirStatus === 'active' && auth()->user()->role === 'director')
                                <div class="flex gap-2">
                                    {{-- TOMBOL TOLAK --}}
                                    <button wire:click="reject" wire:confirm="Yakin ingin menolak pengajuan ini?"
                                        class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition-colors duration-200 flex items-center justify-center gap-1">
                                        Tolak
                                    </button>

                                    {{-- TOMBOL SETUJU --}}
                                    <button wire:click="approveDirector" wire:confirm="Setujui pengajuan ini?"
                                        class="flex-1 px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded transition-colors duration-200 flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Approve
                                    </button>
                                </div>
                            @elseif($dirStatus === 'done')
                                <p class="text-xs text-gray-600">Disetujui pada:
                                    {{ $request->director_approved_at->format('d M Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- 7. FINANCE --}}
                @php
                    $isPaid = $request->status->value === 'paid';
                    $isFinActive = $request->status->value === 'pending_finance';
                    $finStatus = $isPaid ? 'done' : ($isFinActive ? 'active' : 'wait');
                    $config = [
                        'done' => [
                            'bg' => 'bg-green-50 border-green-200',
                            'icon' => 'bg-green-500',
                            'ring' => 'ring-green-50',
                            'badge' => 'bg-green-500 text-white',
                            'label' => '💰 Lunas',
                        ],
                        'active' => [
                            'bg' => 'bg-emerald-50 border-emerald-200',
                            'icon' => 'bg-emerald-500',
                            'ring' => 'ring-emerald-50',
                            'badge' => 'bg-emerald-500 text-white',
                            'label' => '⏳ Pencairan',
                        ],
                        'wait' => [
                            'bg' => 'bg-gray-50 border-gray-200',
                            'icon' => 'bg-gray-300',
                            'ring' => 'ring-gray-50',
                            'badge' => 'bg-gray-300 text-gray-600',
                            'label' => '⚪ Pending',
                        ],
                    ][$finStatus];
                @endphp

                <div class="relative flex items-start">
                    <div
                        class="relative z-10 flex items-center justify-center w-8 h-8 {{ $config['icon'] }} rounded-full shadow-md ring-4 {{ $config['ring'] }} flex-shrink-0 transition-all duration-300">
                        @if ($isPaid)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        @elseif($isFinActive)
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                        @else
                            <div class="w-2 h-2 bg-white rounded-full opacity-50"></div>
                        @endif
                    </div>

                    <div
                        class="ml-4 flex-1 {{ $config['bg'] }} rounded-lg p-3 border hover:shadow-sm transition-all duration-200">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-semibold text-gray-800">Approve Finance & Payment</h4>
                            <span
                                class="px-2 py-0.5 {{ $config['badge'] }} text-[10px] font-medium rounded-full">{{ $config['label'] }}</span>
                        </div>

                        @if ($isFinActive && auth()->user()->role === 'finance')
                            <button
                                class="w-full px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-medium rounded transition-colors duration-200 flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                Cairkan Dana
                            </button>
                        @elseif($isPaid)
                            <div class="p-2 bg-green-100 rounded border border-green-300">
                                <div class="flex items-center gap-2 text-green-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold">PEMBAYARAN SELESAI</p>
                                        <p class="text-[10px]">{{ $request->updated_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Optional: Add custom animations --}}
        <style>
            @keyframes pulse-ring {

                0%,
                100% {
                    transform: scale(1);
                    opacity: 1;
                }

                50% {
                    transform: scale(1.05);
                    opacity: 0.8;
                }
            }

            /* Ping Animation for Dot Indicator */
            @keyframes ping {

                75%,
                100% {
                    transform: scale(2);
                    opacity: 0;
                }
            }

            /* Apply animations (already using Tailwind's animate-pulse and can be extended) */
            .status-timeline .animate-pulse {
                animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            .status-timeline .animate-ping {
                animation: ping 1s cubic-bezier(0, 0, 0.2, 1) infinite;
            }

            /* Smooth transitions for all timeline elements */
            .status-timeline .timeline-item {
                transition: all 0.3s ease-in-out;
            }

            .status-timeline .timeline-item:hover {
                transform: translateX(2px);
            }

            /* Progress bar animation */
            .progress-bar-gradient {
                background: linear-gradient(90deg, #6366f1 0%, #a855f7 50%, #10b981 100%);
                transition: width 0.7s cubic-bezier(0.4, 0, 0.2, 1);
            }
        </style>
    </div>
</div>
