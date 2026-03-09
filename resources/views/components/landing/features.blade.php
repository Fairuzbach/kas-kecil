<section class="features">
    <div class="section-head">
        <div>
            <p class="section-eyebrow">Fitur Unggulan</p>
            <h2 class="section-title">Semua yang Anda Butuhkan<br>dalam Satu Platform</h2>
        </div>
        <p class="section-sub">Dirancang khusus untuk mendukung proses keuangan yang akuntabel, cepat, dan mudah
            digunakan seluruh divisi.</p>
    </div>

    <div class="feat-grid">
        <div class="feat-card fc-r">
            <div class="feat-icon-wrap r">🧾</div>
            <h3>Pengajuan Bukti Pengeluaran</h3>
            <p>Upload struk, invoice, dan nota resmi secara digital. Proses pengajuan lebih cepat tanpa dokumen kertas.
            </p>
            <a href="{{ route('login') }}" class="feat-arrow r" wire:navigate>Mulai Ajukan →</a>
        </div>

        <div class="feat-card fc-y">
            <div class="feat-icon-wrap y">📊</div>
            <h3>Monitoring Anggaran Real-time</h3>
            <p>Pantau realisasi vs alokasi anggaran setiap divisi secara langsung dengan visualisasi yang mudah
                dipahami.</p>
            <a href="{{ route('login') }}" class="feat-arrow y" wire:navigate>Lihat Dashboard →</a>
        </div>

        <div class="feat-card fc-b">
            <div class="feat-icon-wrap b">✅</div>
            <h3>Approval Multi-Level</h3>
            <p>Alur persetujuan bertingkat — dari atasan langsung, manajer keuangan, hingga direksi sesuai nilai
                transaksi.</p>
            <a href="{{ route('login') }}" class="feat-arrow b" wire:navigate>Lihat Alur →</a>
        </div>

        <div class="feat-card feat-card-wide fc-b">
            <div>
                <div class="feat-icon-wrap" style="background:rgba(255,255,255,.12);">⚡</div>
                <h3>Proses Pengajuan Lebih Cepat &amp; Terstruktur</h3>
                <p>Dari upload dokumen hingga dana cair — semua terdokumentasi secara otomatis, dapat ditelusuri
                    riwayatnya, dan terintegrasi penuh dengan sistem akuntansi perusahaan.</p>
                <a href="{{ route('login') }}" class="feat-arrow"
                    style="color:var(--yellow)!important; margin-top:1.2rem; display:inline-flex; align-items:center; gap:.4rem; font-size:.75rem; font-weight:800;"
                    wire:navigate>
                    Masuk dan Mulai →
                </a>
            </div>
            <div class="steps-col">
                <div class="step-item">
                    <div class="step-num r">1</div>
                    <span class="step-text">Upload bukti pengeluaran &amp; lengkapi form pengajuan</span>
                </div>
                <div class="step-item">
                    <div class="step-num y">2</div>
                    <span class="step-text">Verifikasi dokumen otomatis &amp; diteruskan ke atasan</span>
                </div>
                <div class="step-item">
                    <div class="step-num b">3</div>
                    <span class="step-text">Approval multi-level dengan notifikasi real-time</span>
                </div>
                <div class="step-item" style="background:rgba(245,194,0,.1);border-color:rgba(245,194,0,.2);">
                    <div class="step-num g">✓</div>
                    <span class="step-text" style="color:rgba(245,194,0,.9);">Dana diproses &amp; otomatis tercatat
                        dalam sistem akuntansi</span>
                </div>
            </div>
        </div>
    </div>
</section>
