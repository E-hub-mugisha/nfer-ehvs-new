<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? config('app.name', 'NFER-EHVS') }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Fonts: Syne + Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet">

    <style>
        /* ─── Variables ─── */
        :root {
            --navy: #091422;
            --navy-mid: #0e2039;
            --navy-soft: #152a48;
            --gold: #d4943a;
            --gold-light: #e8b96b;
            --gold-pale: rgba(212, 148, 58, 0.12);
            --white: #ffffff;
            --off-white: #f6f8fc;
            --border: rgba(255, 255, 255, 0.08);
            --muted: #8094ae;
        }

        /* ─── Base ─── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 16px;
            background: var(--off-white);
            color: #1a2e45;
            overflow-x: hidden;
        }

        /* ─── Layout Shell ─── */
        .auth-shell {
            display: flex;
            min-height: 100vh;
        }

        /* ─── Left Brand Panel ─── */
        .auth-brand-panel {
            width: 42%;
            min-height: 100vh;
            background: var(--navy);
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 44px 52px;
            overflow: hidden;
            flex-shrink: 0;
        }

        /* Dot grid */
        .auth-brand-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.055) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        /* Gold orb top-right */
        .auth-brand-panel::after {
            content: '';
            position: absolute;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 148, 58, 0.2) 0%, transparent 70%);
            top: -160px;
            right: -120px;
            pointer-events: none;
        }

        /* Second orb bottom-left */
        .orb-bottom {
            position: absolute;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.12) 0%, transparent 70%);
            bottom: -120px;
            left: -80px;
            pointer-events: none;
        }

        .brand-content {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Logo */
        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            margin-bottom: auto;
        }

        .brand-logo-mark {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--gold-pale);
            border: 1px solid rgba(212, 148, 58, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--gold);
        }

        .brand-logo-text {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: var(--white);
            letter-spacing: -0.3px;
        }

        .brand-logo-text span {
            color: var(--gold);
        }

        /* Central brand content */
        .brand-body {
            margin: auto 0;
            padding: 60px 0 40px;
        }

        .brand-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            color: var(--gold);
            background: var(--gold-pale);
            border: 1px solid rgba(212, 148, 58, 0.28);
            border-radius: 50px;
            padding: 5px 12px;
            margin-bottom: 24px;
        }

        .brand-headline {
            font-family: 'Syne', sans-serif;
            font-size: clamp(26px, 2.8vw, 36px);
            font-weight: 800;
            color: var(--white);
            line-height: 1.15;
            letter-spacing: -0.5px;
            margin-bottom: 16px;
        }

        .brand-headline .accent {
            color: var(--gold);
        }

        .brand-sub {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.45);
            line-height: 1.75;
            max-width: 340px;
            margin-bottom: 40px;
        }

        /* Verification preview card */
        .verify-preview-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.09);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 32px;
        }

        .verify-preview-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .preview-avatar {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: rgba(212, 148, 58, 0.15);
            border: 1px solid rgba(212, 148, 58, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: var(--gold);
            flex-shrink: 0;
        }

        .preview-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--white);
        }

        .preview-nid {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.35);
        }

        .preview-badge {
            margin-left: auto;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 50px;
            background: rgba(22, 163, 74, 0.15);
            color: #4ade80;
            border: 1px solid rgba(74, 222, 128, 0.2);
        }

        .preview-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        .preview-row-label {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.35);
        }

        .preview-row-val {
            font-size: 12px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Trust stats */
        .trust-stats {
            display: flex;
            gap: 24px;
        }

        .trust-stat-num {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--white);
            line-height: 1;
            margin-bottom: 3px;
        }

        .trust-stat-label {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.35);
            font-weight: 500;
        }

        /* Footer note */
        .brand-footer-note {
            position: relative;
            z-index: 1;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.2);
            margin-top: auto;
        }

        /* ─── Right Form Panel ─── */
        .auth-form-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: var(--off-white);
            overflow-y: auto;
        }

        .auth-form-wrap {
            width: 100%;
            max-width: 420px;
        }

        /* Page title */
        .auth-page-title {
            font-family: 'Syne', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -0.4px;
            margin-bottom: 6px;
        }

        .auth-page-subtitle {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 36px;
            line-height: 1.6;
        }

        /* ─── Form Field Overrides ─── */
        .auth-form-wrap label {
            display: block;
            font-size: 11px !important;
            font-weight: 600 !important;
            letter-spacing: 0.7px !important;
            text-transform: uppercase;
            color: #6b7fa8 !important;
            margin-bottom: 8px !important;
        }

        .auth-form-wrap input[type="text"],
        .auth-form-wrap input[type="email"],
        .auth-form-wrap input[type="password"] {
            width: 100%;
            background: var(--white) !important;
            border: 1.5px solid #dce5f0 !important;
            border-radius: 10px !important;
            padding: 13px 16px !important;
            font-size: 15px !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            color: var(--navy) !important;
            box-shadow: none !important;
            outline: none !important;
            transition: border-color 0.2s, box-shadow 0.2s !important;
        }

        .auth-form-wrap input[type="text"]:focus,
        .auth-form-wrap input[type="email"]:focus,
        .auth-form-wrap input[type="password"]:focus {
            border-color: var(--gold) !important;
            box-shadow: 0 0 0 3px rgba(212, 148, 58, 0.12) !important;
        }

        .auth-form-wrap input[type="text"]::placeholder,
        .auth-form-wrap input[type="email"]::placeholder,
        .auth-form-wrap input[type="password"]::placeholder {
            color: #b4c3d6 !important;
        }

        /* Error messages */
        .auth-form-wrap .text-red-600,
        .auth-form-wrap [class*="text-red"] {
            font-size: 12px !important;
            color: #dc2626 !important;
            margin-top: 6px !important;
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
        }

        /* Session status */
        .auth-form-wrap .text-green-600,
        .auth-form-wrap [class*="text-green"] {
            font-size: 13px !important;
            color: #16a34a !important;
        }

        /* Primary Button */
        .auth-form-wrap button[type="submit"],
        .auth-form-wrap .btn-auth-primary {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            background: var(--gold) !important;
            color: var(--navy) !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 13px 26px !important;
            font-size: 15px !important;
            font-weight: 600 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            cursor: pointer !important;
            transition: background 0.2s, transform 0.15s !important;
            text-decoration: none !important;
        }

        .auth-form-wrap button[type="submit"]:hover,
        .auth-form-wrap .btn-auth-primary:hover {
            background: var(--gold-light) !important;
            transform: translateY(-1px) !important;
            color: var(--navy) !important;
        }

        /* Checkbox */
        .auth-form-wrap input[type="checkbox"] {
            width: 16px !important;
            height: 16px !important;
            border: 1.5px solid #dce5f0 !important;
            border-radius: 4px !important;
            accent-color: var(--gold) !important;
        }

        .auth-form-wrap .check-label {
            font-size: 13px !important;
            color: #6b7fa8 !important;
            text-transform: none !important;
            letter-spacing: 0 !important;
            font-weight: 400 !important;
        }

        /* Auth links */
        .auth-link {
            font-size: 13px;
            color: var(--gold);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: var(--gold-light);
            text-decoration: underline;
        }

        .auth-muted-link {
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .auth-muted-link:hover {
            color: var(--navy);
        }

        /* Field group spacing */
        .auth-field {
            margin-bottom: 20px;
        }

        /* Input row with icon */
        .input-icon-wrap {
            position: relative;
        }

        .input-icon-wrap .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: #b4c3d6;
            pointer-events: none;
        }

        .input-icon-wrap input {
            padding-left: 42px !important;
        }

        /* Toggle password btn */
        .toggle-pass {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #b4c3d6;
            cursor: pointer;
            font-size: 16px;
            padding: 0;
            transition: color 0.2s;
            line-height: 1;
            display: flex;
            align-items: center;
        }

        .toggle-pass:hover {
            color: var(--muted);
        }

        /* Divider */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 28px 0;
        }

        .auth-divider-line {
            flex: 1;
            height: 1px;
            background: #dce5f0;
        }

        .auth-divider-text {
            font-size: 12px;
            color: #b4c3d6;
            font-weight: 500;
        }

        /* Bottom link strip */
        .auth-bottom-strip {
            text-align: center;
            margin-top: 28px;
            font-size: 13px;
            color: var(--muted);
        }

        .auth-status-box {
            background: rgba(22, 163, 74, 0.08);
            border: 1px solid rgba(22, 163, 74, 0.2);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            color: #15803d;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .auth-error-box {
            background: rgba(220, 38, 38, 0.06);
            border: 1px solid rgba(220, 38, 38, 0.18);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            color: #dc2626;
            margin-bottom: 24px;
        }

        /* Responsive */
        @media (max-width: 860px) {
            .auth-brand-panel {
                display: none;
            }

            .auth-form-panel {
                padding: 40px 24px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-shell">

        {{-- ═══ LEFT BRAND PANEL ═══ --}}
        <div class="auth-brand-panel">
            <div class="orb-bottom"></div>
            <div class="brand-content">

                {{-- Logo --}}
                <a href="/" class="brand-logo">
                    <div class="brand-logo-mark">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <span class="brand-logo-text">NFER<span>-EHVS</span></span>
                </a>

                {{-- Headline --}}
                <div class="brand-body">
                    <div class="brand-eyebrow">
                        <i class="bi bi-patch-check-fill"></i>
                        Government Certified
                    </div>

                    <h2 class="brand-headline">
                        Rwanda's Trusted<br>
                        <span class="accent">Employment</span><br>
                        Verification Platform
                    </h2>

                    <p class="brand-sub">
                        Securely access verified employment records,
                        manage workforce history, and issue government-certified
                        verification certificates.
                    </p>

                    {{-- Verification preview card --}}
                    <div class="verify-preview-card">
                        <div class="verify-preview-header">
                            <div class="preview-avatar">
                                <i class="bi bi-person"></i>
                            </div>
                            <div>
                                <div class="preview-name">Jean-Paul Habimana</div>
                                <div class="preview-nid">NID · 1199880012345678</div>
                            </div>
                            <div class="preview-badge">
                                <i class="bi bi-patch-check-fill" style="font-size:9px;"></i>
                                Verified
                            </div>
                        </div>
                        <div class="preview-row">
                            <span class="preview-row-label">Current Employer</span>
                            <span class="preview-row-val">RwandaTech Solutions</span>
                        </div>
                        <div class="preview-row">
                            <span class="preview-row-label">Employment Records</span>
                            <span class="preview-row-val">3 verified positions</span>
                        </div>
                        <div class="preview-row">
                            <span class="preview-row-label">Last Verified</span>
                            <span class="preview-row-val">Today, 09:42 AM</span>
                        </div>
                    </div>

                    {{-- Trust stats --}}
                    <div class="trust-stats">
                        <div>
                            <div class="trust-stat-num">25K+</div>
                            <div class="trust-stat-label">Employees Registered</div>
                        </div>
                        <div style="width:1px;background:rgba(255,255,255,0.08);"></div>
                        <div>
                            <div class="trust-stat-num">3,200</div>
                            <div class="trust-stat-label">Verified Employers</div>
                        </div>
                        <div style="width:1px;background:rgba(255,255,255,0.08);"></div>
                        <div>
                            <div class="trust-stat-num">98%</div>
                            <div class="trust-stat-label">Accuracy Rate</div>
                        </div>
                    </div>
                </div>

                <p class="brand-footer-note">
                    © {{ date('Y') }} NFER-EHVS · Republic of Rwanda · All rights reserved
                </p>
            </div>
        </div>

        {{-- ═══ RIGHT FORM PANEL ═══ --}}
        <div class="auth-form-panel">
            <div class="auth-form-wrap">

                {{-- Mobile logo (hidden on desktop) --}}
                <div class="d-flex align-items-center gap-2 mb-4 d-md-none">
                    <div style="width:32px;height:32px;border-radius:8px;background:var(--navy);display:flex;align-items:center;justify-content:center;font-size:15px;color:var(--gold);">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <span style="font-family:'Syne',sans-serif;font-size:17px;font-weight:800;color:var(--navy);">NFER<span style="color:var(--gold);">-EHVS</span></span>
                </div>

                {{-- Page title + subtitle via named slots --}}
                @isset($pageTitle)
                <h1 class="auth-page-title">{{ $pageTitle }}</h1>
                @endisset

                @isset($pageSubtitle)
                <p class="auth-page-subtitle">{{ $pageSubtitle }}</p>
                @endisset

                {{-- Form slot --}}
                {{ $slot }}

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* Password visibility toggles */
        document.querySelectorAll('.toggle-pass').forEach(btn => {
            btn.addEventListener('click', () => {
                const inp = btn.closest('.input-icon-wrap').querySelector('input');
                const icon = btn.querySelector('i');
                if (inp.type === 'password') {
                    inp.type = 'text';
                    icon.className = 'bi bi-eye-slash';
                } else {
                    inp.type = 'password';
                    icon.className = 'bi bi-eye';
                }
            });
        });
    </script>
</body>

</html>