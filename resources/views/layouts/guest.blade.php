<x-base-layout>

    {{-- Memanggil CSS khusus halaman Auth/Login --}}
    <x-slot:styles>
        @vite('resources/css/guest.css')
    </x-slot:styles>

    <div class="page-grid">

        {{-- ══ KOMPONEN PANEL KIRI ══ --}}
        <x-auth.left-panel />

        {{-- ══ PANEL KANAN (Tempat form Login/Register berada) ══ --}}
        <div class="right-panel page-enter-fade">
            <div class="form-wrap">

                {{-- Logo khusus tampilan Mobile --}}
                <div class="mobile-logo">
                    <img src="{{ asset('logo.webp') }}" alt="Logo">
                    <span class="mobile-logo-name">{{ config('app.name', 'Finance Portal') }}</span>
                    <span class="mobile-logo-div">Finance &amp; Accounting Division</span>
                </div>

                {{-- Konten Form dari Breeze akan dirender disini --}}
                {{ $slot }}

            </div>
        </div>

    </div>

</x-base-layout>
