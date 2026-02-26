<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'aa') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS FIRST -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables CSS - AFTER Bootstrap -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <!-- Select2 CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <!-- Custom CSS - LAST -->
    @vite(['resources/js/app.js', 'resources/css/layout.css'])
    @stack('styles')

    <style>
        /* Fix DataTables Bootstrap integration */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 0.5rem !important;
            margin-bottom: 0.5rem !important;
        }

        .dataTables_wrapper .dataTables_filter {
            text-align: right !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5rem !important;
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            padding: 0.375rem 0.75rem !important;
        }

        /* Fix table styling */
        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0 !important;
        }

        table.dataTable thead th {
            border-bottom: 2px solid #dee2e6 !important;
            position: relative;
        }

        /* Fix sorting icons */
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after {
            position: absolute !important;
            top: 50% !important;
            right: 8px !important;
            transform: translateY(-50%) !important;
            font-family: "Font Awesome 5 Free" !important;
            font-weight: 900 !important;
        }

        table.dataTable thead .sorting:after {
            content: "\f0dc" !important;
            color: #6c757d !important;
            opacity: 0.5 !important;
        }

        table.dataTable thead .sorting_asc:after {
            content: "\f0de" !important;
            color: #0d6efd !important;
        }

        table.dataTable thead .sorting_desc:after {
            content: "\f0dd" !important;
            color: #0d6efd !important;
        }

        /* Additional dropdown menu styles */
        .sidebar-menu-item.has-submenu .sidebar-menu-link {
            position: relative;
        }

        .submenu-indicator {
            margin-left: auto;
            font-size: 0.75rem;
            transition: transform 0.2s ease;
            flex-shrink: 0;
        }

        .submenu-indicator.rotated {
            transform: rotate(180deg);
        }

        .sidebar-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background-color: #f8fafc;
            border-left: 3px solid var(--primary-blue);
            margin-left: 1rem;
        }

        .sidebar-submenu.show {
            max-height: 500px;
        }

        .submenu-item {
            border-bottom: 1px solid #e5e7eb;
        }

        .submenu-item:last-child {
            border-bottom: none;
        }

        .submenu-item .sidebar-menu-link {
            padding-left: 2rem;
            font-size: 0.875rem;
            border-right: none;
        }

        .submenu-item .sidebar-menu-link:hover,
        .submenu-item .sidebar-menu-link.active {
            background-color: var(--light-blue);
            color: var(--primary-blue);
        }

        .menu-disabled {
            opacity: 0.6;
            cursor: not-allowed !important;
            pointer-events: none;
        }

        .sidebar.collapsed .has-submenu .sidebar-menu-link {
            justify-content: center;
        }

        .sidebar.collapsed .submenu-indicator {
            display: none;
        }

        .sidebar.collapsed .sidebar-submenu {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar-submenu {
                margin-left: 0;
                border-left: none;
                border-top: 1px solid #e5e7eb;
            }

            .submenu-item .sidebar-menu-link {
                padding-left: 3rem;
            }
        }

        .sidebar.collapsed .sidebar-menu-item[data-tooltip]:hover::before {
            white-space: nowrap;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

</head>

<body>
    <div id="app">
        <!-- Navbar -->
        <nav class="modern-navbar">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="sidebar-toggle" id="sidebarToggleBtn" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-users"></i>
                    SISKA
                </a>
            </div>

            <div class="navbar-right">
                @guest
                    <div class="auth-links">
                        @if (Route::has('login'))
                            <a class="auth-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i>
                                {{ __('Login') }}
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a class="auth-link register" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i>
                                {{ __('Register') }}
                            </a>
                        @endif
                    </div>
                @else
                    <div class="user-dropdown">
                        <button class="user-menu-btn" onclick="toggleUserMenu()">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
                        </button>

                        <div class="dropdown-menu" id="userDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt" style="margin-right: 0.5rem;"></i>
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </nav>

        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <!-- Dashboard -->
                    <li class="sidebar-menu-item" data-tooltip="Dashboard">
                        <a href="{{ url('/') }}"
                            class="sidebar-menu-link {{ request()->is('/') || request()->is('home') || request()->is('dashboard') || request()->is('index.php') || request()->fullUrlIs(url('/')) ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span class="sidebar-menu-text">Dashboard</span>
                        </a>
                    </li>

                    @php
                        // Data Master Access Management
                        $dataMasterActive = isMenuActive([
                            'users*',
                            'perusahaan*',
                            'wilayah-kerja*',
                            'departemen*',
                            'kontrak-kerja*',
                            'dokumen-karyawan*',
                        ]);

                        $hasDataMasterAccess = hasMenuAccess([
                            'pengguna',
                            'perusahaan',
                            'wilayah-kerja',
                            'departemen',
                            'kontrak-kerja',
                            'dokumen-karyawan',
                        ]);

                        // Dokumen Access Management
                        $manajemenDataActive = isMenuActive(['data-karyawan*', 'data-kontrak*', 'data-dokumen*']);

                        $hasManajemenDataAccess = hasMenuAccess(['data-karyawan', 'data-kontrak', 'data-dokumen']);
                    @endphp

                    <!-- DATA MASTER DROPDOWN MENU -->
                    @if ($hasDataMasterAccess)
                        <li class="sidebar-menu-item has-submenu" data-tooltip="Data Master">
                            <a class="sidebar-menu-link menu-dropdown {{ $dataMasterActive ? 'active' : '' }}"
                                href="#" data-menu="dataMaster">
                                <i class="fas fa-database"></i>
                                <span class="sidebar-menu-text">Data Master</span>
                                <i
                                    class="fas fa-chevron-down submenu-indicator {{ $dataMasterActive ? 'rotated' : '' }}"></i>
                            </a>
                            <ul class="sidebar-submenu {{ $dataMasterActive ? 'show' : '' }}" id="dataMaster">
                                @if (Auth::user()->is_admin || Auth::user()->hasAccess('pengguna'))
                                    <li class="submenu-item">
                                        <a class="sidebar-menu-link {{ request()->is('users*') ? 'active' : '' }}"
                                            href="{{ route('users.index') }}">
                                            <i class="fas fa-users"></i>
                                            <span class="sidebar-menu-text">Pengguna</span>
                                        </a>
                                    </li>
                                @endif

                                @if (Auth::user()->is_admin || Auth::user()->hasAccess('perusahaan'))
                                    <li class="submenu-item">
                                        <a class="sidebar-menu-link {{ request()->is('perusahaan*') ? 'active' : '' }}"
                                            href="{{ route('perusahaan.index') }}">
                                            <i class="fas fa-industry"></i>
                                            <span class="sidebar-menu-text">Perusahaan</span>
                                        </a>
                                    </li>
                                @endif

                                @if (Auth::user()->is_admin || Auth::user()->hasAccess('wilayah-kerja'))
                                    <li class="submenu-item">
                                        <a class="sidebar-menu-link {{ request()->is('wilayah-kerja*') ? 'active' : '' }}"
                                            href="{{ route('wilayah-kerja.index') }}">
                                            <i class="fas fa-map"></i>
                                            <span class="sidebar-menu-text">Wilayah Kerja</span>
                                        </a>
                                    </li>
                                @endif

                                @if (Auth::user()->is_admin || Auth::user()->hasAccess('departemen'))
                                    <li class="submenu-item">
                                        <a class="sidebar-menu-link {{ request()->is('departemen*') ? 'active' : '' }}"
                                            href="{{ route('departemen.index') }}">
                                            <i class="fas fa-sitemap"></i>
                                            <span class="sidebar-menu-text">Departemen</span>
                                        </a>
                                    </li>
                                @endif

                                @if (Auth::user()->is_admin || Auth::user()->hasAccess('kontrak-kerja'))
                                    <li class="submenu-item">
                                        <a class="sidebar-menu-link {{ request()->is('kontrak-kerja*') ? 'active' : '' }}"
                                            href="{{ route('kontrak-kerja.index') }}">
                                            <i class="fas fa-file-contract"></i>
                                            <span class="sidebar-menu-text">Kontrak Kerja</span>
                                        </a>
                                    </li>
                                @endif

                                @if (Auth::user()->is_admin || Auth::user()->hasAccess('dokumen-karyawan'))
                                    <li class="submenu-item">
                                        <a class="sidebar-menu-link {{ request()->is('dokumen-karyawan*') ? 'active' : '' }}"
                                            href="{{ route('dokumen-karyawan.index') }}">
                                            <i class="fas fa-folder-open"></i>
                                            <span class="sidebar-menu-text">Dokumen Karyawan</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <!-- APPLICANT DATA DROPDOWN MENU -->
                    @if ($hasManajemenDataAccess)
                        <li class="sidebar-menu-item has-submenu" data-tooltip="Applicant Data">
                            <a class="sidebar-menu-link menu-dropdown {{ $manajemenDataActive ? 'active' : '' }}"
                                href="#" data-menu="manajemenData">
                                <i class="fas fa-user-tie"></i>
                                <span class="sidebar-menu-text">Manajemen Data</span>
                                <i
                                    class="fas fa-chevron-down submenu-indicator {{ $manajemenDataActive ? 'rotated' : '' }}"></i>
                            </a>
                            <ul class="sidebar-submenu {{ $manajemenDataActive ? 'show' : '' }}" id="manajemenData">
                                @if (Auth::user()->is_admin || Auth::user()->hasAccess('data-karyawan'))
                                    <li class="submenu-item">
                                        <a class="sidebar-menu-link {{ request()->is('data-karyawan*') ? 'active' : '' }}"
                                            href="{{ route('data-karyawan.index') }}">
                                            <i class="fas fa-address-card"></i>
                                            <span class="sidebar-menu-text">Data Karyawan</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->is_admin || Auth::user()->hasAccess('data-kontrak'))
                                    <li class="submenu-item">
                                        <a class="sidebar-menu-link {{ request()->is('data-kontrak*') ? 'active' : '' }}"
                                            href="{{ route('data-kontrak.index') }}">
                                            <i class="fas fa-scroll"></i>
                                            <span class="sidebar-menu-text">Data Kontrak</span>
                                        </a>
                                    </li>
                                @endif
                                @if (Auth::user()->is_admin || Auth::user()->hasAccess('data-dokumen'))
                                    <li class="submenu-item">
                                        <a class="sidebar-menu-link {{ request()->is('data-dokumen*') ? 'active' : '' }}"
                                            href="{{ route('data-dokumen.index') }}">
                                            <i class="fas fa-folder-open"></i>
                                            <span class="sidebar-menu-text">Data Dokumen</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif



                    <!-- Settings -->
                    <li class="sidebar-menu-item" data-tooltip="Pengaturan">
                        <a href=""
                            class="sidebar-menu-link {{ request()->is('settings.index') ? 'active' : '' }}">
                            <i class="fas fa-gear"></i>
                            <span class="sidebar-menu-text">Pengaturan</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            @yield('content')
        </main>
    </div>

    <!-- jQuery from CDN - MUST BE FIRST -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS - AFTER jQuery and Bootstrap -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Select2 JS from CDN - AFTER jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="{{ asset('js/auto-uppercase.js') }}"></script>

    <script>
        // Verify libraries are loaded
        console.log('jQuery loaded:', typeof jQuery !== 'undefined' ? jQuery.fn.jquery : 'NO');
        console.log('Select2 loaded:', typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined' ? 'YES' :
            'NO');
    </script>

    <script>
        class SidebarManager {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.mainContent = document.getElementById('mainContent');
                this.overlay = document.getElementById('sidebarOverlay');
                this.toggleBtn = document.getElementById('sidebarToggleBtn');
                this.isCollapsed = false;
                this.isMobile = window.innerWidth <= 768;

                this.init();
            }

            init() {
                this.loadState();
                this.setupEventListeners();
                this.checkScreenSize();
                this.updateToggleButton();
                this.initDropdowns();
            }

            setupEventListeners() {
                window.addEventListener('resize', () => {
                    this.checkScreenSize();
                });

                document.addEventListener('click', (event) => {
                    const userDropdown = document.getElementById('userDropdown');
                    const userMenuBtn = document.querySelector('.user-menu-btn');

                    if (userDropdown &&
                        !userMenuBtn?.contains(event.target) &&
                        !userDropdown.contains(event.target)) {
                        userDropdown.classList.remove('show');
                    }
                });
            }

            initDropdowns() {
                document.querySelectorAll('.menu-dropdown').forEach(toggle => {
                    toggle.addEventListener('click', (e) => {
                        e.preventDefault();

                        if (this.isMobile && this.isCollapsed) {
                            return;
                        }

                        const menuId = toggle.getAttribute('data-menu');
                        const submenu = document.getElementById(menuId);
                        const arrow = toggle.querySelector('.submenu-indicator');

                        if (submenu && arrow) {
                            document.querySelectorAll('.sidebar-submenu.show').forEach(menu => {
                                if (menu !== submenu) {
                                    menu.classList.remove('show');
                                    const otherArrow = document.querySelector(
                                        `[data-menu="${menu.id}"] .submenu-indicator`);
                                    if (otherArrow) otherArrow.classList.remove('rotated');
                                }
                            });

                            submenu.classList.toggle('show');
                            arrow.classList.toggle('rotated');
                        }
                    });
                });
            }

            checkScreenSize() {
                const wasMobile = this.isMobile;
                this.isMobile = window.innerWidth <= 768;

                if (wasMobile !== this.isMobile) {
                    if (this.isMobile) {
                        this.sidebar.classList.remove('collapsed');
                        this.mainContent.classList.remove('sidebar-collapsed');
                        this.closeMobileSidebar();
                        this.updateToggleButton();
                    } else {
                        this.sidebar.classList.remove('mobile-open');
                        this.overlay.classList.remove('show');
                        this.loadState();
                        this.updateToggleButton();
                    }
                }
            }

            toggle() {
                if (this.isMobile) {
                    this.toggleMobile();
                } else {
                    this.toggleDesktop();
                }
                this.updateToggleButton();
            }

            toggleMobile() {
                const isOpen = this.sidebar.classList.contains('mobile-open');

                if (isOpen) {
                    this.closeMobileSidebar();
                } else {
                    this.openMobileSidebar();
                }
            }

            openMobileSidebar() {
                this.sidebar.classList.add('mobile-open');
                this.overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            closeMobileSidebar() {
                this.sidebar.classList.remove('mobile-open');
                this.overlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            toggleDesktop() {
                this.isCollapsed = !this.isCollapsed;

                if (this.isCollapsed) {
                    this.sidebar.classList.add('collapsed');
                    this.mainContent.classList.add('sidebar-collapsed');
                    document.querySelectorAll('.sidebar-submenu.show').forEach(menu => {
                        menu.classList.remove('show');
                        const arrow = document.querySelector(`[data-menu="${menu.id}"] .submenu-indicator`);
                        if (arrow) arrow.classList.remove('rotated');
                    });
                } else {
                    this.sidebar.classList.remove('collapsed');
                    this.mainContent.classList.remove('sidebar-collapsed');
                }

                this.saveState();
            }

            updateToggleButton() {
                if (this.toggleBtn) {
                    if (!this.isMobile && this.isCollapsed) {
                        this.toggleBtn.classList.add('collapsed');
                    } else {
                        this.toggleBtn.classList.remove('collapsed');
                    }
                }
            }

            saveState() {
                if (!this.isMobile) {
                    localStorage.setItem('sidebarCollapsed', this.isCollapsed);
                }
            }

            loadState() {
                if (!this.isMobile) {
                    const savedState = localStorage.getItem('sidebarCollapsed');
                    if (savedState !== null) {
                        this.isCollapsed = savedState === 'true';

                        if (this.isCollapsed) {
                            this.sidebar.classList.add('collapsed');
                            this.mainContent.classList.add('sidebar-collapsed');
                        } else {
                            this.sidebar.classList.remove('collapsed');
                            this.mainContent.classList.remove('sidebar-collapsed');
                        }
                    }
                }
            }
        }

        let sidebarManager;

        document.addEventListener('DOMContentLoaded', () => {
            sidebarManager = new SidebarManager();
        });

        function toggleSidebar() {
            sidebarManager?.toggle();
        }

        function closeMobileSidebar() {
            sidebarManager?.closeMobileSidebar();
        }

        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            dropdown?.classList.toggle('show');
        }
    </script>

    @stack('scripts')
</body>

</html>
