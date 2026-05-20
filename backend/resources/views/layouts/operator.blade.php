<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dasbor' }} — Jalan.In Operator</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Manrope', sans-serif; }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
            background: linear-gradient(135deg, #faf6f6 0%, #f5efef 50%, #f8f4f4 100%);
        }

        /* ===== Sidebar ===== */
        .admin-sidebar {
            width: 240px;
            min-height: 100vh;
            background: #fffaf8;
            border-right: 1px solid rgba(107, 29, 42, 0.06);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 40;
        }

        .sidebar-brand { padding: 28px 24px 8px; }
        .sidebar-brand h1 { font-size: 1.5rem; font-weight: 800; color: var(--maroon, #6B1D2A); letter-spacing: -0.02em; }
        .sidebar-brand p { font-size: 0.7rem; color: #b8a0a5; margin-top: 2px; letter-spacing: 0.02em; }

        .sidebar-nav { padding: 24px 12px; flex: 1; display: flex; flex-direction: column; gap: 4px; }

        .sidebar-link {
            display: flex; align-items: center; gap: 12px; padding: 12px 16px;
            border-radius: 12px; font-size: 0.875rem; font-weight: 500;
            color: #7a6a6d; text-decoration: none; transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover { background: rgba(107, 29, 42, 0.04); color: var(--maroon, #6B1D2A); }
        .sidebar-link.active { color: var(--maroon, #6B1D2A); font-weight: 600; border-left-color: var(--maroon, #6B1D2A); background: rgba(107, 29, 42, 0.03); }
        .sidebar-link svg { width: 20px; height: 20px; flex-shrink: 0; }

        .sidebar-user { padding: 20px 16px; border-top: 1px solid rgba(107, 29, 42, 0.06); display: flex; align-items: center; gap: 12px; }
        .sidebar-user-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #e8d5d5 0%, #d4b8b8 100%); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: var(--maroon, #6B1D2A); }
        .sidebar-user-info h4 { font-size: 0.8rem; font-weight: 600; color: #1a1a1a; }
        .sidebar-user-info p { font-size: 0.65rem; color: #9ca3af; }

        /* ===== Main Content ===== */
        .admin-main { flex: 1; margin-left: 240px; min-height: 100vh; position: relative; }
        .admin-topbar { 
            display: flex; 
            justify-content: flex-end; 
            align-items: center; 
            padding: 16px 40px; 
            gap: 16px; 
            position: relative; 
            z-index: 1100; /* Pastikan di atas elemen Leaflet (1000) */
        }
        .topbar-icon { width: 40px; height: 40px; border-radius: 50%; background: white; border: 1px solid rgba(107, 29, 42, 0.08); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; color: #7a6a6d; }
        .topbar-icon:hover { background: rgba(107, 29, 42, 0.04); color: var(--maroon, #6B1D2A); }
        .admin-content { padding: 0 40px 40px; }

        /* ===== Cards ===== */
        .admin-card { background: white; border: 1px solid rgba(107, 29, 42, 0.06); border-radius: 16px; padding: 24px; transition: all 0.2s ease; }
        .admin-card:hover { box-shadow: 0 4px 24px rgba(107, 29, 42, 0.06); }

        .stat-card { background: white; border: 1px solid rgba(107, 29, 42, 0.06); border-radius: 16px; padding: 20px 24px; display: flex; justify-content: space-between; align-items: flex-start; transition: all 0.2s ease; }
        .stat-card:hover { box-shadow: 0 4px 24px rgba(107, 29, 42, 0.06); }

        .stat-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; }
        .stat-value { font-size: 2rem; font-weight: 800; color: #1a1a1a; line-height: 1.1; margin-top: 4px; }
        .stat-icon { width: 36px; height: 36px; border-radius: 10px; background: rgba(107, 29, 42, 0.06); display: flex; align-items: center; justify-content: center; color: var(--maroon, #6B1D2A); }

        .page-title { font-size: 1.75rem; font-weight: 800; color: var(--maroon, #6B1D2A); letter-spacing: -0.02em; }
        .page-subtitle { font-size: 0.875rem; color: #9ca3af; margin-top: 4px; }
        .section-title { font-size: 1.15rem; font-weight: 700; color: #1a1a1a; }
        .section-subtitle { font-size: 0.8rem; color: #9ca3af; margin-top: 2px; }

        /* Buttons */
        .btn-admin-primary { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, var(--maroon, #6B1D2A) 0%, #8B2E3B 100%); color: white; border: none; border-radius: 12px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.25s ease; text-decoration: none; }
        .btn-admin-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(107, 29, 42, 0.25); }
        .btn-admin-secondary { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: white; border: 1px solid rgba(107, 29, 42, 0.12); border-radius: 12px; font-size: 0.85rem; font-weight: 500; color: #7a6a6d; cursor: pointer; transition: all 0.2s ease; text-decoration: none; }
        .btn-admin-secondary:hover { border-color: rgba(107, 29, 42, 0.25); color: var(--maroon, #6B1D2A); }

        /* Form */
        .admin-input { width: 100%; padding: 14px 16px; background: #faf7f7; border: 1px solid rgba(107, 29, 42, 0.08); border-radius: 12px; font-size: 0.875rem; color: #1a1a1a; transition: all 0.2s ease; outline: none; }
        .admin-input::placeholder { color: #c4b0b3; }
        .admin-input:focus { border-color: rgba(107, 29, 42, 0.2); box-shadow: 0 0 0 3px rgba(107, 29, 42, 0.05); background: white; }
        .admin-label { display: block; font-size: 0.75rem; font-weight: 600; color: #374151; margin-bottom: 8px; }

        /* Badge */
        .badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 8px; font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; }
        .badge-dilaporkan { background: #fef2f2; color: #dc2626; }
        .badge-disurvey   { background: #fffbeb; color: #b45309; }
        .badge-tidakvalid  { background: #f3f4f6; color: #374151; }
        .badge-diproses    { background: #eff6ff; color: #2563eb; }
        .badge-selesai     { background: #ecfdf5; color: #15803d; }

        /* Status dot */
        .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
        .status-dot.dilaporkan { background: #EF4444; }
        .status-dot.disurvey   { background: #F59E0B; }
        .status-dot.tidakvalid  { background: #374151; }
        .status-dot.diproses    { background: #3B82F6; }
        .status-dot.selesai     { background: #22C55E; }

        /* Filter pill */
        .filter-pill { padding: 6px 14px; border-radius: 8px; font-size: 0.75rem; font-weight: 500; color: #9ca3af; background: #f9f6f6; border: 1px solid rgba(107, 29, 42, 0.06); cursor: pointer; transition: all 0.2s ease; }
        .filter-pill:hover, .filter-pill.active { color: var(--maroon, #6B1D2A); border-color: rgba(107, 29, 42, 0.15); background: rgba(107, 29, 42, 0.03); }

        /* Chart toggle */
        .chart-toggle { display: flex; background: #f9f6f6; border-radius: 8px; padding: 2px; gap: 2px; }
        .chart-toggle button { padding: 6px 14px; border-radius: 6px; font-size: 0.7rem; font-weight: 500; color: #9ca3af; border: none; background: none; cursor: pointer; transition: all 0.2s ease; }
        .chart-toggle button.active { background: var(--maroon, #6B1D2A); color: white; }

        /* Pagination */
        .pagination-btn { padding: 8px 16px; border-radius: 10px; font-size: 0.8rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease; text-decoration: none; display: inline-flex; align-items: center; }
        .pagination-btn.prev { background: white; border: 1px solid rgba(107, 29, 42, 0.1); color: #7a6a6d; }
        .pagination-btn.next { background: var(--maroon, #6B1D2A); border: 1px solid var(--maroon, #6B1D2A); color: white; }
        .pagination-btn:hover { transform: translateY(-1px); }
        .page-num { width: 36px; height: 36px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 600; text-decoration: none; border: 1px solid rgba(107,29,42,0.06); color: #7a6a6d; transition: all 0.2s ease; }
        .page-num.active { background: var(--maroon, #6B1D2A); color: white; border-color: var(--maroon, #6B1D2A); }

        /* Action buttons */
        .action-btn { width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s ease; cursor: pointer; border: 1.5px solid #e5e7eb; background: white; color: #9ca3af; }
        .action-btn:hover { border-color: var(--maroon, #6B1D2A); color: var(--maroon, #6B1D2A); }
        .action-btn.active { background: var(--maroon, #6B1D2A); border-color: var(--maroon, #6B1D2A); color: white; }
        .action-btn.disabled { opacity: 0.3; pointer-events: none; }

        /* Profile Dropdown */
        .profile-container { position: relative; }
        .dropdown-menu { 
            position: absolute; 
            top: calc(100% + 12px); 
            right: 0; 
            width: 200px; 
            background: white; 
            border: 1px solid rgba(107, 29, 42, 0.08); 
            border-radius: 12px; 
            box-shadow: 0 10px 25px rgba(107, 29, 42, 0.08); 
            padding: 8px; 
            z-index: 1200; /* Di atas topbar */
            display: none; 
        }
        .dropdown-menu.show { display: block; }
        .dropdown-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #4b5563; font-size: 0.875rem; font-weight: 500; text-decoration: none; border-radius: 8px; transition: all 0.2s ease; width: 100%; border: none; background: none; cursor: pointer; text-align: left; }
        .dropdown-item:hover { background: rgba(107, 29, 42, 0.04); color: var(--maroon, #6B1D2A); }
        .dropdown-item.danger:hover { background: #fef2f2; color: #dc2626; }
        .dropdown-divider { height: 1px; background: rgba(107, 29, 42, 0.06); margin: 8px 0; }

        /* Responsive */
        @media (max-width: 1024px) {
            .admin-sidebar { width: 200px; }
            .admin-main { margin-left: 200px; }
            .admin-content { padding: 0 24px 24px; }
        }
    </style>
</head>
<body class="antialiased">
    <div class="admin-wrapper">
        {{-- ===== SIDEBAR ===== --}}
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('operator.dashboard') }}" style="text-decoration:none;">
                    <h1>jalan.in</h1>
                    <p>Pemantauan Jalan</p>
                </a>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('operator.dashboard') }}" class="sidebar-link {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                    </svg>
                    Dasbor
                </a>

                <a href="{{ route('operator.map') }}" class="sidebar-link {{ request()->routeIs('operator.map*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m0 0-3.75 3.75M9 15l3.75 3.75M15 6.75v8.25m0 0 3.75 3.75M15 15l-3.75 3.75M3.75 4.875c0-.621.504-1.125 1.125-1.125h14.25c.621 0 1.125.504 1.125 1.125v14.25c0 .621-.504 1.125-1.125 1.125H4.875a1.125 1.125 0 0 1-1.125-1.125V4.875Z" />
                    </svg>
                    Peta
                </a>

                <a href="{{ route('operator.reports.index') }}" class="sidebar-link {{ request()->routeIs('operator.reports.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    Pengelolaan Laporan
                </a>

                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Profil
                </a>
            </nav>

            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'O', 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <h4>{{ Auth::user()->name ?? 'Operator' }}</h4>
                    <p>Operator</p>
                </div>
            </div>
        </aside>

        {{-- ===== MAIN ===== --}}
        <div class="admin-main">
            <div class="admin-topbar">
                <button class="topbar-icon" title="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </button>

                <div class="profile-container" x-data="{ open: false }">
                    <div class="topbar-icon"
                         @click="open = !open"
                         @click.away="open = false"
                         style="background: linear-gradient(135deg, #e8d5d5 0%, #d4b8b8 100%); color: var(--maroon, #6B1D2A); font-size: 0.75rem; font-weight: 700; cursor: pointer;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'O', 0, 1)) }}
                    </div>

                    <div class="dropdown-menu" :class="{ 'show': open }" x-show="open" x-transition>
                        <div style="padding: 12px; border-bottom: 1px solid rgba(107, 29, 42, 0.06); margin-bottom: 8px;">
                            <p style="font-size: 0.75rem; font-weight: 600; color: #111827; margin: 0;">{{ Auth::user()->name }}</p>
                            <p style="font-size: 0.65rem; color: #6b7280; margin: 4px 0 0 0;">{{ Auth::user()->email }}</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                            Pengaturan Profil
                        </a>

                        <div class="dropdown-divider"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12" /></svg>
                                Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="admin-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
</body>
</html>
