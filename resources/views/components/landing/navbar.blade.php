<nav class="navbar">
    <a href="/" class="nav-brand" wire:navigate>
        <div class="nav-logo-wrap">
            <img src="{{ asset('logo.webp') }}" alt="Logo">
        </div>
        <div class="nav-brand-info">
            <span class="nav-app-name">{{ config('app.name', 'FA Portal') }}</span>
            <span class="nav-app-div">Finance &amp; Accounting Division</span>
        </div>
    </a>

    <div style="display:flex;align-items:center;">
        <div class="nav-divider"></div>
        <span class="nav-tagline">Sistem Pengajuan Bukti Pengeluaran</span>
    </div>

    @if (Route::has('login'))
        <div class="nav-right">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-nav-dashboard" wire:navigate>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                    </svg>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-nav-login" wire:navigate>
                    Masuk ke Sistem
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                    </svg>
                </a>
            @endauth
        </div>
    @endif
</nav>
