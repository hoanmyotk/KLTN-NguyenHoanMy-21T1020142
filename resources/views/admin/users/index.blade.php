@extends('layouts.admin_layout')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Quản lý người dùng</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="{{ route('admin.users.index') }}">Người dùng</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Form bộ lọc -->
    <div class="table-data" style="width: 100%;">
        <div class="order" style="width: 100%;">
            <div class="head">
                <h3>Bộ lọc người dùng</h3>
            </div>
            <form method="GET" action="{{ route('admin.users.index') }}" style="margin-bottom: 20px;">
                <div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end;">
                    <div style="flex: 1; min-width: 200px;">
                        <label for="name" style="display: block; margin-bottom: 5px; color: var(--dark);">Tên</label>
                        <input type="text" name="name" id="name" value="{{ request('name') }}" placeholder="Nhập tên..." style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <label for="email" style="display: block; margin-bottom: 5px; color: var(--dark);">Email</label>
                        <input type="email" name="email" id="email" value="{{ request('email') }}" placeholder="Nhập email..." style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                    </div>
                    <div style="flex: 1; min-width: 200px;">
                        <label for="roleId" style="display: block; margin-bottom: 5px; color: var(--dark);">Vai trò</label>
                        <select name="roleId" id="roleId" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                            <option value="">Tất cả</option>
                            <option value="R1" {{ request('roleId') == 'R1' ? 'selected' : '' }}>Admin</option>
                            <option value="R2" {{ request('roleId') == 'R2' ? 'selected' : '' }}>Bác sĩ</option>
                            <option value="R3" {{ request('roleId') == 'R3' ? 'selected' : '' }}>Bệnh nhân</option>
                        </select>
                    </div>
                    <div style="align-self: flex-end;">
                        <button type="submit" style="padding: 10px 20px; background-color: var(--blue); color: var(--light); border: none; border-radius: 5px; cursor: pointer; height: 38px;">
                            Lọc
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bảng danh sách người dùng -->
    <div class="table-data" style="width: 100%;">
        <div class="order" style="width: 100%;">
            <div class="head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3>Danh sách người dùng</h3>
                <a href="{{ route('admin.users.create') }}" style="padding: 8px 16px; background-color: var(--blue); color: var(--light); border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-size: 14px;">
                    <i class='bx bx-plus'></i> Thêm người dùng
                </a>
            </div>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background-color: var(--grey);">
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 100px;">Ảnh đại diện</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 150px;">Tên</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 200px;">Email</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 100px;">Giới tính</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 150px;">Số điện thoại</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 120px;">Vai trò</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 200px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $item)
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 12px 15px; vertical-align: middle;">
                                @if ($item->image)
                                    <img src="{{ asset($item->image) }}" alt="Ảnh đại diện" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                @else
                                    <span style="color: var(--grey);">N/A</span>
                                @endif
                            </td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $item->firstName }} {{ $item->lastName }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $item->email }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $item->genderRelation ? $item->genderRelation->valueVi : 'N/A' }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $item->phonenumber }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle;">
                                {{ $item->roleId == 'R1' ? 'Admin' : ($item->roleId == 'R2' ? 'Bác sĩ' : 'Bệnh nhân') }}
                            </td>
                            <td style="padding: 12px 15px; vertical-align: middle; display: flex; align-items: center; gap: 8px;">
                                <a href="{{ route('admin.users.edit', $item->id) }}" style="padding: 6px 12px; background-color: var(--yellow); color: var(--dark); border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class='bx bx-edit'></i> Sửa
                                </a>
                                <form action="{{ route('admin.users.destroy', $item->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 12px; background-color: var(--red); color: var(--light); border: none; border-radius: 5px; display: inline-flex; align-items: center; gap: 4px; cursor: pointer;" onclick="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                        <i class='bx bx-trash'></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 12px 15px; text-align: center; color: var(--grey);">Không có người dùng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Phân trang -->
            <div class="pagination" style="margin-top: 15px;">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection