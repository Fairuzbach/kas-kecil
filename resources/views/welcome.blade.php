<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Finance Portal') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,500;0,600;0,700;1,500&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ══════════════════════════════════════
           DESIGN TOKENS
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

        /* ── CANVAS ── */
        #bg-canvas {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        /* ══════════════════════════════════════
           TOP ACCENT STRIPE
        ══════════════════════════════════════ */
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
           NAVBAR
        ══════════════════════════════════════ */
        .navbar {
            position: fixed;
            top: 3px;
            left: 0;
            right: 0;
            z-index: 100;
            height: 68px;
            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(221, 228, 240, .8);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 3rem;
            box-shadow: 0 1px 0 rgba(221, 228, 240, .6), 0 2px 12px rgba(15, 28, 46, .04);
            animation: slideDown .7s cubic-bezier(.22, 1, .36, 1) both;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: .9rem;
            text-decoration: none !important;
        }

        .nav-logo-wrap {
            position: relative;
            width: 42px;
            height: 42px;
            border-radius: 11px;
            background: var(--white);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .nav-logo-wrap img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .nav-brand-info {
            display: flex;
            flex-direction: column;
            gap: .05rem;
        }

        .nav-app-name {
            font-size: .9rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -.01em;
            line-height: 1.2;
        }

        .nav-app-div {
            font-size: .56rem;
            font-weight: 600;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--blue-md);
        }

        /* Nav divider */
        .nav-divider {
            height: 28px;
            width: 1px;
            background: var(--border);
            margin: 0 1.2rem;
        }

        .nav-tagline {
            font-size: .72rem;
            color: var(--muted);
            font-style: italic;
            letter-spacing: .01em;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        /* Login button — large & prominent */
        .btn-nav-login {
            display: inline-flex;
            align-items: center;
            gap: .65rem;
            padding: .82rem 2.2rem;
            background: linear-gradient(135deg, var(--blue-md) 0%, var(--blue) 100%);
            color: var(--white) !important;
            border-radius: 12px;
            font-size: .95rem;
            font-weight: 800;
            text-decoration: none !important;
            letter-spacing: .02em;
            box-shadow: 0 6px 22px rgba(26, 59, 140, .35), 0 2px 6px rgba(26, 59, 140, .18);
            transition: transform .18s, box-shadow .18s;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-nav-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(245, 194, 0, .12), transparent);
            pointer-events: none;
        }

        .btn-nav-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(26, 59, 140, .45), 0 3px 8px rgba(26, 59, 140, .22);
        }

        .btn-nav-login svg {
            transition: transform .18s;
        }

        .btn-nav-login:hover svg {
            transform: translateX(4px);
        }

        .btn-nav-dashboard {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .58rem 1.5rem;
            background: var(--blue-lt);
            color: var(--blue) !important;
            border: 1.5px solid rgba(36, 80, 181, .2);
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 700;
            text-decoration: none !important;
            transition: background .18s, border-color .18s;
        }

        .btn-nav-dashboard:hover {
            background: #dce6f8 !important;
            border-color: rgba(36, 80, 181, .4) !important;
        }

        /* ══════════════════════════════════════
           HERO — TWO COLUMN
        ══════════════════════════════════════ */
        .hero-wrap {
            position: relative;
            z-index: 1;
            max-width: 1240px;
            margin: 0 auto;
            padding: 130px 3rem 4rem;
            display: grid;
            grid-template-columns: 1fr 1.05fr;
            gap: 5rem;
            align-items: center;
        }

        /* LEFT */
        .hero-left {
            animation: fadeUp .85s cubic-bezier(.22, 1, .36, 1) .1s both;
        }

        .hero-pill {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 99px;
            padding: .35rem 1rem .35rem .55rem;
            margin-bottom: 1.6rem;
            box-shadow: var(--shadow-sm);
        }

        .hero-pill-dot {
            width: 22px;
            height: 22px;
            border-radius: 99px;
            background: linear-gradient(135deg, var(--blue-md), var(--blue));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .55rem;
            color: #fff;
            font-weight: 800;
        }

        .hero-pill span {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--blue-md);
        }

        .hero-title {
            font-family: 'Lora', serif;
            font-size: 3.1rem;
            font-weight: 700;
            line-height: 1.18;
            letter-spacing: -.02em;
            color: var(--text);
            margin-bottom: 1.3rem;
        }

        .hero-title .t-red {
            color: var(--red);
        }

        .hero-title .t-blue {
            color: var(--blue-md);
        }

        .hero-title .t-acc {
            position: relative;
            display: inline-block;
            color: var(--blue);
        }

        .hero-title .t-acc::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -3px;
            right: 0;
            height: 3px;
            background: var(--yellow);
            border-radius: 2px;
        }

        .hero-desc {
            font-size: .9rem;
            line-height: 1.8;
            color: var(--muted);
            max-width: 450px;
            margin-bottom: 2.2rem;
        }

        /* CTA block */
        .hero-cta {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            flex-wrap: wrap;
        }

        .btn-hero-login {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            padding: 1.1rem 2.8rem;
            background: linear-gradient(135deg, var(--blue-md) 0%, var(--blue) 100%);
            color: var(--white) !important;
            border-radius: 14px;
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: .02em;
            text-decoration: none !important;
            box-shadow: 0 8px 28px rgba(26, 59, 140, .36), 0 3px 8px rgba(26, 59, 140, .18);
            transition: transform .2s, box-shadow .2s;
            position: relative;
            overflow: hidden;
        }

        .btn-hero-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(245, 194, 0, .14), transparent);
            pointer-events: none;
        }

        .btn-hero-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 14px 38px rgba(26, 59, 140, .45), 0 5px 14px rgba(26, 59, 140, .22);
        }

        .btn-hero-login svg {
            transition: transform .2s;
        }

        .btn-hero-login:hover svg {
            transform: translateX(5px);
        }

        .hero-trust {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .72rem;
            color: var(--muted);
        }

        .hero-trust svg {
            color: var(--green);
            flex-shrink: 0;
        }

        /* Stats row */
        .hero-stats {
            display: flex;
            gap: 0;
            margin-top: 2.8rem;
            padding-top: 2.4rem;
            border-top: 1px solid var(--border);
        }

        .hstat {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: .28rem;
            padding-right: 2rem;
            position: relative;
        }

        .hstat+.hstat {
            padding-left: 2rem;
            padding-right: 2rem;
            border-left: 1px solid var(--border);
        }

        .hstat:last-child {
            padding-right: 0;
        }

        .hstat-val {
            font-family: 'Lora', serif;
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1;
        }

        .hstat-val.r {
            color: var(--red);
        }

        .hstat-val.b {
            color: var(--blue-md);
        }

        .hstat-val.y {
            color: var(--yellow-dk);
        }

        .hstat-lbl {
            font-size: .62rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
        }

        /* RIGHT — document panel */
        .hero-right {
            animation: fadeUp .85s cubic-bezier(.22, 1, .36, 1) .22s both;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        /* ── Flow card (alur pengajuan) ── */
        .flow-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.5rem 1.6rem;
            box-shadow: var(--shadow-md);
        }

        .flow-card-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.3rem;
        }

        .flow-card-title {
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text);
        }

        .flow-card-sub {
            font-size: .62rem;
            color: var(--muted);
            background: var(--off);
            border: 1px solid var(--border);
            padding: .2rem .7rem;
            border-radius: 99px;
        }

        .flow-steps {
            display: flex;
            flex-direction: column;
        }

        .flow-step {
            display: flex;
            align-items: center;
            gap: .9rem;
            padding: .65rem .6rem;
            border-radius: 11px;
            transition: background .18s;
        }

        .flow-step:hover {
            background: var(--off);
        }

        .flow-step--done .flow-step-title {
            color: var(--green);
        }

        .flow-step-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .flow-step-body {
            flex: 1;
        }

        .flow-step-title {
            font-size: .8rem;
            font-weight: 700;
            color: var(--text);
            display: block;
            line-height: 1.2;
        }

        .flow-step-desc {
            font-size: .65rem;
            color: var(--muted);
            margin-top: .12rem;
            display: block;
        }

        .flow-step-num {
            font-size: .62rem;
            font-weight: 800;
            letter-spacing: .08em;
            color: var(--muted);
            flex-shrink: 0;
            width: 22px;
            text-align: right;
        }

        .flow-connector {
            padding: 0 .6rem;
            height: 16px;
            display: flex;
            align-items: center;
        }

        .flow-connector-line {
            width: 1.5px;
            height: 100%;
            margin-left: 18px;
            background: linear-gradient(to bottom, var(--border), transparent);
        }

        /* ── Highlights row ── */
        .highlights-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .85rem;
        }

        .highlight-item {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: .65rem;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .2s, transform .2s;
        }

        .highlight-item:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .highlight-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .highlight-title {
            font-size: .77rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }

        .highlight-sub {
            font-size: .62rem;
            color: var(--muted);
            margin-top: .1rem;
        }

        .up {
            color: var(--green);
        }

        .dn {
            color: var(--red);
        }

        /* ══════════════════════════════════════
           FEATURES SECTION
        ══════════════════════════════════════ */
        .features {
            position: relative;
            z-index: 1;
            max-width: 1240px;
            margin: 0 auto;
            padding: 1rem 3rem 6rem;
        }

        .section-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 2.5rem;
        }

        .section-eyebrow {
            font-size: .62rem;
            font-weight: 800;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--red);
            margin-bottom: .5rem;
        }

        .section-title {
            font-family: 'Lora', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.28;
        }

        .section-sub {
            font-size: .78rem;
            color: var(--muted);
            max-width: 340px;
            text-align: right;
            line-height: 1.6;
        }

        .feat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }

        .feat-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.8rem;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .22s, transform .22s, border-color .22s;
            position: relative;
            overflow: hidden;
        }

        .feat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: 18px 18px 0 0;
            transition: height .22s;
        }

        .feat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .feat-card:hover::before {
            height: 4px;
        }

        .feat-card.fc-r::before {
            background: var(--red);
        }

        .feat-card.fc-y::before {
            background: var(--yellow);
        }

        .feat-card.fc-b::before {
            background: var(--blue-md);
        }

        .feat-card.fc-r:hover {
            border-color: rgba(208, 34, 42, .25);
        }

        .feat-card.fc-y:hover {
            border-color: rgba(245, 194, 0, .35);
        }

        .feat-card.fc-b:hover {
            border-color: rgba(36, 80, 181, .25);
        }

        .feat-icon-wrap {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .feat-icon-wrap.r {
            background: var(--red-lt);
        }

        .feat-icon-wrap.y {
            background: var(--yellow-lt);
        }

        .feat-icon-wrap.b {
            background: var(--blue-lt);
        }

        .feat-card h3 {
            font-size: .95rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: .55rem;
            letter-spacing: -.01em;
        }

        .feat-card p {
            font-size: .79rem;
            color: var(--muted);
            line-height: 1.7;
        }

        .feat-arrow {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            margin-top: 1.3rem;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-decoration: none !important;
            transition: gap .2s;
        }

        .feat-arrow:hover {
            gap: .6rem !important;
        }

        .feat-arrow.r {
            color: var(--red) !important;
        }

        .feat-arrow.y {
            color: var(--yellow-dk) !important;
        }

        .feat-arrow.b {
            color: var(--blue-md) !important;
        }

        /* Wide process card */
        .feat-card-wide {
            grid-column: span 3;
            background: linear-gradient(130deg, var(--blue) 0%, #0C2255 100%);
            border: none;
            color: #fff;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
            padding: 2.2rem 2.5rem;
        }

        .feat-card-wide h3 {
            color: #fff;
            font-size: 1.15rem;
            margin-bottom: .6rem;
        }

        .feat-card-wide p {
            color: rgba(255, 255, 255, .65);
            font-size: .82rem;
            line-height: 1.7;
        }

        .feat-card-wide::before {
            background: var(--yellow);
            height: 3px;
        }

        .feat-card-wide .feat-arrow {
            color: var(--yellow) !important;
        }

        /* Steps */
        .steps-col {
            display: flex;
            flex-direction: column;
            gap: .7rem;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 12px;
            padding: .8rem 1.1rem;
            transition: background .18s;
        }

        .step-item:hover {
            background: rgba(255, 255, 255, .12);
        }

        .step-num {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .68rem;
            font-weight: 900;
            flex-shrink: 0;
        }

        .step-num.r {
            background: var(--red);
            color: #fff;
        }

        .step-num.y {
            background: var(--yellow);
            color: var(--text);
        }

        .step-num.b {
            background: rgba(255, 255, 255, .18);
            color: #fff;
        }

        .step-num.g {
            background: rgba(22, 121, 76, .8);
            color: #fff;
        }

        .step-text {
            font-size: .78rem;
            color: rgba(255, 255, 255, .82);
            line-height: 1.4;
        }

        /* ══════════════════════════════════════
           FOOTER
        ══════════════════════════════════════ */
        .footer-stripe {
            height: 3px;
            background: linear-gradient(90deg, var(--blue) 0%, var(--red) 45%, var(--yellow) 100%);
            position: relative;
            z-index: 1;
        }

        .site-footer {
            position: relative;
            z-index: 1;
            background: var(--white);
            border-top: 1px solid var(--border);
            padding: 1.4rem 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .site-footer p {
            font-size: .7rem;
            color: var(--muted);
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .footer-brand img {
            height: 22px;
            opacity: .7;
        }

        /* ══════════════════════════════════════
           ANIMATIONS
        ══════════════════════════════════════ */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ══════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════ */
        @media (max-width: 1024px) {
            .hero-wrap {
                grid-template-columns: 1fr;
                gap: 2.5rem;
                padding: 120px 2rem 3rem;
            }

            .hero-right {
                display: none;
            }

            .hero-title {
                font-size: 2.4rem;
            }

            .feat-grid {
                grid-template-columns: 1fr 1fr;
            }

            .feat-card-wide {
                grid-column: span 2;
                grid-template-columns: 1fr;
            }

            .steps-col {
                display: none;
            }

            .section-head {
                flex-direction: column;
                align-items: flex-start;
                gap: .5rem;
            }

            .section-sub {
                text-align: left;
            }
        }

        @media (max-width: 640px) {
            .navbar {
                padding: 0 1.25rem;
            }

            .nav-divider,
            .nav-tagline {
                display: none;
            }

            .hero-wrap {
                padding: 110px 1.25rem 2.5rem;
            }

            .features {
                padding: 1rem 1.25rem 4rem;
            }

            .feat-grid {
                grid-template-columns: 1fr;
            }

            .feat-card-wide {
                grid-column: span 1;
            }

            .hero-stats {
                flex-direction: column;
                gap: 1.2rem;
            }

            .hstat+.hstat {
                border-left: none;
                padding-left: 0;
                border-top: 1px solid var(--border);
                padding-top: 1.2rem;
            }

            .site-footer {
                flex-direction: column;
                gap: .5rem;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    @php
        $formatCurrency = function ($amount) {
            if ($amount >= 1_000_000_000) {
                return 'IDR ' . round($amount / 1_000_000_000, 1) . 'B';
            }
            if ($amount >= 1_000_000) {
                return 'IDR ' . round($amount / 1_000_000, 1) . 'M';
            }
            return 'IDR ' . number_format($amount, 0, ',', '.');
        };
    @endphp

    <canvas id="bg-canvas"></canvas>
    <div class="top-stripe"></div>

    {{-- ══ NAVBAR ══ --}}
    <nav class="navbar">
        <a href="/" class="nav-brand" wire:navigate>
            <div class="nav-logo-wrap">
                <img src="{{ asset('logo.webp') }}" alt="Logo">
            </div>
            <div class="nav-brand-info">
                <span class="nav-app-name">{{ config('app.name', 'Finance Portal') }}</span>
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

    {{-- ══ HERO ══ --}}
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
                <span class="t-acc">Transparan</span> &amp; Efisien
            </h1>

            <p class="hero-desc">
                Platform digital terintegrasi untuk pengelolaan reimbursement, bukti kas, dan laporan pengeluaran divisi
                — terstruktur, tertelusuri, dan terintegrasi dengan sistem akuntansi perusahaan.
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

        {{-- RIGHT — Process illustration (no confidential data) --}}
        <div class="hero-right">

            {{-- Alur pengajuan visual --}}
            <div class="flow-card">
                <div class="flow-card-hdr">
                    <span class="flow-card-title">Alur Pengajuan</span>
                    <span class="flow-card-sub">4 langkah mudah</span>
                </div>

                <div class="flow-steps">
                    <div class="flow-step">
                        <div class="flow-step-icon" style="background:var(--red-lt); color:var(--red);">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
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
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
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
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
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
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
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

            {{-- Feature highlights --}}
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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="var(--yellow-dk)" stroke-width="2.5">
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
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="var(--blue-md)" stroke-width="2.5">
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

    {{-- ══ FEATURES ══ --}}
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
                <p>Upload struk, invoice, dan nota resmi secara digital. Proses pengajuan lebih cepat tanpa dokumen
                    kertas.</p>
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
                        <span class="step-text" style="color:rgba(245,194,0,.9);">Dana diproses &amp; otomatis
                            tercatat dalam sistem akuntansi</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ FOOTER ══ --}}
    <div class="footer-stripe"></div>
    <footer class="site-footer">
        <div class="footer-brand">
            <img src="{{ asset('logo.webp') }}" alt="Logo">
            <p>{{ config('app.name', 'Finance Portal') }} &copy; {{ date('Y') }} — Fairuz Bachri</p>
        </div>
        <p>Sistem Pengajuan Bukti Pengeluaran &nbsp;·&nbsp; v1.0</p>
    </footer>

    <script>
        (() => {
            const canvas = document.getElementById('bg-canvas');
            const ctx = canvas.getContext('2d');
            const TOKENS = ['IDR', 'Rp', '%', '∑', '✓', '↑', '→', 'Invoice', 'Nota', 'SPJ', 'BKU', 'RAB', 'Q4', 'APB',
                'SKU'
            ];
            const COLS = [
                [26, 59, 140],
                [208, 34, 42],
                [201, 154, 0],
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
