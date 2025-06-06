@extends('layouts.admin_layout')

@section('title', 'Quản lý bác sĩ')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Quản lý bác sĩ</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="{{ route('admin.doctors.index') }}">Bác sĩ</a>
                </li>
            </ul>
        </div>
        <a href="{{ route('admin.doctors.create') }}" style="padding: 10px 20px; background-color: var(--blue); color: var(--light); border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <i class='bx bx-plus'></i> Thêm bác sĩ
        </a>
    </div>

    <!-- Form bộ lọc -->
    <div class="table-data" style="width: 100%; max-width: 100%;">
        <div class="order" style="width: 100%; max-width: 100%;">
            <div class="head">
                <h3>Bộ lọc bác sĩ</h3>
            </div>
            <form method="GET" action="{{ route('admin.doctors.index') }}" style="margin-bottom: 20px;">
                <div style="display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end;">
                    <div style="flex: 1; min-width: 200px; max-width: 100%;">
                        <label for="name" style="display: block; margin-bottom: 5px; color: var(--dark);">Tên</label>
                        <input type="text" name="name" id="name" value="{{ request('name') }}" placeholder="Nhập tên..." style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                    </div>
                    <div style="flex: 1; min-width: 200px; max-width: 100%;">
                        <label for="email" style="display: block; margin-bottom: 5px; color: var(--dark);">Email</label>
                        <input type="email" name="email" id="email" value="{{ request('email') }}" placeholder="Nhập email..." style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
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

    <!-- Bảng danh sách bác sĩ -->
    <div class="table-data" style="width: 100%; max-width: 100%; overflow-x: auto;">
        <div class="order" style="width: 100%; max-width: 100%;">
            <div class="head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3>Danh sách bác sĩ</h3>
                <!-- Nút "Thêm bác sĩ" đã được di chuyển lên head-title -->
            </div>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px; min-width: 800px;">
                <thead>
                    <tr style="background-color: var(--grey);">
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 100px;">Ảnh đại diện</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 150px;">Tên</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 200px;">Email</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 100px;">Giới tính</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 150px;">Số điện thoại</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 120px;">Giá khám</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 120px;">Tỉnh</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 150px;">Phương thức thanh toán</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 200px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($doctors as $doctor)
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 12px 15px; vertical-align: middle;">
                                @if ($doctor->user->image)
                                    <img src="{{ asset($doctor->user->image) }}" alt="Ảnh đại diện" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                @else
                                    <span style="color: var(--grey);">N/A</span>
                                @endif
                            </td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $doctor->user->firstName }} {{ $doctor->user->lastName }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $doctor->user->email }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $doctor->user->genderRelation ? $doctor->user->genderRelation->valueVi : 'N/A' }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $doctor->user->phonenumber }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $doctor->priceRelation ? $doctor->priceRelation->valueVi : 'N/A' }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $doctor->provinceRelation ? $doctor->provinceRelation->valueVi : 'N/A' }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $doctor->paymentRelation ? $doctor->paymentRelation->valueVi : 'N/A' }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle; display: flex; align-items: center; gap: 8px;">
                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" style="padding: 6px 12px; background-color: var(--yellow); color: var(--dark); border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class='bx bx-edit'></i> Sửa
                                </a>
                                <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 12px; background-color: var(--red); color: var(--light); border: none; border-radius: 5px; display: inline-flex; align-items: center; gap: 4px; cursor: pointer;" onclick="return confirm('Bạn có chắc muốn xóa bác sĩ này?')">
                                        <i class='bx bx-trash'></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="padding: 12px 15px; text-align: center; color: var(--grey);">Không có bác sĩ nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Thêm phân trang nếu cần -->
            @if ($doctors->hasPages())
                <div class="pagination" style="margin-top: 15px;">
                    {{ $doctors->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection