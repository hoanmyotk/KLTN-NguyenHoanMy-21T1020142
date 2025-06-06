<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - BookingCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="{{ asset('css/auth/login.css') }}" rel="stylesheet"/>
</head>
<body>
    <div class="custom-container">
        <!-- Logo BookingCare -->
        <div class="py-4">
            <h1 class="text-2xl font-bold logo">BookingCare</h1>
        </div>

        <main class="login-container">
            <!-- Bên trái: Hình ảnh -->
            <div class="login-image">
                <img src="{{ asset('images/home.png') }}" alt="Home Image">
            </div>

            <!-- Bên phải: Form quên mật khẩu -->
            <div class="login-form">
                <h2 class="text-2xl font-bold mb-6 text-center">Quên mật khẩu</h2>

                @if (session('success'))
                    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-4">
                        {{ session('info') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.send-reset-link') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-sm text-gray-600 mb-1">Email:</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="border rounded px-2 py-1 w-full" required>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600">
                        Gửi liên kết đặt lại mật khẩu
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Quay lại đăng nhập</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>