{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NFER-EHVS — National Employment History Verification System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Syne (display) + Plus Jakarta Sans (body) -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ─── CSS Variables ─── */
        :root {
            --navy:       #091422;
            --navy-mid:   #0e2039;
            --navy-soft:  #152a48;
            --gold:       #d4943a;
            --gold-light: #e8b96b;
            --gold-pale:  rgba(212,148,58,0.12);
            --white:      #ffffff;
            --off-white:  #f6f8fc;
            --muted:      #8094ae;
            --border:     rgba(255,255,255,0.08);
            --radius-sm:  10px;
            --radius-md:  16px;
            --radius-lg:  24px;
        }

        /* ─── Base ─── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            background: var(--off-white);
            color: #1a2e45;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5 {
            font-family: 'Syne', sans-serif;
        }

        /* ─── Navbar ─── */
        .site-nav {
            background: rgba(9, 20, 34, 0.92);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
            padding: 0;
        }

        .site-nav .container {
            height: 68px;
            display: flex;
            align-items: center;
        }

        .nav-brand {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: var(--white) !important;
            letter-spacing: -0.3px;
            text-decoration: none;
        }

        .nav-brand span {
            color: var(--gold);
        }

        .site-nav .nav-link {
            color: rgba(255,255,255,0.65) !important;
            font-size: 14px;
            font-weight: 500;
            padding: 0.4rem 1rem !important;
            border-radius: 6px;
            transition: color 0.2s, background 0.2s;
        }

        .site-nav .nav-link:hover {
            color: var(--white) !important;
            background: rgba(255,255,255,0.06);
        }

        .btn-nav-outline {
            border: 1px solid rgba(255,255,255,0.25);
            color: var(--white) !important;
            font-size: 14px;
            font-weight: 500;
            padding: 7px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .btn-nav-outline:hover {
            border-color: var(--gold);
            background: var(--gold-pale);
        }

        .btn-nav-solid {
            background: var(--gold);
            color: var(--navy) !important;
            font-size: 14px;
            font-weight: 600;
            padding: 7px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-nav-solid:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
        }

        /* ─── Hero ─── */
        .hero {
            min-height: 100vh;
            background: var(--navy);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        /* Dot grid pattern */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
            background-size: 36px 36px;
        }

        /* Gold gradient orb */
        .hero::after {
            content: '';
            position: absolute;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212,148,58,0.18) 0%, transparent 70%);
            top: -200px;
            right: -100px;
            pointer-events: none;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--gold);
            background: var(--gold-pale);
            border: 1px solid rgba(212,148,58,0.3);
            border-radius: 50px;
            padding: 6px 14px;
            margin-bottom: 28px;
        }

        .hero-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(40px, 5.5vw, 68px);
            font-weight: 800;
            line-height: 1.08;
            color: var(--white);
            letter-spacing: -1.5px;
            margin-bottom: 24px;
        }

        .hero-title .accent {
            color: var(--gold);
        }

        .hero-desc {
            font-size: 17px;
            color: rgba(255,255,255,0.55);
            line-height: 1.75;
            max-width: 500px;
            margin-bottom: 40px;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gold);
            color: var(--navy);
            font-weight: 600;
            font-size: 15px;
            padding: 14px 28px;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }

        .btn-hero-primary:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(212,148,58,0.35);
            color: var(--navy);
        }

        .btn-hero-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: transparent;
            color: rgba(255,255,255,0.75);
            font-weight: 500;
            font-size: 15px;
            padding: 14px 28px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.18);
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }

        .btn-hero-ghost:hover {
            border-color: rgba(255,255,255,0.4);
            color: var(--white);
            background: rgba(255,255,255,0.05);
        }

        /* ─── Verify Card ─── */
        .verify-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: var(--radius-lg);
            padding: 36px;
            position: relative;
        }

        .verify-card-header {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 28px;
        }

        .verify-card-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: var(--gold-pale);
            border: 1px solid rgba(212,148,58,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: var(--gold);
        }

        .verify-card-title {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--white);
            margin: 0;
        }

        .verify-card-sub {
            font-size: 12px;
            color: var(--muted);
            margin: 0;
        }

        .verify-card label {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
            display: block;
            margin-bottom: 8px;
        }

        .verify-card input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 15px;
            color: var(--white);
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: border-color 0.2s, background 0.2s;
            outline: none;
        }

        .verify-card input::placeholder { color: rgba(255,255,255,0.25); }
        .verify-card input:focus {
            border-color: var(--gold);
            background: rgba(212,148,58,0.07);
        }

        .btn-verify {
            width: 100%;
            background: var(--gold);
            color: var(--navy);
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-verify:hover {
            background: var(--gold-light);
            transform: translateY(-1px);
        }

        .verify-note {
            text-align: center;
            font-size: 12px;
            color: rgba(255,255,255,0.3);
            margin-top: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* Trusted-by strip */
        .trusted-strip {
            background: var(--navy-mid);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 18px 0;
        }

        .trusted-strip-inner {
            display: flex;
            align-items: center;
            gap: 32px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .trusted-label {
            font-size: 11px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            font-weight: 600;
            white-space: nowrap;
        }

        .trusted-org {
            font-size: 13px;
            font-weight: 600;
            color: rgba(255,255,255,0.45);
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        /* ─── Stats ─── */
        .stats-section {
            background: var(--off-white);
            padding: 80px 0;
        }

        .stat-card {
            background: var(--white);
            border: 1px solid #e8edf5;
            border-radius: var(--radius-md);
            padding: 32px 28px;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 48px rgba(14, 32, 57, 0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
        }

        .stat-card.c-gold::before  { background: var(--gold); }
        .stat-card.c-blue::before  { background: #2563eb; }
        .stat-card.c-green::before { background: #16a34a; }
        .stat-card.c-slate::before { background: #475569; }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        .stat-card.c-gold .stat-icon  { background: rgba(212,148,58,0.12); color: var(--gold); }
        .stat-card.c-blue .stat-icon  { background: rgba(37,99,235,0.1);  color: #2563eb; }
        .stat-card.c-green .stat-icon { background: rgba(22,163,74,0.1);  color: #16a34a; }
        .stat-card.c-slate .stat-icon { background: rgba(71,85,105,0.1);  color: #475569; }

        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 40px;
            font-weight: 800;
            color: var(--navy);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--muted);
            font-weight: 500;
        }

        /* ─── Features ─── */
        .features-section {
            background: var(--white);
            padding: 100px 0;
        }

        .section-eyebrow {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 16px;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(30px, 3.5vw, 44px);
            font-weight: 800;
            color: var(--navy);
            line-height: 1.15;
            letter-spacing: -0.5px;
            margin-bottom: 0;
        }

        .section-desc {
            font-size: 16px;
            color: var(--muted);
            line-height: 1.7;
            max-width: 520px;
            margin: 16px auto 0;
        }

        .feature-card {
            background: var(--off-white);
            border: 1px solid #e8edf5;
            border-radius: var(--radius-md);
            padding: 32px;
            height: 100%;
            transition: transform 0.25s, box-shadow 0.25s, border-color 0.25s;
            position: relative;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 60px rgba(14, 32, 57, 0.1);
            border-color: rgba(212,148,58,0.35);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            background: var(--navy);
            color: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 24px;
        }

        .feature-card h4 {
            font-size: 18px;
            font-weight: 700;
            color: var(--navy);
            margin-bottom: 12px;
        }

        .feature-card p {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 0;
        }

        .feature-tag {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            background: var(--gold-pale);
            color: var(--gold);
            border: 1px solid rgba(212,148,58,0.25);
            padding: 4px 10px;
            border-radius: 50px;
        }

        /* ─── Timeline Section ─── */
        .timeline-section {
            background: var(--navy);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .timeline-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 36px 36px;
        }

        .timeline-section .section-title,
        .timeline-section .section-desc {
            color: var(--white);
        }

        .timeline-section .section-desc {
            color: rgba(255,255,255,0.5);
        }

        .timeline-track {
            position: relative;
            padding-left: 28px;
        }

        .timeline-track::before {
            content: '';
            position: absolute;
            left: 0;
            top: 6px;
            bottom: 6px;
            width: 2px;
            background: linear-gradient(to bottom, var(--gold), rgba(212,148,58,0.2));
        }

        .timeline-item {
            position: relative;
            padding-bottom: 36px;
        }

        .timeline-item:last-child { padding-bottom: 0; }

        .timeline-dot {
            position: absolute;
            left: -34px;
            top: 4px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid var(--gold);
            background: var(--navy);
        }

        .timeline-dot.active {
            background: var(--gold);
            box-shadow: 0 0 0 4px rgba(212,148,58,0.25);
        }

        .timeline-year {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 4px;
        }

        .timeline-role {
            font-family: 'Syne', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 2px;
        }

        .timeline-company {
            font-size: 14px;
            color: rgba(255,255,255,0.45);
        }

        .timeline-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 50px;
            margin-top: 8px;
        }

        .status-verified {
            background: rgba(22,163,74,0.15);
            color: #4ade80;
            border: 1px solid rgba(74,222,128,0.2);
        }

        .status-active {
            background: rgba(212,148,58,0.15);
            color: var(--gold-light);
            border: 1px solid rgba(212,148,58,0.3);
        }

        /* Verification steps card */
        .verify-steps-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: var(--radius-lg);
            padding: 36px;
        }

        .step-row {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .step-row:last-child { border-bottom: none; padding-bottom: 0; }
        .step-row:first-child { padding-top: 0; }

        .step-num {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--gold-pale);
            border: 1px solid rgba(212,148,58,0.3);
            color: var(--gold);
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-content h6 {
            font-size: 14px;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 2px;
        }

        .step-content p {
            font-size: 13px;
            color: rgba(255,255,255,0.4);
            margin: 0;
        }

        /* ─── Analytics ─── */
        .analytics-section {
            background: var(--off-white);
            padding: 100px 0;
        }

        .progress-item {
            margin-bottom: 20px;
        }

        .progress-item:last-child { margin-bottom: 0; }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .progress-name {
            font-size: 14px;
            font-weight: 500;
            color: #1a2e45;
        }

        .progress-pct {
            font-size: 14px;
            font-weight: 700;
            color: var(--navy);
            font-family: 'Syne', sans-serif;
        }

        .progress-bar-track {
            height: 8px;
            background: #e8edf5;
            border-radius: 50px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 50px;
            transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fill-blue   { background: linear-gradient(90deg, #2563eb, #60a5fa); }
        .fill-gold   { background: linear-gradient(90deg, var(--gold), var(--gold-light)); }
        .fill-red    { background: linear-gradient(90deg, #dc2626, #f87171); }

        .security-list-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 0;
            border-bottom: 1px solid #e8edf5;
        }

        .security-list-item:last-child { border-bottom: none; }

        .security-check {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(22,163,74,0.1);
            color: #16a34a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .security-text {
            font-size: 14px;
            font-weight: 500;
            color: #1a2e45;
        }

        /* ─── CTA ─── */
        .cta-section {
            background: var(--navy);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .cta-section::after {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212,148,58,0.15) 0%, transparent 70%);
            bottom: -200px;
            left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
        }

        .cta-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(30px, 4vw, 50px);
            font-weight: 800;
            color: var(--white);
            letter-spacing: -0.5px;
            margin-bottom: 16px;
        }

        .cta-desc {
            font-size: 16px;
            color: rgba(255,255,255,0.5);
            max-width: 480px;
            margin: 0 auto 40px;
            line-height: 1.7;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gold);
            color: var(--navy);
            font-weight: 600;
            font-size: 16px;
            padding: 16px 36px;
            border-radius: 12px;
            text-decoration: none;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
        }

        .btn-cta:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
            box-shadow: 0 16px 40px rgba(212,148,58,0.35);
            color: var(--navy);
        }

        /* ─── Footer ─── */
        .site-footer {
            background: #06101c;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 60px 0 32px;
        }

        .footer-brand {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 12px;
        }

        .footer-brand span { color: var(--gold); }

        .footer-tagline {
            font-size: 14px;
            color: rgba(255,255,255,0.4);
            line-height: 1.6;
            max-width: 280px;
        }

        .footer-heading {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            margin-bottom: 18px;
        }

        .footer-link {
            display: block;
            font-size: 14px;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            margin-bottom: 10px;
            transition: color 0.2s;
        }

        .footer-link:hover { color: var(--white); }

        .footer-divider {
            border-color: rgba(255,255,255,0.06);
            margin: 40px 0 24px;
        }

        .footer-copy {
            font-size: 13px;
            color: rgba(255,255,255,0.25);
        }

        /* ─── Utilities ─── */
        .divider-line {
            display: inline-block;
            width: 40px;
            height: 3px;
            background: var(--gold);
            border-radius: 2px;
            margin-bottom: 18px;
        }
    </style>
</head>

<body>

    <!-- ═══ NAVBAR ═══ -->
    <nav class="site-nav sticky-top">
        <div class="container">
            <a class="nav-brand me-auto" href="#">NFER<span>-EHVS</span></a>

            <button class="navbar-toggler border-0 ms-2" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMain"
                style="color:white;">
                <i class="bi bi-list fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#verification">Verification</a></li>
                    <li class="nav-item"><a class="nav-link" href="#analytics">Analytics</a></li>
                    <li class="nav-item ms-lg-2">
                        <a href="/login" class="btn-nav-outline">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="/register" class="btn-nav-solid">Get Started</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ═══ HERO ═══ -->
    <section class="hero">
        <div class="container position-relative" style="z-index:1;">
            <div class="row align-items-center g-5">

                <div class="col-lg-6">
                    <div class="hero-eyebrow">
                        <i class="bi bi-shield-check"></i>
                        Rwanda Workforce Registry
                    </div>

                    <h1 class="hero-title">
                        National <span class="accent">Employment</span><br>
                        History Verification<br>
                        System
                    </h1>

                    <p class="hero-desc">
                        Securely manage employee records, verify employment history,
                        resolve disputes, and empower employers and government
                        institutions with trusted workforce intelligence.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        <a href="/register" class="btn-hero-primary">
                            Get Started <i class="bi bi-arrow-right"></i>
                        </a>
                        <a href="/verify-nid" class="btn-hero-ghost">
                            <i class="bi bi-search"></i> Verify Employment
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="verify-card">
                        <div class="verify-card-header">
                            <div class="verify-card-icon">
                                <i class="bi bi-person-vcard"></i>
                            </div>
                            <div>
                                <p class="verify-card-title">Quick NID Verification</p>
                                <p class="verify-card-sub">Instant employment history lookup</p>
                            </div>
                        </div>

                        <form action="/verify-nid">
                            <div class="mb-3">
                                <label>National ID Number</label>
                                <input type="text" name="nid" placeholder="e.g. 1199880012345678">
                            </div>
                            <div class="mb-4">
                                <label>Full Name (optional)</label>
                                <input type="text" name="name" placeholder="First and Last name">
                            </div>
                            <button type="submit" class="btn-verify">
                                <i class="bi bi-search"></i>
                                Search Employment History
                            </button>
                        </form>

                        <p class="verify-note">
                            <i class="bi bi-lock-fill"></i>
                            Secured by 256-bit encryption · Government certified
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Trusted by strip -->
    <div class="trusted-strip">
        <div class="container">
            <div class="trusted-strip-inner">
                <span class="trusted-label">Trusted by</span>
                <span class="trusted-org">Ministry of Public Service</span>
                <span style="color:rgba(255,255,255,0.12);">·</span>
                <span class="trusted-org">Rwanda Development Board</span>
                <span style="color:rgba(255,255,255,0.12);">·</span>
                <span class="trusted-org">National Social Security Fund</span>
                <span style="color:rgba(255,255,255,0.12);">·</span>
                <span class="trusted-org">Rwanda Revenue Authority</span>
            </div>
        </div>
    </div>

    <!-- ═══ STATS ═══ -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stat-card c-gold">
                        <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-num" data-count="25000">0</div>
                        <div class="stat-label">Employees Registered</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card c-blue">
                        <div class="stat-icon"><i class="bi bi-building"></i></div>
                        <div class="stat-num" data-count="3200">0</div>
                        <div class="stat-label">Verified Employers</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card c-green">
                        <div class="stat-icon"><i class="bi bi-patch-check"></i></div>
                        <div class="stat-num" data-count="1250">0</div>
                        <div class="stat-label">Employment Verifications</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card c-slate">
                        <div class="stat-icon"><i class="bi bi-graph-up-arrow"></i></div>
                        <div class="stat-num" data-count="98">0</div>
                        <div class="stat-label">Verification Accuracy %</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ FEATURES ═══ -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-eyebrow">Platform Capabilities</span>
                <h2 class="section-title">Everything You Need for<br>Workforce Verification</h2>
                <p class="section-desc">Designed for employees, employers, and government institutions across Rwanda.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <span class="feature-tag">Core</span>
                        <div class="feature-icon"><i class="bi bi-person-badge"></i></div>
                        <h4>Employee Profiles</h4>
                        <p>Create and manage verified employee records with complete employment history, performance remarks, and real-time verification status.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <span class="feature-tag">Gov-Approved</span>
                        <div class="feature-icon"><i class="bi bi-building-check"></i></div>
                        <h4>Employer Verification</h4>
                        <p>Government-approved employer verification with comprehensive audit logs, activity tracking, and compliance reporting dashboards.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <span class="feature-tag">Insights</span>
                        <div class="feature-icon"><i class="bi bi-bar-chart-line"></i></div>
                        <h4>Analytics Dashboard</h4>
                        <p>Workforce insights, dispute monitoring, and national employment analytics — all in one intuitive government-grade interface.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-link-45deg"></i></div>
                        <h4>Blockchain Audit Trail</h4>
                        <p>Immutable, tamper-proof verification records anchored on a distributed ledger for maximum institutional trust.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-file-earmark-check"></i></div>
                        <h4>Certified Documents</h4>
                        <p>Issue government-certified employment certificates and verification letters accepted by all registered institutions.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
                        <h4>Dispute Resolution</h4>
                        <p>Structured dispute filing, review, and resolution workflows with government mediator oversight and audit logging.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ VERIFICATION / TIMELINE ═══ -->
    <section class="timeline-section" id="verification">
        <div class="container position-relative" style="z-index:1;">
            <div class="row align-items-start g-5">

                <div class="col-lg-5">
                    <span class="section-eyebrow">Employment Timeline</span>
                    <h2 class="section-title" style="color:white;">Verify Any Worker's<br>Full Employment History</h2>
                    <p class="section-desc">Employers get instant, trusted access to verified employment records using National ID — no manual calls, no forged references.</p>

                    <div class="timeline-track mt-5">
                        <div class="timeline-item">
                            <div class="timeline-dot active"></div>
                            <div class="timeline-year">2025 — Current</div>
                            <div class="timeline-role">Operations Coordinator</div>
                            <div class="timeline-company">RwandaTech Solutions Ltd</div>
                            <span class="timeline-status status-active">
                                <i class="bi bi-circle-fill" style="font-size:7px;"></i> Active
                            </span>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-year">2024</div>
                            <div class="timeline-role">HR Assistant</div>
                            <div class="timeline-company">Grand Legacy Hotel</div>
                            <span class="timeline-status status-verified">
                                <i class="bi bi-patch-check-fill" style="font-size:11px;"></i> Verified
                            </span>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-year">2023</div>
                            <div class="timeline-role">Customer Support Officer</div>
                            <div class="timeline-company">PrideConnect Rwanda</div>
                            <span class="timeline-status status-verified">
                                <i class="bi bi-patch-check-fill" style="font-size:11px;"></i> Verified
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="verify-steps-card">
                        <p style="font-family:'Syne',sans-serif;font-size:16px;font-weight:700;color:white;margin-bottom:24px;">How Verification Works</p>

                        <div class="step-row">
                            <div class="step-num">01</div>
                            <div class="step-content">
                                <h6>Enter National ID</h6>
                                <p>Employer or institution inputs the worker's 16-digit NID number into the portal.</p>
                            </div>
                        </div>
                        <div class="step-row">
                            <div class="step-num">02</div>
                            <div class="step-content">
                                <h6>Authenticate Request</h6>
                                <p>The system validates requester credentials and logs the access for audit purposes.</p>
                            </div>
                        </div>
                        <div class="step-row">
                            <div class="step-num">03</div>
                            <div class="step-content">
                                <h6>Retrieve Verified History</h6>
                                <p>NFER-EHVS returns the complete employment timeline — verified by each previous employer.</p>
                            </div>
                        </div>
                        <div class="step-row">
                            <div class="step-num">04</div>
                            <div class="step-content">
                                <h6>Download Certificate</h6>
                                <p>Generate and download an official, government-signed verification certificate.</p>
                            </div>
                        </div>

                        <a href="/verify-nid" class="btn-verify mt-4" style="display:flex;max-width:300px;">
                            <i class="bi bi-search"></i> Try a Verification Now
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ═══ ANALYTICS ═══ -->
    <section class="analytics-section" id="analytics">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-eyebrow">Workforce Intelligence</span>
                <h2 class="section-title">Government Workforce Analytics</h2>
                <p class="section-desc">Real-time employment insights and verification intelligence for decision-makers.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 rounded-4 p-4 shadow-sm h-100">
                        <h5 class="fw-bold mb-4" style="font-family:'Syne',sans-serif;color:var(--navy);">Employment Distribution</h5>

                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-name">Active Employment</span>
                                <span class="progress-pct">75%</span>
                            </div>
                            <div class="progress-bar-track">
                                <div class="progress-bar-fill fill-blue" style="width:75%"></div>
                            </div>
                        </div>

                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-name">Pending Verification</span>
                                <span class="progress-pct">15%</span>
                            </div>
                            <div class="progress-bar-track">
                                <div class="progress-bar-fill fill-gold" style="width:15%"></div>
                            </div>
                        </div>

                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-name">Disputes / Flagged</span>
                                <span class="progress-pct">10%</span>
                            </div>
                            <div class="progress-bar-track">
                                <div class="progress-bar-fill fill-red" style="width:10%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border-0 rounded-4 p-4 shadow-sm h-100">
                        <h5 class="fw-bold mb-4" style="font-family:'Syne',sans-serif;color:var(--navy);">Security & Compliance</h5>

                        <div class="security-list-item">
                            <div class="security-check"><i class="bi bi-check-lg"></i></div>
                            <span class="security-text">Blockchain-ready immutable audit trail</span>
                        </div>
                        <div class="security-list-item">
                            <div class="security-check"><i class="bi bi-check-lg"></i></div>
                            <span class="security-text">Government employer verification & approval</span>
                        </div>
                        <div class="security-list-item">
                            <div class="security-check"><i class="bi bi-check-lg"></i></div>
                            <span class="security-text">Full activity tracking & access logs</span>
                        </div>
                        <div class="security-list-item">
                            <div class="security-check"><i class="bi bi-check-lg"></i></div>
                            <span class="security-text">Secure, government-signed certificates</span>
                        </div>
                        <div class="security-list-item">
                            <div class="security-check"><i class="bi bi-check-lg"></i></div>
                            <span class="security-text">256-bit SSL encryption on all data transfers</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ CTA ═══ -->
    <section class="cta-section">
        <div class="container text-center position-relative" style="z-index:1;">
            <div class="divider-line"></div>
            <h2 class="cta-title">Start Verifying Employment<br>Records Today</h2>
            <p class="cta-desc">Secure, trusted, and government-ready employment verification platform for modern workforce management in Rwanda.</p>
            <a href="/register" class="btn-cta">
                Create Free Account <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </section>

    <!-- ═══ FOOTER ═══ -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="footer-brand">NFER<span>-EHVS</span></div>
                    <p class="footer-tagline">National Employment History Verification System — powering trusted workforce intelligence across Rwanda.</p>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="footer-heading">Platform</div>
                    <a href="#features" class="footer-link">Features</a>
                    <a href="#verification" class="footer-link">Verification</a>
                    <a href="#analytics" class="footer-link">Analytics</a>
                    <a href="/register" class="footer-link">Get Started</a>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="footer-heading">Resources</div>
                    <a href="#" class="footer-link">Documentation</a>
                    <a href="#" class="footer-link">API Reference</a>
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Use</a>
                </div>
                <div class="col-lg-4">
                    <div class="footer-heading">Government Partners</div>
                    <p style="font-size:13px;color:rgba(255,255,255,0.35);line-height:1.7;">
                        Ministry of Public Service · Rwanda Development Board ·
                        National Social Security Fund · Rwanda Revenue Authority
                    </p>
                </div>
            </div>

            <hr class="footer-divider">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <p class="footer-copy mb-0">© 2026 NFER-EHVS. All rights reserved. Republic of Rwanda.</p>
                <p class="footer-copy mb-0">Built with <i class="bi bi-heart-fill" style="color:var(--gold);font-size:11px;"></i> for workforce transparency</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* ── Animated stat counters ── */
        const formatNum = (n, max) => {
            if (max >= 10000) return (n >= 1000 ? (n / 1000).toFixed(1) + 'K+' : n);
            if (max === 98) return n + '%';
            return n.toLocaleString() + '+';
        };

        const animateCounter = (el) => {
            const target = parseInt(el.dataset.count);
            const duration = 1800;
            const startTime = performance.now();
            const step = (now) => {
                const progress = Math.min((now - startTime) / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 4);
                const current = Math.round(ease * target);
                el.textContent = formatNum(current, target);
                if (progress < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    animateCounter(e.target);
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.4 });

        document.querySelectorAll('[data-count]').forEach(el => observer.observe(el));
    </script>

</body>
</html>