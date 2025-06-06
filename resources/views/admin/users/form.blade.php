@extends('layouts.admin_layout')

@section('title', isset($user) ? 'Sửa người dùng' : 'Thêm người dùng')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>{{ isset($user) ? 'Sửa người dùng' : 'Thêm người dùng' }}</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a href="{{ route('admin.users.index') }}">Người dùng</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#">{{ isset($user) ? 'Sửa' : 'Thêm' }}</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Form {{ isset($user) ? 'sửa' : 'thêm' }} người dùng</h3>
            </div>
            @if (session('success'))
                <div class="alert alert-success" style="padding: 10px; margin-bottom: 15px; background-color: #d4edda; color: #155724;">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error" style="padding: 10px; margin-bottom: 15px; background-color: #f8d7da; color: #721c24;">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user->id) : route('admin.users.store') }}" enctype="multipart/form-data">
                @csrf
                @if (isset($user))
                    @method('PUT')
                @endif
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="firstName" style="display: block; margin-bottom: 5px; color: var(--dark);">Họ</label>
                    <input type="text" name="firstName" id="firstName" value="{{ old('firstName', isset($user) ? $user->firstName : '') }}" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                    @error('firstName')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="lastName" style="display: block; margin-bottom: 5px; color: var(--dark);">Tên</label>
                    <input type="text" name="lastName" id="lastName" value="{{ old('lastName', isset($user) ? $user->lastName : '') }}" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                    @error('lastName')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="email" style="display: block; margin-bottom: 5px; color: var(--dark);">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', isset($user) ? $user->email : '') }}" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                    @error('email')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="password" style="display: block; margin-bottom: 5px; color: var(--dark);">
                        Mật khẩu {{ isset($user) ? '(để trống nếu không đổi)' : '' }}
                    </label>
                    <input type="password" name="password" id="password" {{ isset($user) ? '' : 'required' }} style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                    @error('password')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="gender" style="display: block; margin-bottom: 5px; color: var(--dark);">Giới tính</label>
                    <select name="gender" id="gender" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                        <option value="">Chọn giới tính</option>
                        @foreach (\App\Models\Allcode::where('type', 'GENDER')->get() as $gender)
                            <option value="{{ $gender->keyMap }}" {{ old('gender', isset($user) ? $user->gender : '') == $gender->keyMap ? 'selected' : '' }}>
                                {{ $gender->valueVi }}
                            </option>
                        @endforeach
                    </select>
                    @error('gender')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="phonenumber" style="display: block; margin-bottom: 5px; color: var(--dark);">Số điện thoại</label>
                    <input type="text" name="phonenumber" id="phonenumber" value="{{ old('phonenumber', isset($user) ? $user->phonenumber : '') }}" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                    @error('phonenumber')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="image" style="display: block; margin-bottom: 5px; color: var(--dark);">
                        Ảnh đại diện {{ isset($user) ? '(để trống nếu không đổi)' : '' }}
                    </label>
                    @if (isset($user) && $user->image)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset($user->image) }}" alt="Ảnh đại diện" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                        </div>
                    @endif
                    <input type="file" name="image" id="image" accept="image/*" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                    @error('image')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="roleId" style="display: block; margin-bottom: 5px; color: var(--dark);">Vai trò</label>
                    <select name="roleId" id="roleId" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                        <option value="R1" {{ old('roleId', isset($user) ? $user->roleId : '') == 'R1' ? 'selected' : '' }}>Admin</option>
                        <option value="R2" {{ old('roleId', isset($user) ? $user->roleId : '') == 'R2' ? 'selected' : '' }}>Bác sĩ</option>
                        <option value="R3" {{ old('roleId', isset($user) ? $user->roleId : 'R3') == 'R3' ? 'selected' : '' }}>Bệnh nhân</option>
                    </select>
                    @error('roleId')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" style="padding: 10px 20px; background-color: var(--blue); color: var(--light); border: none; border-radius: 5px; cursor: pointer;">
                    {{ isset($user) ? 'Cập nhật' : 'Thêm người dùng' }}
                </button>
            </form>
        </div>
    </div>
@endsection