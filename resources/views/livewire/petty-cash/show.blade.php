<div class="max-w-5xl mx-auto py-8">
    <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6 border-l-4 border-indigo-500">
        <div class="flex flex-col md:flex-row justify-between items-start gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $request->title }}</h1>

                <p class="text-sm text-gray-500 mt-2 flex flex-wrap gap-x-4 gap-y-1">
                    <span class="flex items-center gap-1">
                        🏷️ <span class="font-mono font-bold text-gray-700">{{ $request->tracking_number }}</span>
                    </span>

                    <span class="flex items-center gap-1">
                        👤 <span class="font-semibold">{{ $request->user->name }}</span>
                    </span>

                    <span class="flex items-center gap-1">
                        🏢 <span class="font-bold text-indigo-700">
                            {{ $request->department->code ?? '-' }} - {{ $request->department->name ?? 'No Dept' }}
                        </span>
                    </span>
                </p>

                <div class="mt-3 text-sm text-gray-600 bg-gray-50 inline-block px-3 py-1 rounded border">
                    Jenis Pengajuan: <span class="font-bold text-gray-800">{{ $request->type->label() }}</span>
                </div>
            </div>

            <div class="text-right">
                <span
                    class="px-4 py-2 rounded-lg text-sm font-bold bg-gray-100 border border-gray-200 block md:inline-block text-center">
                    {{ $request->status->label() }}
                </span>
                <p class="text-xs text-gray-400 mt-2">Dibuat: {{ $request->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Rincian Pengajuan</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3">Nama Barang / Jasa</th>
                                <th class="px-4 py-3">COA (Akun)</th>
                                <th class="px-4 py-3 text-right">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($request->details as $item)
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $item->item_name }}</td>
                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $item->coa->code }} <span
                                            class="text-xs text-gray-400">({{ $item->coa->name }})</span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-mono text-gray-700">
                                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 font-bold text-gray-900">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right">Total Pengajuan:</td>
                                <td class="px-4 py-3 text-right text-indigo-600 text-base">
                                    Rp {{ number_format($request->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if ($request->description)
                    <div class="mt-6 bg-yellow-50 p-4 rounded-md border border-yellow-100">
                        <h4 class="text-xs font-bold text-yellow-800 uppercase mb-1">Keterangan Tambahan:</h4>
                        <p class="text-sm text-yellow-700">{{ $request->description }}</p>
                    </div>
                @endif
            </div>

            @if ($request->attachment)
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Bukti Lampiran</h3>
                    <div class="border rounded-lg p-2 bg-gray-50 inline-block">
                        <img src="{{ asset('storage/' . $request->attachment) }}" alt="Lampiran"
                            class="max-h-96 rounded shadow-sm">
                        <div class="mt-2 text-xs text-blue-600">
                            <a href="{{ asset('storage/' . $request->attachment) }}" target="_blank"
                                class="hover:underline">Buka Gambar Asli ↗</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Status Approval</h3>

                <ol class="relative border-l border-gray-200 ml-3">

                    <li class="mb-10 ml-6">
                        <span
                            class="absolute flex items-center justify-center w-6 h-6 bg-green-100 rounded-full -left-3 ring-8 ring-white">
                            ✅
                        </span>
                        <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-900">
                            @if ($request->type->value === 'invoice')
                                Invoice Masuk
                            @elseif($request->type->value === 'pengobatan')
                                Pengajuan Pengobatan
                            @else
                                Reimburse Diajukan
                            @endif
                        </h3>
                        <time class="block mb-2 text-xs font-normal leading-none text-gray-400">
                            {{ $request->created_at->format('d M Y, H:i') }}
                        </time>
                        <p class="text-xs text-gray-500">
                            Oleh: {{ $request->user->name }}
                        </p>
                    </li>

                    <li class="mb-10 ml-6">
                        <span
                            class="absolute flex items-center justify-center w-6 h-6 {{ $request->manager_approved_at ? 'bg-green-100' : 'bg-gray-100' }} rounded-full -left-3 ring-8 ring-white">
                            {{ $request->manager_approved_at ? '✅' : '⏳' }}
                        </span>
                        <h3
                            class="mb-1 text-sm font-semibold {{ $request->manager_approved_at ? 'text-gray-900' : 'text-gray-500' }}">
                            Approval Manager Dept
                        </h3>
                        @if ($request->manager_approved_at)
                            <time class="block mb-2 text-xs font-normal leading-none text-gray-400">
                                {{ $request->manager_approved_at->format('d M Y, H:i') }}
                            </time>
                            <p class="text-xs text-green-600 font-medium">Disetujui</p>
                        @else
                            <p class="text-xs text-gray-400 italic">Menunggu Manager...</p>
                        @endif
                    </li>

                    @if ($request->type->value !== 'pengobatan')
                        <li class="mb-10 ml-6">
                            @php
                                $directorStatus = $request->director_approved_at
                                    ? 'bg-green-100'
                                    : ($request->manager_approved_at
                                        ? 'bg-yellow-100'
                                        : 'bg-gray-100');
                                $directorIcon = $request->director_approved_at ? '✅' : '⏳';
                            @endphp

                            <span
                                class="absolute flex items-center justify-center w-6 h-6 {{ $directorStatus }} rounded-full -left-3 ring-8 ring-white">
                                {{ $directorIcon }}
                            </span>
                            <h3
                                class="mb-1 text-sm font-semibold {{ $request->director_approved_at ? 'text-gray-900' : 'text-gray-500' }}">
                                Approval Direktur
                            </h3>
                            <p class="text-xs text-gray-400 mb-1">(Wajib untuk {{ $request->type->label() }})</p>

                            @if ($request->director_approved_at)
                                <time class="block mb-2 text-xs font-normal leading-none text-gray-400">
                                    {{ $request->director_approved_at->format('d M Y, H:i') }}
                                </time>
                                <p class="text-xs text-green-600 font-medium">Disetujui</p>
                            @else
                                @if ($request->manager_approved_at)
                                    <p class="text-xs text-orange-500 font-medium animate-pulse">Menunggu Direktur...
                                    </p>
                                @else
                                    <p class="text-xs text-gray-300 italic">Menunggu antrian...</p>
                                @endif
                            @endif
                        </li>
                    @endif

                    <li class="ml-6">
                        @php
                            // Logic warna: Hijau jika paid, Abu jika belum
                            $financeStatus = $request->status->value === 'paid' ? 'bg-green-100' : 'bg-gray-100';
                            $financeIcon = $request->status->value === 'paid' ? '💰' : '⏳';
                        @endphp

                        <span
                            class="absolute flex items-center justify-center w-6 h-6 {{ $financeStatus }} rounded-full -left-3 ring-8 ring-white">
                            {{ $financeIcon }}
                        </span>

                        <h3
                            class="mb-1 text-sm font-semibold {{ $request->status->value === 'paid' ? 'text-gray-900' : 'text-gray-500' }}">
                            Finance & Payment
                        </h3>

                        @if ($request->status->value === 'paid')
                            <time class="block mb-2 text-xs font-normal leading-none text-gray-400">
                                {{ $request->updated_at->format('d M Y') }}
                            </time>
                            <p class="text-xs text-green-600 font-bold mt-1">
                                @if ($request->type->value === 'invoice')
                                    Lunas ke Vendor
                                @elseif($request->type->value === 'reimburse')
                                    Uang Diterima Requester (Transfer)
                                @else
                                    Klaim Medis Cair
                                @endif
                            </p>
                        @else
                            <p class="text-xs text-gray-400 italic">
                                @if ($request->type->value === 'pengobatan')
                                    Menunggu Verifikasi Plafon & Transfer
                                @else
                                    Menunggu Pencairan Dana
                                @endif
                            </p>
                        @endif
                    </li>
                    @if ($request->status->value === 'pending_manager')
                        {{-- Tombol ini hanya untuk user Role Manager --}}
                        @if (Auth::user()->role === 'manager')
                            <div class="mt-8 pt-6 border-t bg-yellow-50 p-4 rounded-lg border-yellow-100">
                                <h4 class="text-sm font-bold text-yellow-800 mb-3">👮 Action Manager:</h4>

                                <div class="flex gap-2">
                                    <button wire:click="reject" wire:confirm="Tolak pengajuan ini?"
                                        class="px-4 py-2 bg-white text-red-600 border border-red-300 rounded font-bold text-sm hover:bg-red-50">
                                        Tolak
                                    </button>

                                    <button wire:click="approveManager" wire:confirm="Setujui sebagai Manager?"
                                        class="flex-1 bg-yellow-600 text-white py-2 rounded font-bold text-sm hover:bg-yellow-700 shadow-md">
                                        ✅ Approve Manager
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2 text-center">
                                    Langkah selanjutnya: Approval Direktur
                                </p>
                            </div>
                        @else
                            {{-- Pesan untuk Staff/User lain yang melihat --}}
                            <div class="mt-6 p-4 bg-gray-50 rounded text-center text-gray-500 text-sm italic">
                                Menunggu persetujuan Manager...
                            </div>
                        @endif
                    @endif


                    @if ($request->status->value === 'pending_director')

                        {{-- Cek 1: Apakah user adalah Direktur? --}}
                        {{-- Cek 2: Apakah Group Direkturnya COCOK dengan departemen pemohon? --}}
                        @if (Auth::user()->role === 'director' && Auth::user()->director_group === $request->user->department->director_group)
                            <div class="mt-8 pt-6 border-t bg-blue-50 p-4 rounded-lg border-blue-100">
                                <h4 class="text-sm font-bold text-blue-800 mb-3">👔 Action Direktur
                                    ({{ ucfirst(Auth::user()->director_group) }}):</h4>

                                <div class="flex gap-2">
                                    <button wire:click="reject" wire:confirm="Tolak pengajuan ini?"
                                        class="px-4 py-2 bg-white text-red-600 border border-red-300 rounded font-bold text-sm hover:bg-red-50">
                                        Tolak
                                    </button>

                                    <button wire:click="approveDirector" wire:confirm="Setujui sebagai Direktur?"
                                        class="flex-1 bg-blue-700 text-white py-2 rounded font-bold text-sm hover:bg-blue-800 shadow-md">
                                        ✅ Approve Direktur
                                    </button>
                                </div>
                            </div>
                        @elseif(Auth::user()->role === 'director')
                            {{-- Jika Direktur login tapi BUKAN wewenangnya (misal Direktur Finance lihat tiket Ops) --}}
                            <div
                                class="mt-6 p-4 bg-red-50 rounded text-center text-red-500 text-sm font-bold border border-red-100">
                                ⛔ Anda login sebagai Direktur {{ ucfirst(Auth::user()->director_group) }},
                                namun tiket ini butuh approval Direktur
                                {{ ucfirst($request->user->department->director_group) }}.
                            </div>
                        @else
                            <div class="mt-6 p-4 bg-gray-50 rounded text-center text-gray-500 text-sm italic">
                                Menunggu persetujuan Direktur...
                            </div>
                        @endif
                    @endif


                    @if ($request->status->value === 'pending_finance')

                        @if (Auth::user()->role === 'finance')
                            <div class="mt-8 pt-6 border-t bg-green-50 p-4 rounded-lg border-green-100">
                                <h4 class="text-sm font-bold text-green-800 mb-3">💸 Action Finance:</h4>

                                <div class="flex gap-2">
                                    <button wire:click="reject" wire:confirm="Dokumen tidak valid, tolak?"
                                        class="px-4 py-2 bg-white text-red-600 border border-red-300 rounded font-bold text-sm hover:bg-red-50">
                                        Revisi / Tolak
                                    </button>

                                    <button wire:click="approveFinance" wire:confirm="Dana sudah dicairkan?"
                                        class="flex-1 bg-green-600 text-white py-2 rounded font-bold text-sm hover:bg-green-700 shadow-md">
                                        💰 Proses Pembayaran (Cairkan)
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="mt-6 p-4 bg-gray-50 rounded text-center text-gray-500 text-sm italic">
                                Menunggu pencairan dana oleh Finance...
                            </div>
                        @endif
                    @endif
                </ol>
            </div>
        </div>
    </div>
</div>
