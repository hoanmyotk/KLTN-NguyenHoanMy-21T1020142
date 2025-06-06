@extends('layouts.admin_layout')

@section('title', 'Hồ sơ cá nhân')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Hồ sơ cá nhân</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center mb-6">
                <img src="{{ Auth::user()->image ?? 'https://via.placeholder.com/150' }}" alt="Profile Image" class="w-32 h-32 rounded-full object-cover mr-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">{{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</h2>
                    <p class="text-gray-600">Vai trò: {{ Auth::user()->roleRelation->value ?? 'Chưa xác định' }}</p>
                </div>
            </div>

            <!-- Form chỉnh sửa thông tin -->
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Hiển thị thông tin không chỉnh sửa được -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Email</label>
                        <input type="text" value="{{ Auth::user()->email }}" class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" readonly>
                    </div>
                </div>

                <!-- Form chỉnh sửa thông tin -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Họ</label>
                        <input type="text" name="firstName" value="{{ old('firstName', Auth::user()->firstName) }}" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Tên</label>
                        <input type="text" name="lastName" value="{{ old('lastName', Auth::user()->lastName) }}" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Địa chỉ</label>
                        <input type="text" name="address" value="{{ old('address', Auth::user()->address) }}" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Số điện thoại</label>
                        <input type="text" name="phonenumber" value="{{ old('phonenumber', Auth::user()->phonenumber) }}" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Giới tính</label>
                        <select name="gender" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="" disabled {{ !Auth::user()->gender ? 'selected' : '' }}>Chọn giới tính</option>
                            @foreach (['MALE', 'FEMALE', 'OTHER'] as $genderKey)
                                <option value="{{ $genderKey }}" {{ Auth::user()->gender === $genderKey ? 'selected' : '' }}>
                                    {{ Auth::user()->genderRelation->value ?? $genderKey }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Vai trò</label>
                        <input type="text" value="{{ Auth::user()->roleRelation->value ?? 'Chưa xác định' }}" class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Ảnh đại diện</label>
                        <input type="file" name="image" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">Cập nhật thông tin</button>
            </form>
        </div>

        <!-- Form đổi mật khẩu -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Đổi mật khẩu</h2>
            <form method="POST" action="{{ route('profile.change-password') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Mật khẩu cũ</label>
                        <input type="password" name="current_password" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
@endsection