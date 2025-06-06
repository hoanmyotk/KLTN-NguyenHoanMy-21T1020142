@extends('layouts.admin_layout')

@section('title', 'Quản lý khám bệnh')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">Quản lý khám bệnh</h1>
        <!-- Input chọn ngày -->
        <div class="date-filter mb-8">
            <form method="GET" action="{{ route('admin.schedules.manage') }}" class="flex items-center space-x-4">
                <label for="date" class="text-lg font-semibold text-gray-800">Chọn ngày:</label>
                <select id="date" name="date" class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 hover:border-blue-300 transition-all">
                    @foreach ($workingDays as $day)
                        <option value="{{ $day }}" {{ $selectedDate == $day ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($day)->format('d/m/Y (D)') }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Lọc</button>
            </form>
        </div>

        <!-- Bảng hiển thị ca khám -->
        <div class="table-data">
            <div class="schedule-table">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Danh sách ca khám (Ngày: {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }})</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Thời gian khám</th>
                            <th>Tên bệnh nhân</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Giới tính</th>
                            <th>Lý do khám</th>
                            <th>Chức năng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->timeType }}</td>
                                <td>{{ $booking->patient->firstName }} {{ $booking->patient->lastName }}</td>
                                <td>{{ $booking->patient->email }}</td>
                                <td>{{ $booking->patient->phonenumber ?? 'Chưa cập nhật' }}</td>
                                <td>{{ $booking->patient->genderRelation->value ?? 'Chưa cập nhật' }}</td>
                                <td>{{ $booking->reason ?? 'Không có' }}</td>
                                <td>
                                    <span class="action-btn view">Xem</span>
                                    <a href="{{ route('admin.schedules.create-prescription', $booking->id) }}" class="action-btn create">Tạo</a>
                                    <span class="action-btn send" data-booking-id="{{ $booking->id }}" data-email="{{ $booking->patient->email }}" onclick="openSendModal(this)">Gửi</span>
                                    <span class="action-btn cancel">Hủy</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty">Không có ca khám nào trong ngày này.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal gửi email -->
    <div id="sendModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Gửi đơn thuốc</h2>
            <form id="sendForm" action="{{ route('admin.schedules.send-prescription') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="booking_id" id="modalBookingId">
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Email bệnh nhân:</label>
                    <input type="email" id="modalEmail" name="email" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none" readonly>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Chọn file đơn thuốc:</label>
                    <input type="file" name="prescription_file" class="w-full p-2 border border-gray-300 rounded-lg" accept=".txt,.pdf" required>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors" onclick="closeSendModal()">Hủy</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Gửi</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openSendModal(element) {
            const bookingId = element.getAttribute('data-booking-id');
            const email = element.getAttribute('data-email');
            document.getElementById('modalBookingId').value = bookingId;
            document.getElementById('modalEmail').value = email;
            document.getElementById('sendModal').classList.remove('hidden');
        }

        function closeSendModal() {
            document.getElementById('sendModal').classList.add('hidden');
            document.getElementById('sendForm').reset();
        }
    </script>
@endsection