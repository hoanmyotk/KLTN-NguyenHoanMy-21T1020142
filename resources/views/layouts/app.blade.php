<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BookingCare - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        /* Thêm CSS tùy chỉnh */
        .drawer-transition {
            transition: transform 0.3s ease-in-out;
        }
        .drawer-open {
            transform: translateX(0);
        }
        .drawer-closed {
            transform: translateX(-100%);
        }
        .user-image {
            transition: transform 0.2s ease;
        }
        .user-image:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 font-sans">
    <!-- Alert thông báo -->
    @if (session('success'))
        <div id="alert-success" class="fixed top-4 right-4 z-50 flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 shadow-lg transition-opacity duration-500 opacity-100">
            <svg class="flex-shrink-0 w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @elseif (session('error'))
        <div id="alert-error" class="fixed top-4 right-4 z-50 flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 shadow-lg transition-opacity duration-500 opacity-100">
            <svg class="flex-shrink-0 w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <header class="bg-white shadow-lg py-4 sticky top-0 z-40">
        <div class="container mx-auto flex items-center justify-between px-6">
            <div class="flex items-center space-x-4">
                <button class="text-2xl text-gray-700 hover:text-blue-500 transition-colors" onclick="toggleDrawer()">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('home') }}" class="flex items-center">
                    <img alt="BookingCare logo" class="mr-2 transition-transform hover:scale-105" height="40" src="https://storage.googleapis.com/a1aa/image/B--P6XB6-uMf8sAAMBbU-KNlGngl7UeUkoE7tvh5Nhk.jpg" width="40"/>
                    <span class="text-2xl font-bold text-yellow-500 hover:text-yellow-600 transition-colors">BookingCare</span>
                </a>
            </div>
            <nav class="flex items-center space-x-6">
                <a class="text-yellow-500 font-semibold hover:text-yellow-600 transition-colors" href="{{ route('home') }}">Tất cả</a>
                <a class="text-gray-700 hover:text-blue-500 transition-colors" href="#">Tại nhà</a>
                <a class="text-gray-700 hover:text-blue-500 transition-colors" href="#">Tại viện</a>
                <a class="text-gray-700 hover:text-blue-500 transition-colors" href="#">Sống khỏe</a>
                <a class="text-gray-700 hover:text-blue-500 transition-colors" href="#"><i class="fas fa-handshake mr-1"></i> Hợp tác</a>
                @if (Auth::check())
                    @php
                        $user = Auth::user();
                    @endphp
                    <div class="relative group">
                        <div class="p-2 rounded-full hover:bg-gray-100 transition-colors cursor-pointer">
                            <img src="{{ $user->image ?? 'https://via.placeholder.com/40' }}" alt="User profile" class="user-image w-10 h-10 rounded-full border-2 border-blue-500">
                        </div>
                        <div class="absolute hidden group-hover:block bg-white shadow-lg rounded-md p-3 right-0 min-w-[140px] z-50">
                            <a class="block text-gray-700 hover:text-blue-500 text-sm py-1 px-2 rounded hover:bg-gray-100 transition-colors" href="{{ route('profile') }}"><i class="fas fa-user mr-1"></i> Hồ sơ</a>
                            <form action="{{ route('logout') }}" class="mt-2">
                                <button type="submit" class="text-gray-700 hover:text-blue-500 text-sm w-full text-left py-1 px-2 rounded hover:bg-gray-100 transition-colors"><i class="fas fa-sign-out-alt mr-1"></i> Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a class="text-gray-700 hover:text-blue-500 transition-colors" href="{{ route('login') }}">Đăng nhập</a>
                    <a class="text-gray-700 hover:text-blue-500 transition-colors" href="{{ route('register') }}">Đăng ký</a>
                @endif
            </nav>
        </div>
    </header>

    <!-- Phần Hỏi trợ lý AI -->
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-4xl font-bold text-center text-gray-800 mb-8 animate-fade-in">
            Hỏi nhanh, đáp chuẩn - Đặt khám dễ dàng
        </h1>
        <div class="bg-white rounded-xl shadow-xl p-6 mb-8 transform hover:scale-101 transition-transform duration-300">
            <form method="POST" action="{{ route('save.question') }}" class="space-y-4">
                @csrf
                <input class="w-full py-3 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Hỏi Trợ lý AI cách đặt lịch khám" type="text" name="question" required/>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-hospital mr-2"></i>
                        <span>Chọn Bệnh viện - phòng khám</span>
                    </div>
                    <button type="submit" class="text-blue-500 hover:text-blue-700 transition-colors"><i class="fas fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-300 p-6 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300">
                <ul class="space-y-4">
                    <li class="flex justify-between items-center hover:bg-blue-200 p-2 rounded-lg transition-colors">
                        <span class="text-gray-800">Bệnh nhân bị lở ở lưỡi đặt khám chuyên khoa nào tại Bệnh viện Chợ Rẫy</span>
                        <button class="text-gray-700 hover:text-blue-500 transition-colors">+</button>
                    </li>
                    <li class="flex justify-between items-center hover:bg-blue-200 p-2 rounded-lg transition-colors">
                        <span class="text-gray-800">Review về Bệnh viện Quốc tế Cần</span>
                        <button class="text-gray-700 hover:text-blue-500 transition-colors">+</button>
                    </li>
                    <li class="flex justify-between items-center hover:bg-blue-200 p-2 rounded-lg transition-colors">
                        <span class="text-gray-800">Nếu phát sinh vấn đề trong quá trình đi khám tại Bệnh viện Bạch Mai thì liên hệ ai?</span>
                        <button class="text-gray-700 hover:text-blue-500 transition-colors">+</button>
                    </li>
                </ul>
            </div>
            <div class="bg-blue-300 p-6 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300">
                <ul class="space-y-4">
                    <li class="flex justify-between items-center hover:bg-blue-200 p-2 rounded-lg transition-colors">
                        <span class="text-gray-800">Sơ đồ Bệnh viện Nhi đồng 1</span>
                        <button class="text-gray-700 hover:text-blue-500 transition-colors">+</button>
                    </li>
                    <li class="flex justify-between items-center hover:bg-blue-200 p-2 rounded-lg transition-colors">
                        <span class="text-gray-800">Lịch sử hình thành Bệnh viện Nhi Trung Ương</span>
                        <button class="text-gray-700 hover:text-blue-500 transition-colors">+</button>
                    </li>
                    <li class="flex justify-between items-center hover:bg-blue-200 p-2 rounded-lg transition-colors">
                        <span class="text-gray-800">Lịch khám bác sĩ khoa Tim mạch Can thiệp, Bệnh viện Nhân dân 115</span>
                        <button class="text-gray-700 hover:text-blue-500 transition-colors">+</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Drawer -->
    <div class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden drawer-transition" id="drawer" :class="{'drawer-open': open, 'drawer-closed': !open}">
        <div class="fixed inset-y-0 left-0 bg-white w-64 p-6 shadow-xl transform transition-transform duration-300">
            <button class="text-gray-700 text-2xl mb-6 hover:text-blue-500 transition-colors" onclick="toggleDrawer()">
                <i class="fas fa-times"></i>
            </button>
            <nav class="space-y-6">
                <a class="block text-gray-700 hover:text-blue-500 transition-colors" href="{{ route('home') }}">Tất cả</a>
                <a class="block text-gray-700 hover:text-blue-500 transition-colors" href="#">Tại nhà</a>
                <a class="block text-gray-700 hover:text-blue-500 transition-colors" href="#">Tại viện</a>
                <a class="block text-gray-700 hover:text-blue-500 transition-colors" href="#">Sống khỏe</a>
                <a class="block text-gray-700 hover:text-blue-500 transition-colors" href="#">Hợp tác</a>
                @if (Auth::check())
                    @php
                        $user = Auth::user();
                    @endphp
                    <a class="block text-gray-700 hover:text-blue-500 transition-colors" href="{{ route('profile') }}">{{ $user->firstName }} {{ $user->lastName }}</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-500 transition-colors w-full text-left">Đăng xuất</button>
                    </form>
                @else
                    <a class="block text-gray-700 hover:text-blue-500 transition-colors" href="{{ route('login') }}">Đăng nhập</a>
                    <a class="block text-gray-700 hover:text-blue-500 transition-colors" href="{{ route('register') }}">Đăng ký</a>
                @endif
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main class="bg-gradient-to-br from-blue-200 to-white py-12">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-inner py-8">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo and Brand -->
                <div>
                    <div class="flex items-center mb-4">
                        <img alt="BookingCare logo" class="mr-2 transition-transform hover:scale-105" height="40" src="https://storage.googleapis.com/a1aa/image/B--P6XB6-uMf8sAAMBbU-KNlGngl7UeUkoE7tvh5Nhk.jpg" width="40"/>
                        <span class="text-xl font-bold text-yellow-500 hover:text-yellow-600 transition-colors">BookingCare</span>
                    </div>
                    <p class="text-gray-600 text-sm">Đặt khám dễ dàng - Chăm sóc sức khỏe toàn diện</p>
                </div>

                <!-- Useful Links -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Liên kết hữu ích</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-blue-500 transition-colors text-sm">Về chúng tôi</a></li>
                        <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors text-sm"><li>Hỗ trợ</li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-500 transition-colors text-sm">Điều khoản sử dụng</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-blue-500 transition-colors text-sm">Chính sách bảo mật</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Liên hệ</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-600 text-sm flex items-center"><i class="fas fa-envelope mr-2"></i> support@bookingcare.com</li>
                        <li class="text-gray-600 text-sm flex items-center"><i class="fas fa-phone mr-2"></i> (+84) 123 456 789</li>
                        <li class="text-gray-600 text-sm flex items-center"><i class="fas fa-map-marker-alt mr-2"></i> 123 Đường Sức Khỏe, TP.HCM</li>
                    </ul>
                </div>

                <!-- Social Media -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Kết nối với chúng tôi</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors"><i class="fab fa-facebook-f text-xl"></i></a>
                        <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors"><i class="fab fa-twitter text-xl"></i></a>
                        <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors"><i class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-600 hover:text-blue-500 transition-colors"><i class="fab fa-youtube text-xl"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200 mt-6 pt-4 text-center">
                <p class="text-gray-600 text-sm">© 2025 BookingCare. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Xử lý toggle drawer
        function toggleDrawer() {
            const drawer = document.getElementById('drawer');
            drawer.classList.toggle('drawer-open');
            drawer.classList.toggle('drawer-closed');
        }

        // Xử lý alert tự động biến mất sau 5 giây
        document.addEventListener('DOMContentLoaded', function () {
            const successAlert = document.getElementById('alert-success');
            const errorAlert = document.getElementById('alert-error');

            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.opacity = '0';
                    setTimeout(() => successAlert.remove(), 500); // Xóa sau khi mờ dần
                }, 5000); // 5 giây
            }

            if (errorAlert) {
                setTimeout(() => {
                    errorAlert.style.opacity = '0';
                    setTimeout(() => errorAlert.remove(), 500); // Xóa sau khi mờ dần
                }, 5000); // 5 giây
            }
        });
    </script>
</body>
</html>