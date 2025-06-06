<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">
    
    <title>BookingCare - Admin @yield('title')</title>
</head>
<body>
    <!-- Alert thông báo -->
    @if (session('success'))
        <div id="alert-success" class="fixed top-4 right-4 z-[100] flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 shadow-lg transition-opacity duration-500 opacity-100" style="margin-top: 60px;">
            <svg class="flex-shrink-0 w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @elseif (session('error'))
        <div id="alert-error" class="fixed top-4 right-4 z-[100] flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 shadow-lg transition-opacity duration-500 opacity-100" style="margin-top: 60px;">
            <svg class="flex-shrink-0 w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <i class='bx bxs-clinic'></i>
            <span class="text">BookingCare Admin</span>
        </a>
        <ul class="side-menu top">
            @if (Auth::check() && Auth::user()->roleId !== 'R2')
                <!-- Hiển thị đầy đủ chức năng cho Admin -->
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class='bx bxs-dashboard'></i>
                        <span class="text">Dashboard</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}">
                        <i class='bx bxs-group'></i>
                        <span class="text">Người dùng</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.schedules.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.schedules.create') }}">
                        <i class='bx bxs-calendar-check'></i>
                        <span class="text">Lịch hẹn</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.clinics.index') }}">
                        <i class='bx bx-clinic'></i>
                        <span class="text">Quản lý bệnh viện</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.specialties.index') }}">
                        <i class='bx bx-book-alt'></i>
                        <span class="text">Quản lý chuyên khoa</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.doctors.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.doctors.index') }}">
                        <i class='bx bxs-user'></i>
                        <span class="text">Bác sĩ</span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class='bx bxs-group'></i>
                        <span class="text">Bệnh nhân</span>
                    </a>
                </li>
            @else
                <!-- Hiển thị chức năng cho Bác sĩ (roleId = R2) -->
                <li class="{{ request()->routeIs('admin.schedules.manage') ? 'active' : '' }}">
                    <a href="">
                        <i class='bx bxs-calendar-check'></i>
                        <span class="text">Quản lý ca khám bệnh</span>
                    </a>
                </li>
            @endif
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Cài đặt</span>
                </a>
            </li>
            <li>
                <form action="{{ route('logout') }}">
                    @csrf
                    <a href="#" class="logout" onclick="this.closest('form').submit()">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text">Đăng xuất</span>
                    </a>
                </form>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link">Danh mục</a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Tìm kiếm...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">0</span>
            </a>
            <a href="{{ route('profile') }}" class="profile">
                @if (Auth::check() && Auth::user()->image)
                    <img src="{{ asset(Auth::user()->image) }}" alt="Profile Image">
                @else
                    <img src="{{ asset('images/users/avt.jpg') }}" alt="Default Avatar">
                @endif
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            @yield('content')
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Custom JS -->
    <script src="{{ asset('js/admin/script.js') }}"></script>
</body>
</html>