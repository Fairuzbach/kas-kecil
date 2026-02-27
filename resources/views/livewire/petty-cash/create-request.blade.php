<div class="bg-gray-50 py-4 px-2 sm:px-4 font-sans text-black">

    {{-- ========================================================= --}}
    {{-- PANEL DIGITAL (Hanya tampil di layar, sembunyi saat print) --}}
    {{-- ========================================================= --}}
    <div class="w-full bg-white p-4 rounded-t-xl shadow-sm border-b-4 border-indigo-500 mb-4 print:hidden">
        <h3 class="text-base font-bold text-gray-800 mb-3">⚙️ Pengaturan Pengajuan (Digital)</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Tipe Pengajuan</label>
                <select wire:model.live="type"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition text-sm py-2">
                    <option value="">-- Pilih Jenis --</option>
                    @foreach (\App\Enums\PettyCashType::cases() as $type)
                        <option value="{{ $type->value }}" {{ $type->value === 'pengobatan' ? 'disabled' : '' }}>
                            {{ strtoupper($type->name) }} {{ $type->value === 'pengobatan' ? '(Nonaktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Upload Lampiran (Struk/Nota)</label>
                <input type="file" wire:model="attachment" accept="image/*, application/pdf"
                    class="w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <div wire:loading wire:target="attachment" class="text-xs text-blue-600 mt-1 animate-pulse">Mengunggah
                    file...</div>
                @error('attachment')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="w-full bg-white p-5 sm:p-8 shadow-md relative border border-gray-200">

        <div class="flex flex-col sm:flex-row justify-between items-start mb-4">
            <div class="font-bold italic text-base sm:text-lg tracking-wide uppercase">
                PT. JEMBO CABLE COMPANY Tbk,
            </div>

            <table class="border-collapse border border-black text-xs mt-3 sm:mt-0 w-full sm:w-auto">
                <tr>
                    <td class="border border-black px-3 py-1.5 font-bold bg-gray-50 w-24">Tanggal</td>
                    <td class="border border-black px-2 py-1.5 w-48">
                        <input type="date" wire:model="tanggal"
                            class="w-full border-none p-0 focus:ring-0 text-xs bg-transparent font-medium">
                    </td>
                </tr>
                <tr>
                    <td class="border border-black px-3 py-1.5 font-bold bg-gray-50">Ref No.</td>
                    <td class="border border-black px-2 py-1.5 font-mono text-gray-600">
                        <input type="text" wire:model="tracking_number" placeholder="(Auto Generate)" readonly
                            class="w-full border-none p-0 focus:ring-0 text-xs bg-transparent cursor-not-allowed">
                    </td>
                </tr>
            </table>
        </div>

        <h2 class="text-center font-bold text-lg sm:text-xl mt-6 mb-5 uppercase tracking-wider">
            Bukti Pengeluaran Kas
        </h2>

        <div class="mb-4 text-sm font-bold flex items-end gap-2">
            <span class="whitespace-nowrap">DIBAYAR KEPADA :</span>
            <input type="text" wire:model="dibayar_kepada" placeholder="Contoh: Kantin (Ny. Luminto)"
                class="uppercase flex-1 border-b border-black border-dashed bg-transparent p-0 px-2 focus:ring-0 focus:border-blue-600 outline-none text-blue-800 italic text-base">
        </div>

        {{-- TABEL RINCIAN --}}
        <div class="w-full">
            <table class="w-full border-collapse border border-black text-xs mb-4">
                <thead>
                    <tr class="bg-gray-50 font-bold text-center">
                        <th class="border border-black px-2 py-2 w-10">NO.</th>
                        <th class="border border-black px-2 py-2">KETERANGAN</th>
                        <th class="border border-black px-2 py-2 w-48">AKUN NO.</th>
                        <th class="border border-black px-2 py-2 w-24">DEPT.</th>
                        <th class="border border-black px-2 py-2 w-40">JUMLAH</th>
                        <th class="border border-black px-2 py-2 w-32 print:hidden bg-indigo-50 text-indigo-800">AKSI
                            DIGITAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr class="group hover:bg-gray-50 transition-colors">
                            <td class="border border-black px-2 py-1.5 text-center">{{ $index + 1 }}.</td>

                            <td class="border border-black px-2 py-1.5 relative align-top">
                                <div class="relative w-full">
                                    <div class="relative w-full flex items-center h-full">
                                        <input type="text"
                                            wire:model.live.debounce.150ms="items.{{ $index }}.item_name"
                                            wire:keydown.tab.prevent="acceptInlineSuggestion({{ $index }})"
                                            wire:keydown.right.prevent="acceptInlineSuggestion({{ $index }})"
                                            class="relative z-10 uppercase w-full border-none p-0 m-0 focus:ring-0 text-xs bg-transparent font-sans"
                                            placeholder="Masukkan keterangan...">

                                    </div>

                                    @if (!empty($suggestedCoas[$index]) && count($suggestedCoas[$index]) > 0)
                                        <div
                                            class="absolute z-30 left-0 top-full mt-2 w-full sm:w-[150%] md:w-[200%] bg-blue-50 border border-blue-200 shadow-xl rounded-md p-1.5 text-xs text-left">

                                            <div class="flex justify-between items-center mb-1 px-1">
                                                <span
                                                    class="text-blue-700 text-[10px] font-bold uppercase tracking-wider">💡
                                                    Saran COA:</span>
                                                <button type="button"
                                                    wire:click="$set('suggestedCoas.{{ $index }}', [])"
                                                    class="text-gray-400 hover:text-red-500 text-[10px] px-1 font-bold">✕</button>
                                            </div>

                                            <div class="flex flex-col gap-1 max-h-48 overflow-y-auto">
                                                @foreach ($suggestedCoas[$index] as $key => $suggestion)
                                                    <button type="button"
                                                        wire:click="applySuggestion({{ $index }}, '{{ $suggestion['id'] }}')"
                                                        class="suggestion-item w-full text-left px-2 py-1.5 bg-white border border-blue-100 hover:bg-blue-100 hover:border-blue-300 text-blue-800 rounded transition-all duration-150 hover:pl-3 shadow-sm"
                                                        style="animation: fadeSlideIn 0.2s ease both; animation-delay: {{ $key * 40 }}ms;">

                                                        <div class="font-bold text-xs">
                                                            {{ $suggestion['label'] }}
                                                        </div>

                                                        @if (!empty($suggestion['help_text']))
                                                            <div
                                                                class="text-[10px] text-gray-500 font-normal italic mt-0.5 leading-tight text-wrap">
                                                                {{ $suggestion['help_text'] }}
                                                            </div>
                                                        @endif

                                                    </button>
                                                @endforeach
                                            </div>

                                            <style>
                                                @keyframes fadeSlideIn {
                                                    from {
                                                        opacity: 0;
                                                        transform: translateY(-4px);
                                                    }

                                                    to {
                                                        opacity: 1;
                                                        transform: translateY(0);
                                                    }
                                                }
                                            </style>
                                        </div>
                                    @endif

                                </div>
                            </td>

                            <td class="border border-black px-2 py-1.5">
                                <select wire:model="items.{{ $index }}.coa_id"
                                    class="w-full border-none p-0 focus:ring-0 text-xs bg-transparent font-mono cursor-pointer truncate">
                                    <option value="">- Pilih Akun -</option>
                                    <optgroup label="Akun Biaya">
                                        @foreach ($coas as $c)
                                            <option value="{{ $c->id }}">{{ $c->code }} -
                                                {{ $c->name }}</option>
                                        @endforeach
                                    </optgroup>

                                    {{-- 2. COA SEMENTARA (HARDCODED) --}}
                                    <optgroup label="Akun Pajak (Sementara)">
                                        <option value="tax_ppn">115105 - PPN Masukan (11%)</option>
                                        <option value="tax_pph">211402 - Hutang PPh 23 (2%)</option>
                                    </optgroup>
                                </select>
                            </td>

                            <td class="border border-black px-2 py-1.5 bg-gray-100">
                                <input type="text" value="{{ auth()->user()->department->code ?? 'GA' }}" readonly
                                    class="w-full border-none p-0 focus:ring-0 text-xs bg-transparent text-center font-bold text-gray-600 cursor-not-allowed">
                            </td>

                            <td class="border border-black px-2 py-1.5">
                                <div class="flex items-center text-sm">
                                    <span class="mr-1 font-semibold">Rp.</span>
                                    <input type="number"
                                        wire:model.live.debounce.500ms="items.{{ $index }}.amount"
                                        class="w-full border-none p-0 focus:ring-0 text-sm bg-transparent text-right {{ ($item['amount'] ?? 0) < 0 ? 'text-red-600 font-bold' : '' }}"
                                        placeholder="0">
                                </div>
                            </td>

                            <td class="border border-black px-1.5 py-1.5 text-center print:hidden bg-indigo-50/30">
                                <div class="flex gap-1 mb-1">
                                    <button type="button" wire:click="addTax({{ $index }}, 'ppn')"
                                        title="Tambah PPN 11%"
                                        class="flex-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-[10px] font-bold py-1 rounded">+PPN</button>
                                    <button type="button" wire:click="addTax({{ $index }}, 'pph23')"
                                        title="Potong PPh 23 (2%)"
                                        class="flex-1 bg-red-100 hover:bg-red-200 text-red-700 text-[10px] font-bold py-1 rounded">-PPh</button>
                                </div>
                                <button type="button" wire:click="removeItem({{ $index }})"
                                    class="w-full text-red-500 hover:text-red-700 text-[10px] font-bold py-1 border border-red-200 rounded">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="print:hidden">
                        <td colspan="6" class="border border-black bg-gray-50 p-2">
                            <button type="button" wire:click="addItem"
                                class="w-full border-2 border-dashed border-gray-400 text-gray-600 font-bold py-2 rounded-md hover:bg-gray-100 hover:border-gray-600 transition text-xs">
                                + Tambah Baris Rincian
                            </button>
                        </td>
                    </tr>
                    <tr class="bg-gray-50">

                        <td colspan="4"
                            class="border border-black px-3 py-2 text-right font-bold tracking-widest text-sm">
                            Total
                        </td>
                        <td class="border border-black px-2 py-2 font-bold bg-gray-200 text-sm">
                            <div class="flex justify-between items-center w-full">
                                <span>Rp.</span>
                                <span>{{ number_format($total ?? 0, 0, ',', '.') }}.-</span>
                            </div>
                        </td>
                        <td class="border border-black print:hidden bg-gray-200"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mb-8 text-sm flex gap-2 items-start">
            <span class="font-bold whitespace-nowrap pt-0.5">Terbilang :</span>
            <div
                class="flex-1 min-h-[2rem] border-b border-black border-dashed px-2 py-0.5 text-blue-800 font-medium capitalize leading-snug text-sm">
                <span class="italic">{{ $terbilang ?? 'Nol Rupiah' }}</span>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center text-xs font-semibold mt-8 mb-4">
            <div class="flex flex-col justify-between h-20">
                <p>Disetujui Oleh,</p>
                <div class="mt-auto">
                    <div class="border-b border-black border-dashed mx-2 mb-1"></div>
                    <p class="text-[10px] font-normal">Dir</p>
                </div>
            </div>
            <div class="flex flex-col justify-between h-20">
                <p>Diperiksa Oleh,</p>
                <div class="mt-auto">
                    <div class="border-b border-black border-dashed mx-2 mb-1"></div>
                    <p class="text-[10px] font-normal">MANAGER</p>
                </div>
            </div>
            <div class="flex flex-col justify-between h-20">
                <p>Dibayar Oleh,</p>
                <div class="mt-auto">
                    <div class="border-b border-black border-dashed mx-2 mb-1"></div>
                    <p class="text-[10px] font-normal">Finance</p>
                </div>
            </div>
            <div class="flex flex-col justify-between h-20">
                <p>Diajukan Oleh,</p>
                <div class="mt-auto">
                    <div class="border-b border-black border-dashed mx-2 mb-1"></div>
                    <p class="text-[10px] font-normal">{{ auth()->user()->name ?? 'Pemohon' }}</p>
                </div>
            </div>
            <div class="flex flex-col justify-between h-20">
                <p>Diterima Oleh,</p>
                <div class="mt-auto">
                    <div class="border-b border-black border-dashed mx-2 mb-1"></div>
                    <p class="text-[10px] font-normal">Penerima Dana</p>
                </div>
            </div>
        </div>

    </div>

    {{-- TOMBOL SUBMIT (Digital Only) --}}
    <div class="w-full mt-4 flex justify-end gap-3 print:hidden">
        <button type="button" onclick="window.print()"
            class="px-4 py-2 bg-gray-600 text-white text-sm font-bold rounded-lg shadow hover:bg-gray-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            Print Draft
        </button>
        <button type="button" wire:click="save"
            class="px-5 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg shadow hover:bg-blue-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Kirim Pengajuan
        </button>
    </div>

</div>
