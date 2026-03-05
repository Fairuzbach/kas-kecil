<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,500;0,600;0,700;1,500&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ══════════════════════════════════════
           DESIGN TOKENS — identik dengan welcome
        ══════════════════════════════════════ */
        :root {
            --red: #D0222A;
            --red-dk: #A81820;
            --red-lt: #FDEAEA;
            --yellow: #F5C200;
            --yellow-dk: #C99A00;
            --yellow-lt: #FFFAE0;
            --blue: #1A3B8C;
            --blue-md: #2450B5;
            --blue-lt: #EBF0FB;
            --white: #FFFFFF;
            --off: #F4F6FB;
            --text: #0F1C2E;
            --muted: #5E6E8A;
            --border: #DDE4F0;
            --green: #16794C;
            --shadow-sm: 0 1px 3px rgba(15, 28, 46, .06), 0 1px 2px rgba(15, 28, 46, .04);
            --shadow-md: 0 4px 20px rgba(15, 28, 46, .08), 0 2px 6px rgba(15, 28, 46, .04);
            --shadow-lg: 0 12px 40px rgba(15, 28, 46, .12), 0 4px 12px rgba(15, 28, 46, .06);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--off);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── CANVAS (sama dengan welcome) ── */
        #bg-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        /* ── TOP STRIPE (identik welcome) ── */
        .top-stripe {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            z-index: 200;
            background: linear-gradient(90deg, var(--blue) 0%, var(--red) 48%, var(--yellow) 100%);
        }

        /* ══════════════════════════════════════
           LAYOUT UTAMA — dua kolom
        ══════════════════════════════════════ */
        .page-grid {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* ══════════════════════════════════════
           PANEL KIRI — branding + fitur
        ══════════════════════════════════════ */
        .left-panel {
            position: relative;
            background: linear-gradient(150deg, var(--blue) 0%, #0C2255 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3rem 3.5rem;
            overflow: hidden;
        }

        /* Stripe atas panel kiri */
        .left-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--yellow) 0%, var(--red) 55%, transparent 100%);
        }

        /* Glow blob */
        .lp-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
        }

        .lp-blob--1 {
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(245, 194, 0, .14) 0%, transparent 70%);
            top: -80px;
            right: -80px;
        }

        .lp-blob--2 {
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, rgba(208, 34, 42, .12) 0%, transparent 70%);
            bottom: -60px;
            left: -60px;
        }

        /* Brand row */
        .lp-brand {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: .9rem;
            text-decoration: none !important;
        }

        .lp-logo {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .2);
        }

        .lp-logo img {
            width: 34px;
            height: 34px;
            object-fit: contain;
        }

        .lp-brand-text {
            display: flex;
            flex-direction: column;
            gap: .06rem;
        }

        .lp-app-name {
            font-size: .92rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.01em;
            line-height: 1.2;
        }

        .lp-app-div {
            font-size: .56rem;
            font-weight: 600;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--yellow);
        }

        /* Hero text */
        .lp-hero {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 2rem;
            padding: 2rem 0;
        }

        .lp-pill {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            background: rgba(245, 194, 0, .12);
            border: 1px solid rgba(245, 194, 0, .25);
            border-radius: 99px;
            padding: .3rem .9rem;
            width: fit-content;
        }

        .lp-pill span {
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--yellow);
        }

        .lp-pill-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--yellow);
        }

        .lp-title {
            font-family: 'Lora', serif;
            font-size: 2.4rem;
            font-weight: 700;
            line-height: 1.22;
            color: #fff;
            letter-spacing: -.02em;
        }

        .lp-title .t-yellow {
            color: var(--yellow);
        }

        .lp-title .t-red {
            color: #FF8B8F;
        }

        .lp-desc {
            font-size: .82rem;
            color: rgba(255, 255, 255, .62);
            line-height: 1.75;
            max-width: 320px;
        }

        /* Feature items */
        .lp-features {
            display: flex;
            flex-direction: column;
            gap: .65rem;
        }

        .lp-feat {
            display: flex;
            align-items: center;
            gap: .85rem;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 12px;
            padding: .75rem 1rem;
            transition: background .18s;
        }

        .lp-feat:hover {
            background: rgba(255, 255, 255, .11);
        }

        .lp-feat-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: .95rem;
        }

        .lp-feat-icon.r {
            background: rgba(208, 34, 42, .28);
        }

        .lp-feat-icon.y {
            background: rgba(245, 194, 0, .2);
        }

        .lp-feat-icon.b {
            background: rgba(255, 255, 255, .12);
        }

        .lp-feat-text {
            font-size: .76rem;
            color: rgba(255, 255, 255, .82);
            font-weight: 600;
            line-height: 1.3;
        }

        .lp-feat-sub {
            font-size: .65rem;
            color: rgba(255, 255, 255, .4);
            font-weight: 400;
            display: block;
            margin-top: .08rem;
        }

        /* Footer */
        .lp-footer {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1.2rem;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }

        .lp-footer p {
            font-size: .6rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .28);
        }

        /* ══════════════════════════════════════
           PANEL KANAN — form slot
        ══════════════════════════════════════ */
        .right-panel {
            position: relative;
            background: var(--white);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 3.5rem;
        }

        /* Stripe atas panel kanan */
        .right-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, var(--blue) 40%, var(--red) 70%, var(--yellow) 100%);
        }

        .form-wrap {
            width: 100%;
            max-width: 400px;
            animation: fadeUp .75s cubic-bezier(.22, 1, .36, 1) both;
        }

        /* Mobile logo (tampil hanya di mobile) */
        .mobile-logo {
            display: none;
            flex-direction: column;
            align-items: center;
            gap: .5rem;
            margin-bottom: 2rem;
        }

        .mobile-logo img {
            height: 48px;
            width: auto;
        }

        .mobile-logo-name {
            font-size: .78rem;
            font-weight: 800;
            color: var(--text);
        }

        .mobile-logo-div {
            font-size: .56rem;
            font-weight: 600;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--blue-md);
        }

        /* Form slot content overrides */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select,
        textarea {
            background: var(--off) !important;
            border: 1.5px solid var(--border) !important;
            color: var(--text) !important;
            border-radius: 10px !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: .875rem !important;
            transition: border-color .2s, box-shadow .2s, background .2s !important;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus,
        textarea:focus {
            outline: none !important;
            border-color: var(--blue-md) !important;
            box-shadow: 0 0 0 3.5px rgba(36, 80, 181, .12) !important;
            background: var(--white) !important;
        }

        input::placeholder {
            color: var(--muted) !important;
            opacity: .6 !important;
        }

        label {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: .74rem !important;
            font-weight: 700 !important;
            letter-spacing: .04em !important;
            color: var(--text) !important;
        }

        /* Primary button */
        button[type="submit"],
        input[type="submit"] {
            background: linear-gradient(135deg, var(--blue-md) 0%, var(--blue) 100%) !important;
            color: var(--white) !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 800 !important;
            font-size: .92rem !important;
            letter-spacing: .03em !important;
            border: none !important;
            border-radius: 11px !important;
            box-shadow: 0 6px 22px rgba(26, 59, 140, .32), 0 2px 6px rgba(26, 59, 140, .16) !important;
            transition: transform .2s, box-shadow .2s !important;
            position: relative;
            overflow: hidden !important;
        }

        button[type="submit"]::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(245, 194, 0, .1), transparent);
            pointer-events: none;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 30px rgba(26, 59, 140, .42), 0 4px 10px rgba(26, 59, 140, .2) !important;
        }

        /* Links */
        a {
            color: var(--blue-md) !important;
            transition: color .18s !important;
        }

        a:hover {
            color: var(--blue) !important;
        }

        /* Checkbox */
        input[type="checkbox"] {
            accent-color: var(--blue-md) !important;
        }

        /* Error messages */
        .text-red-600,
        .text-red-500,
        [class*="text-red"] {
            color: var(--red) !important;
        }

        /* ══════════════════════════════════════
           ANIMATIONS
        ══════════════════════════════════════ */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(22px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-enter-slide {
            animation: slideInRight 0.65s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .page-enter-fade {
            animation: fadeInScale 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
            animation-delay: 0.15s;
            /* Sedikit dijeda agar panel kiri masuk duluan */
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.97) translateY(15px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* ══════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════ */
        @media (max-width: 860px) {
            .page-grid {
                grid-template-columns: 1fr;
            }

            .left-panel {
                display: none;
            }

            .right-panel {
                padding: 4rem 1.75rem 3rem;
                min-height: 100vh;
            }

            .mobile-logo {
                display: flex;
            }

            .top-stripe {
                display: block;
            }
        }
    </style>
</head>

<body>
    <canvas id="bg-canvas"></canvas>
    <div class="top-stripe"></div>

    <div class="page-grid">

        {{-- ══ PANEL KIRI ══ --}}
        <div class="left-panel page-enter-slide">
            <div class="lp-blob lp-blob--1"></div>
            <div class="lp-blob lp-blob--2"></div>

            {{-- Brand --}}
            <a href="/" class="lp-brand" wire:navigate>
                <div class="lp-logo">
                    <img src="{{ asset('logo.webp') }}" alt="Logo">
                </div>
                <div class="lp-brand-text">
                    <span class="lp-app-name">{{ config('app.name', 'Finance Portal') }}</span>
                    <span class="lp-app-div">Finance &amp; Accounting Division</span>
                </div>
            </a>

            {{-- Hero --}}
            <div class="lp-hero">
                <div class="lp-pill">
                    <span class="lp-pill-dot"></span>
                    <span>Sistem Pengajuan Pengeluaran</span>
                </div>

                <div>
                    <h2 class="lp-title">
                        Pengajuan <span class="t-yellow">Bukti</span><br>
                        Pengeluaran yang<br>
                        <span class="t-red">Transparan</span>
                    </h2>
                    <p class="lp-desc" style="margin-top:.9rem;">
                        Platform digital terintegrasi untuk pengelolaan reimbursement, bukti kas, dan laporan
                        pengeluaran divisi secara efisien dan tertelusuri.
                    </p>
                </div>

                <div class="lp-features">
                    <div class="lp-feat">
                        <div class="lp-feat-icon r">🧾</div>
                        <div>
                            <span class="lp-feat-text">Pengajuan Bukti Pengeluaran</span>
                            <span class="lp-feat-sub">Upload struk, invoice, dan nota resmi</span>
                        </div>
                    </div>
                    <div class="lp-feat">
                        <div class="lp-feat-icon y">📊</div>
                        <div>
                            <span class="lp-feat-text">Monitoring Anggaran Real-time</span>
                            <span class="lp-feat-sub">Pantau realisasi vs alokasi divisi</span>
                        </div>
                    </div>
                    <div class="lp-feat">
                        <div class="lp-feat-icon b">✅</div>
                        <div>
                            <span class="lp-feat-text">Approval Multi-Level</span>
                            <span class="lp-feat-sub">Alur persetujuan bertingkat otomatis</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="lp-footer">
                <p>Sistem Keuangan Terintegrasi</p>
                <p>Finance &amp; Accounting &copy; {{ date('Y') }}</p>
            </div>
        </div>

        {{-- ══ PANEL KANAN — form ══ --}}
        <div class="right-panel page-enter-fade">
            <div class="form-wrap">

                {{-- Mobile only logo --}}
                <div class="mobile-logo">
                    <img src="{{ asset('logo.webp') }}" alt="Logo">
                    <span class="mobile-logo-name">{{ config('app.name', 'Finance Portal') }}</span>
                    <span class="mobile-logo-div">Finance &amp; Accounting Division</span>
                </div>

                {{-- Blade slot: konten form login/register --}}
                {{ $slot }}

            </div>
        </div>

    </div>

    <script>
        (() => {
            /* Canvas particles — identik dengan welcome */
            const canvas = document.getElementById('bg-canvas');
            const ctx = canvas.getContext('2d');
            const TOKENS = ['IDR', 'Rp', '%', '∑', '✓', '↑', '→', 'Invoice', 'Nota', 'SPJ', 'BKU', 'RAB', 'Q4', 'APB',
                'SKU'
            ];
            const COLS = [
                [26, 59, 140],
                [208, 34, 42],
                [201, 154, 0]
            ];
            let W, H, pts = [];

            function resize() {
                W = canvas.width = window.innerWidth;
                H = canvas.height = window.innerHeight;
            }
            resize();
            window.addEventListener('resize', () => {
                resize();
                spawn();
            });

            class P {
                constructor() {
                    this.reset(true);
                }
                reset(init) {
                    this.x = Math.random() * W;
                    this.y = init ? Math.random() * H : H + 20;
                    this.vy = -(0.08 + Math.random() * .18);
                    this.vx = (Math.random() - .5) * .07;
                    this.sz = 7 + Math.random() * 8;
                    this.maxA = .04 + Math.random() * .03;
                    this.a = 0;
                    this.text = TOKENS[Math.floor(Math.random() * TOKENS.length)];
                    this.col = COLS[Math.floor(Math.random() * COLS.length)];
                    this.life = 0;
                    this.maxL = 400 + Math.random() * 350;
                }
                tick() {
                    this.x += this.vx;
                    this.y += this.vy;
                    this.life++;
                    const t = this.life / this.maxL;
                    this.a = t < .1 ? (t / .1) * this.maxA : t > .8 ? ((1 - t) / .2) * this.maxA : this.maxA;
                    if (this.life > this.maxL) this.reset();
                }
                draw() {
                    ctx.save();
                    ctx.globalAlpha = this.a;
                    ctx.font = `600 ${this.sz}px 'Plus Jakarta Sans', sans-serif`;
                    const [r, g, b] = this.col;
                    ctx.fillStyle = `rgba(${r},${g},${b},1)`;
                    ctx.fillText(this.text, this.x, this.y);
                    ctx.restore();
                }
            }

            function spawn() {
                pts = Array.from({
                    length: Math.max(16, Math.floor(W * H / 28000))
                }, () => new P());
            }
            spawn();

            (function loop() {
                ctx.clearRect(0, 0, W, H);
                pts.forEach(p => {
                    p.tick();
                    p.draw();
                });
                requestAnimationFrame(loop);
            })();
        })();
    </script>
</body>

</html>
