{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NFER-EHVS — Employment History Verification</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:    #0b1f3a;
            --gold:    #c8873a;
            --gold-lt: #e8b96b;
            --white:   #ffffff;
            --surface: #f8f9fc;
            --muted:   #6b7e99;
            --border:  #e3e8f0;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--white);
            color: var(--navy);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, .brand {
            font-family: 'Syne', sans-serif;
        }

        /* ── Nav ── */
        .nav-wrap {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(11,31,58,0.97);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .nav-inner {
            height: 62px;
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .brand {
            font-size: 18px;
            font-weight: 800;
            color: var(--white);
            text-decoration: none;
            letter-spacing: -0.2px;
        }

        .brand em { color: var(--gold); font-style: normal; }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-left: auto;
        }

        .nav-links a {
            font-size: 13.5px;
            font-weight: 500;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 6px;
            transition: color 0.18s, background 0.18s;
        }

        .nav-links a:hover { color: var(--white); background: rgba(255,255,255,0.07); }

        .btn-pill {
            font-size: 13.5px;
            font-weight: 600;
            padding: 7px 18px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.18s;
        }

        .btn-pill-outline {
            color: rgba(255,255,255,0.75);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .btn-pill-outline:hover { color: var(--white); border-color: rgba(255,255,255,0.5); }

        .btn-pill-gold {
            background: var(--gold);
            color: var(--navy);
        }

        .btn-pill-gold:hover { background: var(--gold-lt); color: var(--navy); }

        /* ── Hero ── */
        .hero {
            background: var(--navy);
            padding: 90px 0 80px;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 22px;
        }

        .hero-tag::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--gold);
        }

        .hero-title {
            font-size: clamp(36px, 4.5vw, 58px);
            font-weight: 800;
            color: var(--white);
            line-height: 1.1;
            letter-spacing: -1.2px;
            margin-bottom: 20px;
        }

        .hero-title span { color: var(--gold); }

        .hero-desc {
            font-size: 16px;
            color: rgba(255,255,255,0.5);
            line-height: 1.75;
            max-width: 440px;
            margin-bottom: 36px;
        }

        .btn-lg-gold {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gold);
            color: var(--navy);
            font-weight: 600;
            font-size: 14.5px;
            padding: 13px 26px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.18s;
        }

        .btn-lg-gold:hover { background: var(--gold-lt); color: var(--navy); transform: translateY(-1px); }

        .btn-lg-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.6);
            font-weight: 500;
            font-size: 14.5px;
            padding: 13px 26px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.15);
            text-decoration: none;
            transition: all 0.18s;
        }

        .btn-lg-ghost:hover { color: var(--white); border-color: rgba(255,255,255,0.35); }

        /* ── Verify Card ── */
        .v-card {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 18px;
            padding: 32px;
        }

        .v-card-head {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .v-card-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(200,135,58,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--gold);
        }

        .v-card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--white);
            margin: 0;
        }

        .v-card-sub {
            font-size: 12px;
            color: rgba(255,255,255,0.4);
            margin: 0;
        }

        .v-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-bottom: 7px;
        }

        .v-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 14px;
            color: var(--white);
            font-family: 'Plus Jakarta Sans', sans-serif;
            outline: none;
            transition: border-color 0.18s;
        }

        .v-input::placeholder { color: rgba(255,255,255,0.2); }
        .v-input:focus { border-color: var(--gold); }

        .btn-verify {
            width: 100%;
            background: var(--gold);
            color: var(--navy);
            border: none;
            border-radius: 8px;
            padding: 13px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            transition: background 0.18s;
        }

        .btn-verify:hover { background: var(--gold-lt); }

        .v-note {
            text-align: center;
            font-size: 11.5px;
            color: rgba(255,255,255,0.25);
            margin-top: 14px;
        }

        /* ── Partners strip ── */
        .partners {
            background: var(--surface);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 16px 0;
        }

        .partners-inner {
            display: flex;
            align-items: center;
            gap: 8px 28px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .p-label {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: #aab4c2;
        }

        .p-sep { color: var(--border); }

        .p-name {
            font-size: 13px;
            font-weight: 600;
            color: #8a97aa;
        }

        /* ── Features ── */
        .section { padding: 80px 0; }

        .eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            color: var(--gold);
            display: block;
            margin-bottom: 12px;
        }

        .section-h {
            font-size: clamp(26px, 3vw, 38px);
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.4px;
            line-height: 1.2;
            margin-bottom: 0;
        }

        .section-p {
            font-size: 15px;
            color: var(--muted);
            line-height: 1.7;
            margin-top: 12px;
        }

        .feat-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px 26px;
            height: 100%;
            transition: transform 0.22s, box-shadow 0.22s, border-color 0.22s;
        }

        .feat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 36px rgba(11,31,58,0.08);
            border-color: rgba(200,135,58,0.3);
        }

        .feat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--navy);
            color: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .feat-card h4 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--navy);
        }

        .feat-card p {
            font-size: 13.5px;
            color: var(--muted);
            line-height: 1.65;
            margin: 0;
        }

        .feat-badge {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: var(--gold);
            background: rgba(200,135,58,0.1);
            border: 1px solid rgba(200,135,58,0.2);
            padding: 3px 9px;
            border-radius: 50px;
            margin-bottom: 14px;
        }

        /* ── Steps ── */
        .steps-section { background: var(--navy); padding: 80px 0; }

        .steps-section .section-h { color: var(--white); }
        .steps-section .section-p  { color: rgba(255,255,255,0.45); }

        .step-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2px;
            background: rgba(255,255,255,0.06);
            border-radius: 14px;
            overflow: hidden;
        }

        .step-cell {
            background: var(--navy);
            padding: 28px 24px;
        }

        .step-num {
            font-family: 'Syne', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: rgba(200,135,58,0.3);
            line-height: 1;
            margin-bottom: 16px;
        }

        .step-cell h5 {
            font-size: 15px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 6px;
        }

        .step-cell p {
            font-size: 13px;
            color: rgba(255,255,255,0.4);
            margin: 0;
            line-height: 1.6;
        }

        /* ── Stats row ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .stat-cell {
            background: var(--white);
            padding: 28px 24px;
        }

        .stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: var(--navy);
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--muted);
        }

        /* ── CTA ── */
        .cta-section {
            background: var(--navy);
            padding: 80px 0;
            text-align: center;
        }

        .cta-section h2 {
            font-size: clamp(28px, 3.5vw, 44px);
            font-weight: 800;
            color: var(--white);
            letter-spacing: -0.5px;
            margin-bottom: 14px;
        }

        .cta-section p {
            font-size: 15px;
            color: rgba(255,255,255,0.45);
            max-width: 420px;
            margin: 0 auto 36px;
            line-height: 1.7;
        }

        /* ── Footer ── */
        .site-footer {
            background: #07111f;
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 52px 0 28px;
        }

        .footer-brand {
            font-size: 20px;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 10px;
        }

        .footer-brand em { color: var(--gold); font-style: normal; }

        .footer-tagline {
            font-size: 13px;
            color: rgba(255,255,255,0.35);
            line-height: 1.65;
            max-width: 260px;
        }

        .footer-col-head {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.25);
            margin-bottom: 16px;
        }

        .footer-link {
            display: block;
            font-size: 13.5px;
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            margin-bottom: 9px;
            transition: color 0.18s;
        }

        .footer-link:hover { color: var(--white); }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.06);
            margin-top: 40px;
            padding-top: 22px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .footer-copy {
            font-size: 12.5px;
            color: rgba(255,255,255,0.22);
        }

        @media (max-width: 768px) {
            .nav-links .hide-mob { display: none; }
            .stats-row { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav class="nav-wrap">
    <div class="container nav-inner">
        <a href="#" class="brand">NFER<em>-EHVS</em></a>

        <div class="nav-links">
            <a href="#features" class="hide-mob">Features</a>
            <a href="#how" class="hide-mob">How It Works</a>
            <a href="/login" class="btn-pill btn-pill-outline ms-2">Login</a>
            <a href="/register" class="btn-pill btn-pill-gold ms-2">Get Started</a>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-6">
                <div class="hero-tag">Rwanda Workforce Registry</div>
                <h1 class="hero-title">
                    National <span>Employment</span><br>
                    Verification System
                </h1>
                <p class="hero-desc">
                    Securely verify employment history, manage records,
                    and connect employers with trusted workforce intelligence — all in one platform.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="/register" class="btn-lg-gold">Get Started <i class="bi bi-arrow-right"></i></a>
                    <a href="/verify-nid" class="btn-lg-ghost"><i class="bi bi-search"></i> Verify Employment</a>
                </div>
            </div>

            <div class="col-lg-5 offset-lg-1">
                <div class="v-card">
                    <div class="v-card-head">
                        <div class="v-card-icon"><i class="bi bi-person-vcard"></i></div>
                        <div>
                            <p class="v-card-title">Quick NID Lookup</p>
                            <p class="v-card-sub">Instant employment history</p>
                        </div>
                    </div>

                    <form action="/verify-nid">
                        <div class="mb-3">
                            <label class="v-label">National ID Number</label>
                            <input class="v-input" type="text" name="nid" placeholder="e.g. 1199880012345678">
                        </div>
                        <div class="mb-4">
                            <label class="v-label">Full Name <span style="opacity:.5">(optional)</span></label>
                            <input class="v-input" type="text" name="name" placeholder="First and Last name">
                        </div>
                        <button type="submit" class="btn-verify">
                            <i class="bi bi-search"></i> Search Employment History
                        </button>
                    </form>

                    <p class="v-note"><i class="bi bi-lock-fill me-1"></i>256-bit encryption · Government certified</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- PARTNERS -->
<div class="partners">
    <div class="container partners-inner">
        <span class="p-label">Trusted by</span>
        <span class="p-name">Ministry of Public Service</span>
        <span class="p-sep">·</span>
        <span class="p-name">Rwanda Development Board</span>
        <span class="p-sep">·</span>
        <span class="p-name">National Social Security Fund</span>
        <span class="p-sep">·</span>
        <span class="p-name">Rwanda Revenue Authority</span>
    </div>
</div>

<!-- STATS -->
<section class="section" style="background: var(--surface); padding-top: 60px; padding-bottom: 60px;">
    <div class="container">
        <div class="stats-row">
            <div class="stat-cell">
                <div class="stat-num" data-count="25000">0</div>
                <div class="stat-label">Employees Registered</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num" data-count="3200">0</div>
                <div class="stat-label">Verified Employers</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num" data-count="1250">0</div>
                <div class="stat-label">Verifications Done</div>
            </div>
            <div class="stat-cell">
                <div class="stat-num" data-count="98">0</div>
                <div class="stat-label">Accuracy Rate</div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="section" id="features">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-5">
                <span class="eyebrow">Platform Capabilities</span>
                <h2 class="section-h">Everything for Workforce Verification</h2>
            </div>
            <div class="col-lg-5 offset-lg-1 d-flex align-items-end">
                <p class="section-p">Designed for employees, employers, and government institutions across Rwanda.</p>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="feat-card">
                    <span class="feat-badge">Core</span>
                    <div class="feat-icon"><i class="bi bi-person-badge"></i></div>
                    <h4>Employee Profiles</h4>
                    <p>Verified records with complete employment history, performance remarks, and real-time status.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feat-card">
                    <span class="feat-badge">Gov-Approved</span>
                    <div class="feat-icon"><i class="bi bi-building-check"></i></div>
                    <h4>Employer Verification</h4>
                    <p>Government-approved employer accounts with audit logs, activity tracking, and compliance reporting.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feat-card">
                    <span class="feat-badge">Insights</span>
                    <div class="feat-icon"><i class="bi bi-bar-chart-line"></i></div>
                    <h4>Analytics Dashboard</h4>
                    <p>Workforce insights and national employment analytics in one government-grade interface.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feat-card">
                    <div class="feat-icon"><i class="bi bi-link-45deg"></i></div>
                    <h4>Blockchain Audit Trail</h4>
                    <p>Immutable, tamper-proof records anchored on a distributed ledger for maximum trust.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feat-card">
                    <div class="feat-icon"><i class="bi bi-file-earmark-check"></i></div>
                    <h4>Certified Documents</h4>
                    <p>Issue government-certified employment certificates accepted by all registered institutions.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feat-card">
                    <div class="feat-icon"><i class="bi bi-shield-lock"></i></div>
                    <h4>Dispute Resolution</h4>
                    <p>Structured dispute filing and resolution workflows with government mediator oversight.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="steps-section" id="how">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-5">
                <span class="eyebrow" style="color:var(--gold);">How It Works</span>
                <h2 class="section-h">Verify Any Worker in 4 Steps</h2>
            </div>
            <div class="col-lg-5 offset-lg-1 d-flex align-items-end">
                <p class="section-p">Instant, trusted access to verified employment records — no manual calls, no forged references.</p>
            </div>
        </div>

        <div class="step-grid">
            <div class="step-cell">
                <div class="step-num">01</div>
                <h5>Enter National ID</h5>
                <p>Input the worker's 16-digit NID number into the portal.</p>
            </div>
            <div class="step-cell">
                <div class="step-num">02</div>
                <h5>Authenticate Request</h5>
                <p>The system validates credentials and logs access for audit purposes.</p>
            </div>
            <div class="step-cell">
                <div class="step-num">03</div>
                <h5>Retrieve History</h5>
                <p>NFER-EHVS returns the complete verified employment timeline.</p>
            </div>
            <div class="step-cell">
                <div class="step-num">04</div>
                <h5>Download Certificate</h5>
                <p>Generate an official, government-signed verification certificate.</p>
            </div>
        </div>

        <div class="mt-4">
            <a href="/verify-nid" class="btn-lg-gold">
                <i class="bi bi-search"></i> Try a Verification
            </a>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container position-relative" style="z-index:1;">
        <h2>Start Verifying Employment<br>Records Today</h2>
        <p>Secure, government-ready employment verification for modern workforce management in Rwanda.</p>
        <a href="/register" class="btn-lg-gold" style="font-size:15px; padding: 14px 32px;">
            Create Free Account <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer class="site-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="footer-brand">NFER<em>-EHVS</em></div>
                <p class="footer-tagline">National Employment History Verification System — trusted workforce intelligence across Rwanda.</p>
            </div>
            <div class="col-6 col-lg-2">
                <div class="footer-col-head">Platform</div>
                <a href="#features" class="footer-link">Features</a>
                <a href="#how" class="footer-link">How It Works</a>
                <a href="/register" class="footer-link">Get Started</a>
            </div>
            <div class="col-6 col-lg-2">
                <div class="footer-col-head">Resources</div>
                <a href="#" class="footer-link">Documentation</a>
                <a href="#" class="footer-link">API Reference</a>
                <a href="#" class="footer-link">Privacy Policy</a>
            </div>
            <div class="col-lg-4">
                <div class="footer-col-head">Government Partners</div>
                <p style="font-size:13px;color:rgba(255,255,255,0.3);line-height:1.75;">
                    Ministry of Public Service · Rwanda Development Board ·
                    National Social Security Fund · Rwanda Revenue Authority
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <span class="footer-copy">© 2026 NFER-EHVS. All rights reserved. Republic of Rwanda.</span>
            <span class="footer-copy">Built for workforce transparency</span>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const fmt = (n, max) => {
        if (max >= 10000) return (n >= 1000 ? (n / 1000).toFixed(1) + 'K+' : n + '+');
        if (max === 98)   return n + '%';
        return n.toLocaleString() + '+';
    };

    const animateCount = (el) => {
        const target = +el.dataset.count;
        const start  = performance.now();
        const dur    = 1600;
        const tick   = (now) => {
            const p = Math.min((now - start) / dur, 1);
            el.textContent = fmt(Math.round((1 - Math.pow(1 - p, 3)) * target), target);
            if (p < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    };

    document.querySelectorAll('[data-count]').forEach(el => {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) { animateCount(e.target); obs.unobserve(e.target); }
            });
        }, { threshold: 0.5 });
        obs.observe(el);
    });
</script>

</body>
</html>