@extends('layouts.admin_layout')

@section('title', 'Quản lý bệnh viện')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Quản lý bệnh viện</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Bệnh viện</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.clinics.create') }}" class="btn-download" style="padding: 10px 20px; background-color: var(--blue); color: var(--light); border-radius: 5px; text-decoration: none;">
            <i class='bx bx-plus'></i> Thêm bệnh viện
        </a>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Danh sách bệnh viện</h3>
            </div>
            <form method="GET" action="{{ route('admin.clinics.index') }}" class="filter-form" style="margin-bottom: 15px; display: flex; gap: 15px; align-items: flex-end;">
                <div style="flex: 1;">
                    <label for="name" style="display: block; margin-bottom: 5px; color: var(--dark);">Tên bệnh viện</label>
                    <input type="text" name="name" id="name" placeholder="Tìm theo tên bệnh viện" value="{{ request('name') }}" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                </div>
                <div style="flex: 1;">
                    <label for="address" style="display: block; margin-bottom: 5px; color: var(--dark);">Địa chỉ</label>
                    <input type="text" name="address" id="address" placeholder="Tìm theo địa chỉ" value="{{ request('address') }}" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                </div>
                <button type="submit" style="padding: 8px 16px; background-color: var(--blue); color: var(--light); border: none; border-radius: 5px; height: 38px;">Tìm kiếm</button>
            </form>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: var(--grey);">
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600;">Ảnh</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600;">Tên bệnh viện</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600;">Địa chỉ</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clinics as $clinic)
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 12px 15px; vertical-align: middle;">
                                @if ($clinic->image)
                                    <img src="{{ asset($clinic->image) }}" alt="Ảnh bệnh viện" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                                @else
                                    <span style="color: var(--grey);">N/A</span>
                                @endif
                            </td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $clinic->name }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $clinic->address }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle; display: flex; align-items: center; gap: 8px;">
                                <a href="{{ route('admin.clinics.edit', $clinic->id) }}" style="padding: 6px 12px; background-color: var(--yellow); color: var(--dark); border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class='bx bx-edit'></i> Sửa
                                </a>
                                <form action="{{ route('admin.clinics.destroy', $clinic->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 12px; background-color: var(--red); color: var(--light); border: none; border-radius: 5px; display: inline-flex; align-items: center; gap: 4px; cursor: pointer;" onclick="return confirm('Bạn có chắc muốn xóa bệnh viện này?')">
                                        <i class='bx bx-trash'></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 12px 15px; text-align: center; color: var(--grey);">Không có bệnh viện nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination" style="margin-top: 15px;">
                {{ $clinics->links() }}
            </div>
        </div>
    </div>
@endsection