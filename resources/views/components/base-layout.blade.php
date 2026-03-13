<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FA Portal') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,500;0,600;0,700;1,500&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Tempat untuk menyisipkan CSS khusus halaman --}}
    {{ $styles ?? '' }}
</head>

<body>
    {{-- Memanggil komponen Canvas Background yang tadi kita buat --}}
    <x-canvas-bg />

    {{-- Konten Utama Halaman akan masuk ke sini --}}
    {{ $slot }}

    {{-- Tempat untuk menyisipkan Script khusus halaman --}}
    {{ $scripts ?? '' }}
</body>

</html>
