<style>
    /* CSS Khusus untuk Info Login & Animasi Hover Password */
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

    /* ── ANIMASI HOVER PASSWORD ── */
    .pwd-reveal-box {
        position: relative;
        margin-top: 0.6rem;
        display: inline-block;
        background: rgba(0, 0, 0, 0.4);
        border: 1px dashed rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
    }

    .pwd-secret {
        padding: 0.5rem 1.2rem;
        font-family: monospace;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--yellow);
        letter-spacing: 2px;
        filter: blur(6px);
        /* Memblur teks secara default */
        opacity: 0.5;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .pwd-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 600;
        transition: opacity 0.3s ease;
        z-index: 2;
    }

    /* Saat user melakukan Hover */
    .pwd-reveal-box:hover .pwd-overlay {
        opacity: 0;
        /* Sembunyikan tulisan overlay */
    }

    .pwd-reveal-box:hover .pwd-secret {
        filter: blur(0);
        /* Perjelas teks password */
        opacity: 1;
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
            <span class="lp-app-name">{{ config('app.name', 'Finance Portal') }}</span>
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

        {{-- BOX PANDUAN LOGIN BARU --}}
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
                    <strong>Password Default</strong>
                    <span>Jika ini login pertama Anda, silakan gunakan password bawaan sistem berikut:</span>

                    {{-- Interaksi Hover Password --}}
                    <div class="pwd-reveal-box">
                        <div class="pwd-overlay">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Hover untuk lihat
                        </div>
                        <div class="pwd-secret">password123</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="lp-footer">
        <p>Sistem Keuangan Terintegrasi</p>
        <p>Finance &amp; Accounting &copy; {{ date('Y') }}</p>
    </div>
</div>
