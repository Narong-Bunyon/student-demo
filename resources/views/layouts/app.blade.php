<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Student Management System') - {{ config('app.name', 'student-demo') }}</title>

    <!-- Vite Assets (Supports `npm run dev` and `npm run build`) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <span class="logo-icon me-2">
                <i class="bi bi-mortarboard-fill"></i>
            </span>
            <span>Student<span class="text-primary" style="color: #818cf8 !important;">Demo</span></span>
        </a>

        <div class="sidebar-nav">
            <div class="sidebar-heading">Main Menu</div>
            <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-heading mt-3">Academic</div>
            <a href="{{ route('courses.index') }}" class="nav-link-custom {{ request()->routeIs('courses.*') ? 'active' : '' }}">
                <i class="bi bi-journal-bookmark"></i>
                <span>Courses</span>
            </a>
            <a href="{{ route('students.index') }}" class="nav-link-custom {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Students</span>
            </a>

            <div class="sidebar-heading mt-3">Quick Actions</div>
            <a href="{{ route('students.create') }}" class="nav-link-custom">
                <i class="bi bi-person-plus"></i>
                <span>New Student</span>
            </a>
            <a href="{{ route('courses.create') }}" class="nav-link-custom">
                <i class="bi bi-folder-plus"></i>
                <span>New Course</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Container -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-light border d-lg-none" type="button" id="sidebarToggle">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h5 class="mb-0 fw-bold text-dark">@yield('page-title', 'Overview')</h5>
                    <small class="text-muted d-none d-sm-inline">Manage students and academic departments effortlessly</small>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-light rounded-pill dropdown-toggle d-flex align-items-center gap-2 px-3 py-1 border shadow-sm" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5 text-primary"></i>
                        <span class="fw-semibold small">{{ Auth::user()->name ?? 'Administrator' }}</span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle text-uppercase" style="font-size: 0.65rem;">
                            {{ Auth::user()->role ?? 'Admin' }}
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                        <li class="px-3 py-2 border-bottom">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <div class="fw-bold small">{{ Auth::user()->name ?? 'User' }}</div>
                                <span class="badge bg-primary text-white text-uppercase" style="font-size: 0.65rem;">
                                    {{ Auth::user()->role ?? 'Admin' }}
                                </span>
                            </div>
                            <div class="text-muted small" style="font-size: 0.75rem;">{{ Auth::user()->email ?? '' }}</div>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2">
                                    <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2 text-danger"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-2"></i>Please fix the following errors:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="text-center py-3 text-muted small border-top bg-white">
            &copy; {{ date('Y') }} Student Management System (student-demo) &bull; Built with Laravel 10 & Vite
        </footer>
    </div>

    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    @stack('scripts')
</body>
</html>
