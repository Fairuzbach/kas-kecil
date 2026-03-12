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

            <div class="relative">
                <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full pr-10" type="password"
                    name="password" required autocomplete="current-password" placeholder="Masukkan Password" />

                <button type="button" onclick="togglePassword()"
                    class="absolute inset-y-0 right-0 mt-1 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21" />
                    </svg>
                </button>
            </div>

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
        window.togglePassword = function() {
            const input = document.getElementById('password');
            const iconEye = document.getElementById('icon-eye');
            const iconEyeOff = document.getElementById('icon-eye-off');

            if (input.type === 'password') {
                input.type = 'text';
                iconEye.classList.add('hidden');
                iconEyeOff.classList.remove('hidden');
            } else {
                input.type = 'password';
                iconEye.classList.remove('hidden');
                iconEyeOff.classList.add('hidden');
            }
        }

        const overlay = document.getElementById('loading-overlay');

        Livewire.hook('commit', ({
            component,
            succeed,
            fail
        }) => {
            const isLoginAction = component.name === 'pages.auth.login';
            if (isLoginAction) overlay.style.display = 'flex';

            succeed(() => {
                overlay.style.display = 'none';
            });

            fail(() => {
                overlay.style.display = 'none';
            });
        });

        document.addEventListener('livewire:navigated', () => {
            overlay.style.display = 'none';
        });
    </script>
@endscript
