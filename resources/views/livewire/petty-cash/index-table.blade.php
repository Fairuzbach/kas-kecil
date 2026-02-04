<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Riwayat Pengajuan</h3>
            <button wire:click="$refresh" class="text-sm text-gray-500 hover:text-indigo-600">
                🔄 Refresh Data
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Tracking No</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Judul</th>
                        <th class="px-6 py-3">Nominal</th>
                        <th class="px-6 py-3">Tipe</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $req->tracking_number ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $req->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $req->title }}</div>
                                <div class="text-xs text-gray-400">{{ $req->user->name }}</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-700">
                                Rp {{ number_format($req->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ $req->type->label() ?? $req->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $color = match ($req->status->value) {
                                        'draft' => 'gray',
                                        'pending_manager', 'pending_director', 'pending_finance' => 'yellow',
                                        'paid', 'approved' => 'green',
                                        'rejected' => 'red',
                                        default => 'gray',
                                    };
                                @endphp
                                <span
                                    class="bg-{{ $color }}-100 text-{{ $color }}-800 text-xs font-medium px-2.5 py-0.5 rounded border border-{{ $color }}-200">
                                    {{ $req->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('petty-cash.show', $req->id) }}" wire:navigate
                                    class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline cursor-pointer">
                                    Lihat Detail ➜
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-400">
                                Belum ada data pengajuan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>
</div>
