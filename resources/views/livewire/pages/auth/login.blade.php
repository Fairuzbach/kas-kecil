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
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full justify-center py-2.5" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="login">
                    {{ __('Masuk Sekarang') }}
                </span>
                <span wire:loading wire:target="login">
                    Menghubungkan...
                </span>
            </button>
        </div>
    </form>
</div>

@script
    <script>
        const overlay = document.getElementById('loading-overlay');

        Livewire.hook('commit', ({
            succeed,
            fail
        }) => {
            // Tampilkan overlay saat request dimulai
            overlay.style.display = 'flex';

            fail(() => {
                // Jika gagal (validasi error dll), langsung sembunyikan
                overlay.style.display = 'none';
            });

            // ✅ Jangan sembunyikan di succeed — biarkan tetap tampil
            // selama Livewire navigate masih berjalan
        });

        // ✅ Sembunyikan overlay SETELAH halaman baru selesai dimuat sepenuhnya
        document.addEventListener('livewire:navigated', () => {
            overlay.style.display = 'none';
        });
    </script>
@endscript
