<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <div>
            <x-input-label for="nik" :value="__('Nomor Induk Karyawan (NIK)')" />

            {{-- Ubah wire:model dari email ke nik (tambahkan 'form.' jika pakai Form Object) --}}
            <x-text-input wire:model="form.nik" id="nik" class="block mt-1 w-full" type="text" name="nik"
                required autofocus autocomplete="nik" placeholder="Masukkan NIK" />

            <x-input-error :messages="$errors->get('form.nik')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="current-password" placeholder="Masukkan Password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer"
                    name="remember">
                <span class="ml-2 text-sm text-gray-600 font-medium">{{ __('Ingat Saya') }}</span>
            </label>

            @php
                // 1. Ganti dengan nomor WhatsApp Anda (Gunakan awalan 62, tanpa 0 atau +)
                $waNumber = '6285156469296';

                // 2. Format pesan form yang akan otomatis muncul di WA user
                $waMessage =
                    'Halo Admin, saya lupa password akun Finance Portal saya. Berikut data untuk reset password:%0A%0A' .
                    'Nama Lengkap : %0A' .
                    'NIK : %0A' .
                    'Departemen : %0A%0A' .
                    'Mohon bantuannya. Terima kasih.';

                $waLink = "https://wa.me/{$waNumber}?text={$waMessage}";
            @endphp

            <a href="{{ $waLink }}" target="_blank" class="text-[0.78rem] font-bold"
                style="color: var(--blue-md);">
                Lupa Password?
            </a>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full justify-center py-2.5">
                {{ __('Masuk Sekarang') }}
            </button>
        </div>
    </form>
</div>
