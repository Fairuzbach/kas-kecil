<div class="py-12 font-sans text-gray-900">
    @include('livewire.finance.partials.styles')
    @include('livewire.finance.partials.scripts')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- =========================== --}}
        {{-- 1. HEADER & FILTER SECTION --}}
        {{-- =========================== --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        📊 Finance Analytics
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Monitoring arus kas dan pengeluaran per departemen</p>
                </div>

                <div class="flex flex-wrap gap-3 bg-gray-50 p-2 rounded-lg border border-gray-100">
                    {{-- Filter Tahun --}}
                    <div>
                        <select wire:model.live="year"
                            class="block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2 pl-3 pr-10">
                            @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Filter Periode --}}
                    <div>
                        <select wire:model.live="period"
                            class="block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2 pl-3 pr-10">
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                            <option value="quarterly">Per Kuartal</option>
                            <option value="semester">Per Semester</option>
                            <option value="yearly">Tahunan</option>
                        </select>
                    </div>

                    {{-- Filter Departemen --}}
                    <div>
                        <select wire:model.live="department_id"
                            class="block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm py-2 pl-3 pr-10">
                            <option value="all">Semua Departemen</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- =========================== --}}
        {{-- 2. SUMMARY CARDS --}}
        {{-- =========================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- CARD 1: TOTAL REALISASI (Gradient Blue) --}}
            <div
                class="bg-gradient-to-br from-indigo-600 to-blue-700 p-6 rounded-xl shadow-lg text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-indigo-100 text-xs uppercase font-bold tracking-wider mb-1">Total Realisasi
                                (PAID)</p>
                            <h3 class="text-3xl font-extrabold tracking-tight">
                                Rp {{ number_format($totalExpense, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="p-2 bg-white/10 rounded-lg backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>

                    <div
                        class="mt-4 flex items-center gap-2 text-xs font-medium text-indigo-100 bg-white/10 w-fit px-2 py-1 rounded">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>Tahun {{ $year }} • {{ ucfirst($period) }}</span>
                    </div>
                </div>

                {{-- Dekorasi Background --}}
                <div
                    class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700">
                </div>
            </div>

            {{-- CARD 2: DEPARTEMEN TERBOROS (White with Red Accent) --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-gray-500 text-xs uppercase font-bold tracking-wider flex items-center gap-1">
                            <span class="text-lg">🔥</span> Departemen Terboros
                        </p>
                    </div>
                    <span
                        class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-100 uppercase">
                        Highest Spender
                    </span>
                </div>

                @if ($highestSpender && $highestSpender->department)
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 line-clamp-1"
                            title="{{ $highestSpender->department->name }}">
                            {{ $highestSpender->department->name }}
                        </h3>

                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl font-bold text-red-500">
                                Rp {{ number_format($highestSpender->total_amount, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="mt-3 flex items-center gap-1 text-xs text-gray-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            <span>Total akumulasi tahun {{ $year }}</span>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-24 text-gray-400">
                        <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                        <span class="text-xs">Belum ada data realisasi</span>
                    </div>
                @endif
            </div>

            {{-- CARD 3: INFO TAMBAHAN (Opsional - Agar Grid Rapi) --}}
            <div
                class="hidden md:flex flex-col justify-center bg-gray-50 p-6 rounded-xl border border-dashed border-gray-300 text-center">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Status Dashboard</p>
                <div class="mt-2 flex items-center justify-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                    <span class="text-sm font-medium text-gray-600">Live Updated</span>
                </div>
                <p class="text-[10px] text-gray-400 mt-1">Data sinkron realtime</p>
            </div>

        </div>

        {{-- =========================== --}}
        {{-- 3. CHARTS SECTION --}}
        {{-- =========================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- CHART KIRI: TREND (Lebar 2/3) --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="bg-indigo-100 text-indigo-600 p-1.5 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </span>
                        Grafik Tren Pengeluaran
                    </h3>
                </div>

                {{-- Container Chart --}}
                <div id="trendChart" class="w-full h-[450px]" wire:ignore></div>
            </div>

            {{-- CHART KANAN: TOP 10 COA (Lebar 1/3) --}}
            <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <span class="bg-yellow-100 text-yellow-600 p-1.5 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z">
                                </path>
                            </svg>
                        </span>
                        Top 10 Pengeluaran
                    </h3>
                    <p class="text-xs text-gray-500 mt-1 ml-9">Berdasarkan kategori akun (COA)</p>
                </div>

                {{-- Container Chart --}}
                <div id="coaChart" class="w-full h-[500px]" wire:ignore></div>
            </div>
        </div>

    </div>

    {{-- SCRIPT JAVASCRIPT LENGKAP --}}

</div>
