<x-base-layout>

    {{-- Memanggil CSS yang tadi sudah kita pisah ke file tersendiri --}}
    <x-slot:styles>
        @vite('resources/css/landing.css')
    </x-slot:styles>

    {{-- Komponen Navbar --}}
    <x-landing.navbar />

    {{-- Komponen Hero (Mengoper variabel dari controller/route) --}}
    <x-landing.hero :requestsThisMonth="$requestsThisMonth ?? 0" :approvalRate="$approvalRate ?? 0" :approvedToday="$approvedToday ?? 0" />

    {{-- Komponen Fitur (Features & Steps) --}}
    <x-landing.features />

    {{-- Komponen Footer --}}
    <x-landing.footer />

</x-base-layout>
