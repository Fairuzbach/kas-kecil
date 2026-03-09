@props(['requestsThisMonth' => 0, 'approvalRate' => 0, 'approvedToday' => 0])

<section class="hero-wrap">
    {{-- LEFT --}}
    <div class="hero-left">
        <div class="hero-pill">
            <div class="hero-pill-dot">F&A</div>
            <span>Sistem Pengajuan Pengeluaran Resmi</span>
        </div>

        <h1 class="hero-title">
            Pengajuan <span class="t-red">Bukti</span><br>
            Pengeluaran yang<br>
            <span class="t-acc">Paperless</span> &amp; Otomatis
        </h1>

        <p class="hero-desc">
            Platform digital terintegrasi untuk pengelolaan reimbursement dan kas divisi. Tinggalkan cara manual—kini
            semua proses berjalan transparan, efisien, dan terhubung langsung ke sistem akuntansi perusahaan.
        </p>

        <div class="hero-cta">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-hero-login" wire:navigate>
                        Buka Dashboard
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-hero-login" wire:navigate>
                        Masuk ke Sistem
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                        </svg>
                    </a>
                @endauth
            @endif

            <div class="hero-trust">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
                Akses khusus karyawan terverifikasi
            </div>
        </div>

        <div class="hero-stats">
            <div class="hstat">
                <span class="hstat-val r">{{ number_format($requestsThisMonth) }}</span>
                <span class="hstat-lbl">Pengajuan Bulan Ini</span>
            </div>
            <div class="hstat">
                <span class="hstat-val b">{{ $approvalRate }}%</span>
                <span class="hstat-lbl">Tingkat Disetujui</span>
            </div>
            <div class="hstat">
                <span class="hstat-val y">{{ number_format($approvedToday) }}</span>
                <span class="hstat-lbl">Disetujui Hari Ini</span>
            </div>
        </div>
    </div>

    {{-- RIGHT — Process illustration --}}
    <div class="hero-right">
        <div class="flow-card">
            <div class="flow-card-hdr">
                <span class="flow-card-title">Alur Pengajuan</span>
                <span class="flow-card-sub">4 langkah mudah</span>
            </div>

            <div class="flow-steps">
                <div class="flow-step">
                    <div class="flow-step-icon" style="background:var(--red-lt); color:var(--red);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                    <div class="flow-step-body">
                        <span class="flow-step-title">Upload Dokumen</span>
                        <span class="flow-step-desc">Struk, invoice, atau nota resmi</span>
                    </div>
                    <div class="flow-step-num">01</div>
                </div>

                <div class="flow-connector">
                    <div class="flow-connector-line"></div>
                </div>

                <div class="flow-step">
                    <div class="flow-step-icon" style="background:var(--yellow-lt); color:var(--yellow-dk);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div class="flow-step-body">
                        <span class="flow-step-title">Verifikasi Otomatis</span>
                        <span class="flow-step-desc">Sistem memeriksa kelengkapan berkas</span>
                    </div>
                    <div class="flow-step-num">02</div>
                </div>

                <div class="flow-connector">
                    <div class="flow-connector-line"></div>
                </div>

                <div class="flow-step">
                    <div class="flow-step-icon" style="background:var(--blue-lt); color:var(--blue-md);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div class="flow-step-body">
                        <span class="flow-step-title">Approval Multi-Level</span>
                        <span class="flow-step-desc">Persetujuan bertingkat sesuai nilai</span>
                    </div>
                    <div class="flow-step-num">03</div>
                </div>

                <div class="flow-connector">
                    <div class="flow-connector-line"></div>
                </div>

                <div class="flow-step flow-step--done">
                    <div class="flow-step-icon" style="background:#DCFCE7; color:var(--green);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                    </div>
                    <div class="flow-step-body">
                        <span class="flow-step-title">Dana Diproses</span>
                        <span class="flow-step-desc">Tercatat otomatis di sistem akuntansi</span>
                    </div>
                    <div class="flow-step-num" style="color:var(--green);">✓</div>
                </div>
            </div>
        </div>

        <div class="highlights-row">
            <div class="highlight-item">
                <div class="highlight-icon" style="background:var(--red-lt);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--red)"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
                <div>
                    <p class="highlight-title">Proses Cepat</p>
                    <p class="highlight-sub">Rata-rata 4 jam</p>
                </div>
            </div>
            <div class="highlight-item">
                <div class="highlight-icon" style="background:var(--yellow-lt);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--yellow-dk)"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div>
                    <p class="highlight-title">Aman &amp; Terenkripsi</p>
                    <p class="highlight-sub">Akses terverifikasi</p>
                </div>
            </div>
            <div class="highlight-item">
                <div class="highlight-icon" style="background:var(--blue-lt);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--blue-md)"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                </div>
                <div>
                    <p class="highlight-title">Terdokumentasi</p>
                    <p class="highlight-sub">Riwayat lengkap</p>
                </div>
            </div>
        </div>
    </div>
</section>
