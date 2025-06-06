<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - BookingCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="{{ asset('css/auth/login.css') }}" rel="stylesheet"/>
    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .hover-glow {
            transition: box-shadow 0.2s ease;
        }
        .hover-glow:hover {
            box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body>
    <div class="custom-container">
        <!-- Logo BookingCare -->
        <div class="py-4">
            <h1 class="text-2xl font-bold logo">BookingCare</h1>
        </div>

        <!-- Container chính chia 2 phần -->
        <main class="login-container">
            <!-- Bên trái: Hình ảnh -->
            <div class="login-image">
                <img src="{{ asset('images/home.png') }}" alt="Home Image">
            </div>

            <!-- Bên phải: Form đăng nhập -->
            <div class="login-form">
                <h2 class="text-2xl font-bold mb-6 text-center fade-in">Đăng nhập</h2>

                <!-- Hiển thị thông báo -->
                @if (session('success'))
                    <div class="bg-green-100 text-green-800 p-4 rounded mb-4 fade-in">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 text-red-800 p-4 rounded mb-4 fade-in">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 text-red-800 p-4 rounded mb-4 fade-in">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form đăng nhập -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-sm text-gray-600 mb-1">Email:</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="border rounded px-2 py-1 w-full hover-glow" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm text-gray-600 mb-1">Mật khẩu:</label>
                        <input type="password" id="password" name="password" class="border rounded px-2 py-1 w-full hover-glow" required>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Chưa có tài khoản? Đăng ký</a>
                        <a href="{{ route('password.request') }}" class="text-blue-500 hover:underline">Quên mật khẩu?</a>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600 hover-glow">Đăng nhập</button>
                </form>

                <!-- Nút đăng nhập bằng Google -->
                <div class="mt-4">
                    <a href="{{ route('redirect.google') }}" class="flex items-center justify-center bg-red-500 text-white px-4 py-2 rounded w-full hover:bg-red-600 hover-glow">
                        <i class="fab fa-google mr-2"></i> Đăng nhập bằng Google
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Thêm hiệu ứng fade-in nhẹ khi trang tải
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                }, index * 100);
            });
        });
    </script>
</body>
</html>