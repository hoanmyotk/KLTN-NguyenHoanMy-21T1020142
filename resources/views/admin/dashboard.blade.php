@extends('layouts.admin_layout')

@section('title', 'Dashboard')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Dashboard</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#">Trang chủ</a>
                </li>
            </ul>
        </div>
        <a href="#" class="btn-download" style="padding: 10px 20px; background-color: var(--blue); color: var(--light); border-radius: 5px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <i class='bx bxs-cloud-download'></i>
            <span class="text">Tải báo cáo</span>
        </a>
    </div>

    <ul class="box-info" style="display: flex; gap: 20px; margin-bottom: 20px; list-style: none; padding: 0;">
        <li style="flex: 1; background-color: var(--light); padding: 15px; border-radius: 5px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <i class='bx bxs-calendar-check' style="font-size: 24px; color: var(--blue);"></i>
            <span class="text" style="display: block; margin-top: 10px;">
                <h3 style="font-size: 24px; color: var(--dark); margin: 0;">{{ $totalBookings }}</h3>
                <p style="font-size: 14px; margin: 5px 0 0;">Tổng lịch hẹn</p>
            </span>
        </li>
        <li style="flex: 1; background-color: var(--light); padding: 15px; border-radius: 5px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <i class='bx bxs-book' style="font-size: 24px; color: var(--blue);"></i>
            <span class="text" style="display: block; margin-top: 10px;">
                <h3 style="font-size: 24px; color: var(--dark); margin: 0;">{{ $totalSpecialties }}</h3>
                <p style="font-size: 14px; margin: 5px 0 0;">Chuyên khoa</p>
            </span>
        </li>
        <li style="flex: 1; background-color: var(--light); padding: 15px; border-radius: 5px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <i class='bx bxs-group' style="font-size: 24px; color: var(--blue);"></i>
            <span class="text" style="display: block; margin-top: 10px;">
                <h3 style="font-size: 24px; color: var(--dark); margin: 0;">{{ $totalUsers }}</h3>
                <p style="font-size: 14px; margin: 5px 0 0;">Người dùng</p>
            </span>
        </li>
    </ul>

    <div class="table-data" style="width: 100%;">
        <div class="order" style="width: 100%;">
            <div class="head">
                <h3>Lịch hẹn gần đây</h3>
                <i class='bx bx-search' style="margin-left: 10px; cursor: pointer;"></i>
                <i class='bx bx-filter' style="margin-left: 10px; cursor: pointer;"></i>
            </div>
            <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                <thead>
                    <tr style="background-color: var(--grey);">
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 200px;">Bệnh nhân</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 150px;">Ngày đặt</th>
                        <th style="padding: 12px 15px; text-align: left; color: var(--dark); font-weight: 600; min-width: 150px;">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentBookings as $booking)
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 12px 15px; vertical-align: middle;">
                                <p>{{ $booking->patientName ?? ($booking->patient ? $booking->patient->firstName . ' ' . $booking->patient->lastName : 'N/A') }}</p>
                            </td>
                            <td style="padding: 12px 15px; vertical-align: middle;">
                                {{ \Carbon\Carbon::parse($booking->date)->format('d-m-Y') }}
                            </td>
                            <td style="padding: 12px 15px; vertical-align: middle;">
                                <span class="status {{ $booking->statusId == 'S2' ? 'completed' : ($booking->statusId == 'S1' ? 'pending' : ($booking->statusId == 'S3' ? 'finished' : ($booking->statusId == 'S4' ? 'canceled' : 'process'))) }}" style="padding: 4px 12px; border-radius: 12px;">
                                    {{ $booking->status ? $booking->status->valueVi : ucfirst(str_replace(['S1', 'S2', 'S3', 'S4'], ['Pending', 'Completed', 'Hoàn thành', 'Đã hủy'], $booking->statusId)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 12px 15px; text-align: center; color: var(--grey);">Không có lịch hẹn nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection