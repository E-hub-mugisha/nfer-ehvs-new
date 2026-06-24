{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NFER-EHVS')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy: #0e2039;
            --navy-light: #162d50;
            --navy-dark: #0a1828;
            --gold: #d4943a;
            --gold-light: #e8ab56;
            --gold-pale: rgba(212, 148, 58, 0.12);
            --gold-glow: rgba(212, 148, 58, 0.25);
            --text-dim: #8da0b5;
            --text-muted: #5c7a99;
            --bg-page: #f0f4f9;
            --bg-card: #ffffff;
            --border: rgba(14, 32, 57, 0.1);
            --sidebar-w: 272px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg-page);
            color: #1a2e45;
        }

        /* ── SIDEBAR ─────────────────────────────── */

        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--navy-dark);
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(212, 148, 58, 0.15);
            z-index: 1040;
            transition: transform 0.28s cubic-bezier(.4, 0, .2, 1);
        }

        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: repeating-linear-gradient(135deg,
                    rgba(255, 255, 255, .015) 0px,
                    rgba(255, 255, 255, .015) 1px,
                    transparent 1px,
                    transparent 28px);
            pointer-events: none;
        }

        /* BRAND */
        .sidebar-brand {
            padding: 26px 24px 22px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(212, 148, 58, 0.2);
            flex-shrink: 0;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--navy-dark);
            flex-shrink: 0;
            box-shadow: 0 4px 14px var(--gold-glow);
        }

        .brand-text {
            font-family: 'Sora', sans-serif;
            color: #fff;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        /* NAV SCROLL AREA — this is what scrolls */
        .sidebar-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .1);
            border-radius: 2px;
        }

        /* SECTION LABEL */
        .nav-section-label {
            padding: 18px 24px 6px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        /* NAV LINKS */
        .sidebar-menu {
            padding: 8px 0 20px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-dim);
            padding: 11px 24px;
            text-decoration: none;
            transition: all 0.22s ease;
            font-size: 14px;
            font-weight: 500;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .04);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            transition: all 0.22s ease;
        }

        .sidebar-menu a:hover {
            color: #fff;
            background: rgba(255, 255, 255, .05);
            border-left-color: rgba(212, 148, 58, .4);
        }

        .sidebar-menu a:hover .nav-icon {
            background: rgba(212, 148, 58, .15);
            color: var(--gold);
        }

        .sidebar-menu a.active {
            color: #fff;
            background: linear-gradient(90deg, rgba(212, 148, 58, .18) 0%, rgba(212, 148, 58, .04) 100%);
            border-left-color: var(--gold);
        }

        .sidebar-menu a.active .nav-icon {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--navy-dark);
            box-shadow: 0 3px 10px var(--gold-glow);
        }

        /* SIDEBAR FOOTER (mobile logout only) */
        .sidebar-footer {
            padding: 12px 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, .07);
            flex-shrink: 0;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            border: none;
            background: rgba(239, 68, 68, .08);
            color: #f87171;
            width: 100%;
            text-align: left;
            padding: 11px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.22s;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, .16);
            color: #fca5a5;
        }

        /* OVERLAY (mobile) */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10, 24, 40, .55);
            z-index: 1039;
            backdrop-filter: blur(2px);
        }

        /* ── MAIN CONTENT ─────────────────────────── */

        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* TOPBAR */
        .topbar {
            background: var(--bg-card);
            padding: 0 32px;
            height: 68px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 0 rgba(14, 32, 57, .06);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* Hamburger */
        .sidebar-toggle {
            display: none;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-muted);
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            transition: all .2s;
            flex-shrink: 0;
        }

        .sidebar-toggle:hover {
            border-color: var(--gold);
            color: var(--gold);
            background: var(--gold-pale);
        }

        .page-title {
            font-family: 'Sora', sans-serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--navy);
            margin: 0;
            letter-spacing: -0.3px;
        }

        .topbar-divider {
            width: 1px;
            height: 22px;
            background: var(--border);
        }

        .breadcrumb-hint {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* USER DROPDOWN */
        .user-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            text-decoration: none;
        }

        .user-dropdown .dropdown-toggle::after {
            display: none;
            /* hide default Bootstrap caret */
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--navy);
            line-height: 1.3;
        }

        .user-role {
            font-size: 11px;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 15px;
            font-family: 'Sora', sans-serif;
            border: 2px solid var(--gold);
            box-shadow: 0 2px 8px rgba(212, 148, 58, .3);
            flex-shrink: 0;
        }

        /* Dropdown menu */
        .user-dropdown .dropdown-menu {
            min-width: 200px;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 8px 28px rgba(14, 32, 57, .12);
            padding: 8px;
            margin-top: 8px !important;
        }

        .user-dropdown .dropdown-header {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 6px 12px 4px;
        }

        .user-dropdown .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
            color: #1a2e45;
            padding: 9px 12px;
            border-radius: 9px;
            transition: all .18s;
        }

        .user-dropdown .dropdown-item i {
            font-size: 16px;
            color: var(--text-muted);
        }

        .user-dropdown .dropdown-item:hover {
            background: var(--gold-pale);
            color: var(--navy);
        }

        .user-dropdown .dropdown-item:hover i {
            color: var(--gold);
        }

        .user-dropdown .dropdown-divider {
            border-color: var(--border);
            margin: 6px 0;
        }

        .user-dropdown .dropdown-item.text-danger {
            color: #dc2626 !important;
        }

        .user-dropdown .dropdown-item.text-danger i {
            color: #dc2626;
        }

        .user-dropdown .dropdown-item.text-danger:hover {
            background: rgba(239, 68, 68, .08);
            color: #b91c1c !important;
        }

        /* CONTENT */
        .content-wrapper {
            padding: 30px 32px;
            flex: 1;
        }

        /* ALERTS */
        .alert {
            border: none;
            border-radius: 12px;
            font-size: 14px;
            padding: 14px 18px;
        }

        .alert-success {
            background: rgba(16, 185, 129, .1);
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background: rgba(239, 68, 68, .08);
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        /* CARDS */
        .dashboard-card {
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(14, 32, 57, .06);
            transition: box-shadow .2s;
        }

        .dashboard-card:hover {
            box-shadow: 0 6px 24px rgba(14, 32, 57, .1);
        }

        .dashboard-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--navy), var(--navy-light));
            color: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 4px 14px rgba(14, 32, 57, .2);
        }

        /* TABLE */
        .table {
            vertical-align: middle;
            font-size: 14px;
        }

        .table thead th {
            font-family: 'Sora', sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-muted);
            background: #f8fafc;
            border-bottom: 2px solid var(--border);
            padding: 14px 16px;
        }

        .table tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid rgba(14, 32, 57, .05);
        }

        .table tbody tr:hover {
            background: rgba(212, 148, 58, .04);
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* ── RESPONSIVE ───────────────────────────── */

        @media (max-width: 991.98px) {

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .sidebar-toggle {
                display: flex;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                padding: 0 20px;
            }

            .topbar-divider,
            .breadcrumb-hint {
                display: none;
            }

            .content-wrapper {
                padding: 20px 16px;
            }

            .user-info {
                display: none;
                /* hide name/role on small screens, keep avatar */
            }
        }
    </style>
</head>

<body>

    <!-- OVERLAY (mobile backdrop) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-shield-check"></i></div>
            <div>
                <div class="brand-text">NFER-EHVS</div>
            </div>
        </div>

        <!-- Scrollable nav area -->
        <div class="sidebar-scroll">
            <div class="sidebar-menu">

                @if(auth()->user()->role == 'admin')
                <div class="nav-section-label">Main</div>
                <a href="/admin/dashboard" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-grid-1x2"></i></span>
                    Dashboard
                </a>
                <div class="nav-section-label">Management</div>
                <a href="/admin/government/users" class="{{ request()->is('admin/government/users*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-people-circle"></i></span>
                    Government Users
                </a>
                <a href="/admin/employees" class="{{ request()->is('admin/employees*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-people"></i></span>
                    Employees
                </a>
                <a href="/admin/employers" class="{{ request()->is('admin/employers*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-building"></i></span>
                    Employers
                </a>
                <a href="/admin/employment-records">
                    <span class="nav-icon"><i class="bi bi-clock-history"></i></span>
                    Employment Records
                </a>
                <!-- <a href="/admin/disputes">
                    <span class="nav-icon"><i class="bi bi-exclamation-circle"></i></span>
                    Disputes
                </a> -->
                <a href="{{ route('admin.transfer-requests.index') }}" class="nav-item">
                    <span class="nav-icon"><i class="bi bi-arrow-left-right"></i></span>
                    Transfer Requests
                </a>
                <div class="nav-section-label">System</div>
                <a href="/admin/reports">
                    <span class="nav-icon"><i class="bi bi-bar-chart"></i></span>
                    Reports
                </a>
                @endif

                @if(auth()->user()->role == 'government')
                <div class="nav-section-label">Overview</div>
                <a href="/government/dashboard">
                    <span class="nav-icon"><i class="bi bi-grid-1x2"></i></span>
                    Government Dashboard
                </a>
                <div class="nav-section-label">Operations</div>
                <a href="{{ route('government.employees.index') }}">
                    <span class="nav-icon"><i class="bi bi-building-check"></i></span>
                    Employees
                </a>
                <a href="{{ route('government.employers.index') }}">
                    <span class="nav-icon"><i class="bi bi-building"></i></span>
                    Employers
                </a>
                <a href="{{ route('government.employment-records.index') }}">
                    <span class="nav-icon"><i class="bi bi-file-earmark-text"></i></span>
                    Employment Records
                </a>
                <!-- <a href="{{ route('government.disputes.index') }}">
                    <span class="nav-icon"><i class="bi bi-exclamation-diamond"></i></span>
                    Disputes
                </a> -->
                <div class="nav-section-label">Insights</div>
                <a href="{{ route('government.reports.index') }}">
                    <span class="nav-icon"><i class="bi bi-pie-chart"></i></span>
                    National Reports
                </a>
                <a href="{{ route('government.transfer-requests.index') }}">
                    <span class="nav-icon"><i class="bi bi-graph-up-arrow"></i></span>
                    Transfers
                </a>
                @endif

                @if(auth()->user()->role == 'employer')
                <div class="nav-section-label">Overview</div>
                <a href="/employer/dashboard">
                    <span class="nav-icon"><i class="bi bi-grid-1x2"></i></span>
                    Dashboard
                </a>
                <div class="nav-section-label">Workforce</div>
                <a href="{{ route('employer.employees.index') }}">
                    <span class="nav-icon"><i class="bi bi-person-plus"></i></span>
                    Employees
                </a>
                <a href="{{ route('employer.employees.records.index') }}">
                    <span class="nav-icon"><i class="bi bi-journal-text"></i></span>
                    Employment Records
                </a>
                <a href="{{ route('employer.search.index') }}">
                    <span class="nav-icon"><i class="bi bi-search"></i></span>
                    Search Employee
                </a>
                <div class="nav-section-label">Transfers</div>
                <a href="{{ route('employer.transfer.sent') }}">
                    <span class="nav-icon"><i class="bi bi-arrow-right-circle"></i></span>
                    Sent Requests
                </a>
                <a href="{{ route('employer.transfer.received') }}">
                    <span class="nav-icon"><i class="bi bi-arrow-left-circle"></i></span>
                    Received Requests
                </a>
                <!-- <div class="nav-section-label">Admin</div>
                <a href="{{ route('employer.disputes.index') }}">
                    <span class="nav-icon"><i class="bi bi-exclamation-circle"></i></span>
                    Disputes
                </a> -->
                @endif

                @if(auth()->user()->role == 'employee')
                <div class="nav-section-label">My Space</div>
                <a href="/employee/dashboard">
                    <span class="nav-icon"><i class="bi bi-grid-1x2"></i></span>
                    Dashboard
                </a>
                <a href="/employee/profile">
                    <span class="nav-icon"><i class="bi bi-person"></i></span>
                    My Profile
                </a>
                <a href="/my-employment-records">
                    <span class="nav-icon"><i class="bi bi-clock-history"></i></span>
                    Employment Records
                </a>
                <!-- <a href="/my-disputes">
                    <span class="nav-icon"><i class="bi bi-exclamation-circle"></i></span>
                    Claims & Disputes
                </a> -->
                @endif

            </div>
        </div><!-- /sidebar-scroll -->

        <!-- Mobile-only footer logout -->
        <div class="sidebar-footer d-lg-none">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">
                    <i class="bi bi-box-arrow-right"></i>
                    Sign Out
                </button>
            </form>
        </div>

    </div><!-- /sidebar -->

    <!-- MAIN CONTENT -->
    <div class="main-content">

        <!-- TOPBAR -->
        <div class="topbar">

            <div class="topbar-left">
                <!-- Hamburger (mobile only) -->
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>

                <h5 class="page-title">@yield('title', 'Dashboard')</h5>
                <div class="topbar-divider"></div>
                <span class="breadcrumb-hint">NFER-EHVS</span>
            </div>

            <div class="d-flex align-items-center gap-3">

                <!-- USER DROPDOWN -->
                <div class="dropdown user-dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-info">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-role">{{ auth()->user()->role }}</div>
                        </div>
                        <div class="avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <h6 class="dropdown-header">{{ auth()->user()->name }}</h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger w-100 border-0 bg-transparent">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
        </div><!-- /topbar -->

        <!-- PAGE CONTENT -->
        <div class="content-wrapper">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <i class="bi bi-x-circle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')

        </div>

    </div><!-- /main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggle = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        toggle.addEventListener('click', () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });

        overlay.addEventListener('click', closeSidebar);

        // Close sidebar on nav link click (mobile UX)
        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) closeSidebar();
            });
        });
    </script>

</body>

</html>