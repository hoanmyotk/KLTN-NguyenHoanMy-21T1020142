@extends('layouts.admin_layout')

@section('title', 'Thêm lịch khám')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Thêm lịch khám</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="{{ route('admin.schedules.create') }}">Thêm lịch khám</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Form thêm lịch khám</h3>
            </div>
            <!-- Form thêm lịch -->
            <form method="POST" action="{{ route('admin.schedules.store') }}">
                @csrf

                <!-- Chọn bác sĩ -->
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="doctorId" style="display: block; margin-bottom: 5px; color: var(--dark);">Chọn bác sĩ:</label>
                    <select name="doctorId" id="doctorId" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                        <option value="">-- Chọn bác sĩ --</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->doctorId }}">
                                {{ $doctor->user->firstName }} {{ $doctor->user->lastName }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctorId')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Chọn ngày -->
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="date" style="display: block; margin-bottom: 5px; color: var(--dark);">Chọn ngày:</label>
                    <select name="date" id="date" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                        <option value="">-- Chọn ngày --</option>
                        @foreach ($workingDays as $day)
                            <option value="{{ $day }}">
                                {{ \Carbon\Carbon::parse($day)->format('d/m/Y') }} ({{ \Carbon\Carbon::parse($day)->translatedFormat('l') }})
                            </option>
                        @endforeach
                    </select>
                    @error('date')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Chọn khung giờ -->
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: var(--dark);">Chọn khung giờ:</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        @foreach ($timeTypes as $timeType)
                            <label style="margin-right: 15px; color: var(--dark);">
                                <input type="checkbox" name="timeTypes[]" value="{{ $timeType->keyMap }}">
                                {{ $timeType->valueVi }}
                            </label>
                        @endforeach
                    </div>
                    @error('timeTypes')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Nút submit -->
                <button type="submit" style="padding: 10px 20px; background-color: var(--blue); color: var(--light); border: none; border-radius: 5px; cursor: pointer;">
                    Thêm lịch
                </button>
            </form>
        </div>
    </div>
@endsection