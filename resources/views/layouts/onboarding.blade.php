<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'NFER-EHVS') — NFER</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Global Design System --}}
    <style>
        :root {
            --navy:        #0f1f3d;
            --navy-light:  #162848;
            --navy-mid:    #1e3a5f;
            --gold:        #c9a84c;
            --gold-light:  #e8c96d;
            --gold-muted:  #b8973e;
            --surface:     #f4f6fb;
            --surface-2:   #eef1f8;
            --border:      #dde3f0;
            --text-main:   #0f1f3d;
            --text-muted:  #6b7a99;
            --text-light:  #9aa3bc;
            --white:       #ffffff;
            --sidebar-w:   260px;
            --topbar-h:    64px;
            --radius:      12px;
            --radius-sm:   8px;
            --shadow-sm:   0 1px 3px rgba(15,31,61,.06), 0 1px 2px rgba(15,31,61,.04);
            --shadow-md:   0 4px 16px rgba(15,31,61,.08), 0 2px 6px rgba(15,31,61,.04);
            --shadow-lg:   0 8px 32px rgba(15,31,61,.12), 0 4px 12px rgba(15,31,61,.06);
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14.5px;
            color: var(--text-main);
            background: var(--surface);
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6,
        .font-display { font-family: 'Syne', sans-serif; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-light); }

        /* ── Cards ── */
        .card {
            border-radius: var(--radius) !important;
            border: 1px solid var(--border) !important;
            box-shadow: var(--shadow-sm);
            background: var(--white);
        }
        .card-header {
            background: var(--white) !important;
            border-bottom: 1px solid var(--border) !important;
            padding: 1rem 1.5rem;
        }
        .card-body { padding: 1.5rem; }

        /* ── Buttons ── */
        .btn {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 500;
            letter-spacing: .01em;
            border-radius: var(--radius-sm);
            transition: all .18s ease;
        }
        .btn-primary {
            background: var(--navy);
            border-color: var(--navy);
            color: var(--white);
        }
        .btn-primary:hover, .btn-primary:focus {
            background: var(--navy-mid);
            border-color: var(--navy-mid);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(15,31,61,.2);
        }
        .btn-gold {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--navy);
            font-weight: 600;
        }
        .btn-gold:hover {
            background: var(--gold-light);
            border-color: var(--gold-light);
            color: var(--navy);
        }
        .btn-outline-primary {
            border-color: var(--navy);
            color: var(--navy);
        }
        .btn-outline-primary:hover {
            background: var(--navy);
            border-color: var(--navy);
            color: var(--white);
        }

        /* ── Form controls ── */
        .form-control, .form-select {
            border-color: var(--border);
            border-radius: var(--radius-sm);
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            color: var(--text-main);
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--navy-mid);
            box-shadow: 0 0 0 3px rgba(30,58,95,.1);
        }
        .form-label {
            font-weight: 600;
            font-size: 12.5px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: .4rem;
        }

        /* ── Badges ── */
        .badge { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 500; }

        /* ── Tables ── */
        .table {
            color: var(--text-main);
            font-size: 13.5px;
        }
        .table thead th {
            font-family: 'Syne', sans-serif;
            font-weight: 600;
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--text-muted);
            border-bottom: 2px solid var(--border);
            padding: .75rem 1rem;
            background: var(--surface);
        }
        .table tbody td {
            padding: .85rem 1rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table-hover tbody tr:hover { background: var(--surface-2); }

        /* ── Alerts ── */
        .alert { border-radius: var(--radius); border: none; font-size: 13.5px; }
        .alert-success { background: #edfaf3; color: #146c43; }
        .alert-danger  { background: #fef1f1; color: #842029; }
        .alert-warning { background: #fff9ec; color: #7d4e00; }
        .alert-info    { background: #edf5ff; color: #084298; }

        /* ── Modals ── */
        .modal-content {
            border: none !important;
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow-lg) !important;
        }
        .modal-header {
            border-bottom: 1px solid var(--border) !important;
            padding: 1.25rem 1.5rem;
        }
        .modal-footer {
            border-top: 1px solid var(--border) !important;
            padding: 1rem 1.5rem;
            background: var(--surface) !important;
            border-radius: 0 0 var(--radius) var(--radius) !important;
        }
        .modal-title { font-family: 'Syne', sans-serif; font-weight: 700; }

        /* ── Pagination ── */
        .pagination .page-link {
            color: var(--navy);
            border-color: var(--border);
            border-radius: var(--radius-sm) !important;
            margin: 0 2px;
            font-size: 13px;
        }
        .pagination .page-item.active .page-link {
            background: var(--navy);
            border-color: var(--navy);
        }

        /* ── Page fade-in ── */
        .page-content {
            animation: pageFadeIn .3s ease both;
        }
        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>

    @stack('styles')
</head>
<body>

    @yield('content')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Auto-dismiss alerts --}}
    <script>
        document.querySelectorAll('.alert-dismissible').forEach(function (alert) {
            setTimeout(function () {
                var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                if (bsAlert) bsAlert.close();
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>
</html>