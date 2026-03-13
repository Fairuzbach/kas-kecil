<style>
    /* CSS Khusus untuk Info Login */
    .lp-login-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: .5rem;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 1.4rem;
        box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .info-step {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .info-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: rgba(245, 194, 0, 0.15);
        color: var(--yellow);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: .85rem;
        flex-shrink: 0;
        border: 1px solid rgba(245, 194, 0, 0.3);
    }

    .info-text {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .info-text strong {
        font-size: .85rem;
        color: #fff;
        letter-spacing: .02em;
    }

    .info-text span {
        font-size: .75rem;
        color: rgba(255, 255, 255, 0.65);
        line-height: 1.6;
    }

    /* ── LUPA PASSWORD LINK ── */
    .forgot-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 0.5rem;
        padding: 0.45rem 0.9rem;
        background: rgba(245, 194, 0, 0.1);
        border: 1px solid rgba(245, 194, 0, 0.25);
        border-radius: 8px;
        color: var(--yellow);
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        width: fit-content;
        transition: background 0.2s ease, border-color 0.2s ease;
    }

    .forgot-link:hover {
        background: rgba(245, 194, 0, 0.18);
        border-color: rgba(245, 194, 0, 0.45);
    }

    .forgot-link svg {
        flex-shrink: 0;
    }
</style>

<div class="left-panel page-enter-slide">
    <div class="lp-blob lp-blob--1"></div>
    <div class="lp-blob lp-blob--2"></div>

    <a href="/" class="lp-brand" wire:navigate>
        <div class="lp-logo">
            <img src="{{ asset('logo.webp') }}" alt="Logo">
        </div>
        <div class="lp-brand-text">
            <span class="lp-app-name">{{ config('app.name', 'FA Portal') }}</span>
            <span class="lp-app-div">Finance &amp; Accounting Division</span>
        </div>
    </a>

    <div class="lp-hero">
        <div class="lp-pill">
            <span class="lp-pill-dot"></span>
            <span>Panduan Akses Sistem</span>
        </div>

        <div>
            <h2 class="lp-title">
                Selamat Datang di<br>
                Portal <span class="t-yellow">Keuangan</span>
            </h2>
            <p class="lp-desc" style="margin-top:.9rem;">
                Silakan masuk menggunakan kredensial karyawan Anda untuk mengelola pengajuan kas dan memantau anggaran
                divisi.
            </p>
        </div>

        {{-- BOX PANDUAN LOGIN --}}
        <div class="lp-login-info">
            <div class="info-step">
                <div class="info-icon">1</div>
                <div class="info-text">
                    <strong>Gunakan NIK Karyawan</strong>
                    <span>Masukkan Nomor Induk Karyawan (NIK) Anda yang terdaftar sebagai username.</span>
                </div>
            </div>

            <div class="info-step">
                <div class="info-icon">2</div>
                <div class="info-text">
                    <strong>Lupa Password?</strong>
                    <span>Hubungi admin untuk mereset password Anda. Klik tombol di bawah ini.</span>

                    @php
                        // 1. Ganti dengan nomor WhatsApp Anda (Gunakan awalan 62, tanpa 0 atau +)
                        $waNumber = '6285156469296';

                        // 2. Format pesan form yang akan otomatis muncul di WA user
                        $waMessage =
                            'Halo Admin, saya lupa password akun FA Portal saya. Berikut data untuk reset password:%0A%0A' .
                            'Nama Lengkap : %0A' .
                            'NIK : %0A' .
                            'Departemen : %0A%0A' .
                            'Mohon bantuannya. Terima kasih.';

                        $waLink = "https://wa.me/{$waNumber}?text={$waMessage}";
                    @endphp

                    <a href="{{ $waLink }}" target="_blank" class="forgot-link">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        Lupa Password?
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="lp-footer">
        <p>Sistem Keuangan Terintegrasi</p>
        <p>Finance &amp; Accounting &copy; {{ date('Y') }}</p>
    </div>
</div>
