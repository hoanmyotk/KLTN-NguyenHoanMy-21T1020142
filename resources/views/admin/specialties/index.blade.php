@extends('layouts.admin_layout')

@section('title', 'Quản lý chuyên khoa')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Quản lý chuyên khoa</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Chuyên khoa</a></li>
            </ul>
        </div>
        <a href="{{ route('admin.specialties.create') }}" class="btn-download" style="padding: 10px 20px; background-color: var(--blue); color: var(--light); border-radius: 5px; text-decoration: none;">
            <i class='bx bx-plus'></i> Thêm chuyên khoa
        </a>
    </div>

    <div class="table-data" style="width: 100%;">
        <div class="order" style="width: 100%;">
            <div class="head">
                <h3>Danh sách chuyên khoa</h3>
            </div>
            @if (session('success'))
                <div class="alert alert-success" style="padding: 10px; margin-bottom: 15px; background-color: #d4edda; color: #155724;">
                    {{ session('success') }}
                </div>
            @endif
            <form method="GET" action="{{ route('admin.specialties.index') }}" class="filter-form" style="margin-bottom: 15px; display: flex; gap: 15px; align-items: flex-end;">
                <div style="flex: 1;">
                    <label for="name" style="display: block; margin-bottom: 5px; color: var(--dark);">Tên chuyên khoa</label>
                    <input type="text" name="name" id="name" placeholder="Tìm theo tên chuyên khoa" value="{{ request('name') }}" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                </div>
                <button type="submit" style="padding: 8px 16px; background-color: var(--blue); color: var(--light); border: none; border-radius: 5px; height: 38px;">Tìm kiếm</button>
            </form>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: var(--grey);">
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 100px;">Ảnh</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 300px;">Tên chuyên khoa</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 200px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($specialties as $specialty)
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 12px 15px; vertical-align: middle;">
                                @if ($specialty->image)
                                    <img src="{{ asset($specialty->image) }}" alt="Ảnh chuyên khoa" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                                @else
                                    <span style="color: var(--grey);">N/A</span>
                                @endif
                            </td>
                            <td style="padding: 12px 15px; vertical-align: middle;">{{ $specialty->name }}</td>
                            <td style="padding: 12px 15px; vertical-align: middle; display: flex; align-items: center; gap: 8px;">
                                <a href="{{ route('admin.specialties.edit', $specialty->id) }}" style="padding: 6px 12px; background-color: var(--yellow); color: var(--dark); border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                    <i class='bx bx-edit'></i> Sửa
                                </a>
                                <form action="{{ route('admin.specialties.destroy', $specialty->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 12px; background-color: var(--red); color: var(--light); border: none; border-radius: 5px; display: inline-flex; align-items: center; gap: 4px; cursor: pointer;" onclick="return confirm('Bạn có chắc muốn xóa chuyên khoa này?')">
                                        <i class='bx bx-trash'></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 12px 15px; text-align: center; color: var(--grey);">Không có chuyên khoa nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="pagination" style="margin-top: 15px;">
                {{ $specialties->links() }}
            </div>
        </div>
    </div>
@endsection