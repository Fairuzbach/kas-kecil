<x-base-layout>

    <x-slot:styles>
        @vite('resources/css/guest.css')
    </x-slot:styles>

    {{-- ══ LOADING OVERLAY ══
         Dikontrol via JS di login.blade.php menggunakan @script + Livewire commit hook
    --}}
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="loading-shield">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
            </svg>
        </div>
        <span class="loading-text">Memverifikasi Akses</span>
        <span class="loading-sub">Harap tunggu sebentar...</span>
        <div class="loading-bar-track">
            <div class="loading-bar-fill"></div>
        </div>
    </div>

    <div class="page-grid">
        {{-- ══ KOMPONEN PANEL KIRI ══ --}}
        <x-auth.left-panel />

        {{-- ══ PANEL KANAN ══ --}}
        <div class="right-panel page-enter-fade">
            <div class="form-wrap">

                <div class="mobile-logo">
                    <img src="{{ asset('logo.webp') }}" alt="Logo">
                    <span class="mobile-logo-name">{{ config('app.name', 'Finance Portal') }}</span>
                    <span class="mobile-logo-div">Finance &amp; Accounting Division</span>
                </div>

                {{ $slot }}

            </div>
        </div>
    </div>

</x-base-layout>
