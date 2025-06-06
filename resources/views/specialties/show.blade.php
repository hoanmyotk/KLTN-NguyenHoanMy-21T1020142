@extends('layouts.app')

@section('title', $specialty->name)

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Breadcrumb đơn giản -->
        <nav class="text-sm text-gray-600 mb-6" aria-label="Breadcrumb">
            <ol class="flex space-x-2">
                <li><span class="font-medium">{{ $specialty->name }}</span></li>
            </ol>
        </nav>

        <!-- Tên chuyên khoa -->
        <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ $specialty->name }}</h2>

        <!-- Mô tả chuyên khoa -->
        <section class="prose mb-8 max-w-prose text-gray-700" style="overflow-wrap: break-word;">{!! $specialty->descriptionHTML !!}</section>

        <!-- Danh sách bác sĩ -->
        @if($specialty->doctors->isEmpty())
            <p class="text-gray-700 mb-6">Không có bác sĩ nào thuộc chuyên khoa này.</p>
        @else
            <div class="grid gap-6">
                @foreach($doctorsData as $doctorData)
                    @php
                        $doctor = $doctorData['doctor'];
                        $dates = $doctorData['dates'];
                        $selectedDate = $doctorData['selectedDate'];
                        $doctorName = $doctor->user ? "{$doctor->user->firstName} {$doctor->user->lastName}" : 'N/A';
                    @endphp
                    <div class="bg-white shadow-md rounded-lg p-4 hover:shadow-lg transition-shadow duration-200" 
                         data-doctor-id="{{ $doctor->doctorId }}"
                         data-doctor-name="{{ $doctorName }}">
                        <!-- Thông tin bác sĩ -->
                        <div class="flex items-center mb-4">
                            <img src="{{ asset($doctor->user->image ?? 'default-avatar.png') }}" alt="Doctor's profile picture" class="w-12 h-12 rounded-full mr-3 object-cover">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800">
                                    {{ $doctorName }}
                                </h4>
                                <p class="text-sm text-gray-600">{{ $doctor->user->address ?? 'Hà Nội' }}</p>
                            </div>
                        </div>

                        <!-- Lịch làm việc -->
                        <div class="mb-4">
                            <h5 class="text-lg font-semibold text-gray-800 mb-2">Lịch làm việc</h5>
                            @if($doctor->schedules->isEmpty())
                                @php
                                    $startDate = now();
                                    $endDate = now()->addDays(5);
                                @endphp
                                <p class="text-gray-600 text-sm">
                                    Không có lịch từ {{ $startDate->format('d/m/Y') }} đến {{ $endDate->format('d/m/Y') }}.
                                </p>
                            @else
                                <div class="flex items-center mb-4">
                                    <label for="date-select-{{ $doctor->doctorId }}" class="text-sm text-gray-600 mr-2">Chọn ngày:</label>
                                    <select id="date-select-{{ $doctor->doctorId }}" class="border rounded px-2 py-1 ml-2 w-full max-w-xs" data-specialty-id="{{ $specialty->id }}" data-doctor-id="{{ $doctor->doctorId }}">
                                        @foreach($dates as $date)
                                            <option value="{{ $date }}" {{ $selectedDate == $date ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::parse($date)->locale('vi')->translatedFormat('l - d/m') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="schedule-container-{{ $doctor->doctorId }}" class="mb-4">
                                    <!-- Lịch sẽ được tải bằng AJAX -->
                                    <p class="text-gray-600 text-sm">Đang tải lịch...</p>
                                </div>
                            @endif
                        </div>

                        <!-- Thông tin phòng khám và giá -->
                        <div class="text-sm text-gray-600">
                            <p class="font-semibold">Địa chỉ khám:</p>
                            <p class="text-blue-500 hover:underline">{{ $doctor->nameClinic ?? 'Phòng khám Spinetech Clinic' }}</p>
                            <p>{{ $doctor->addressClinic ?? 'Tòa nhà GP, 257 Giải Phóng, Đống Đa, Hà Nội' }}</p>
                            <p class="mt-2"><span class="font-semibold">Giá khám:</span> {{ $doctor->priceRelation->valueVi ?? '500.000đ' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Modal đặt lịch -->
        <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center h-full">
                <div class="bg-white p-6 rounded-lg w-full max-w-md">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Đặt lịch khám</h2>
                        <button onclick="closeBookingModal()" class="text-gray-600 text-2xl">×</button>
                    </div>
                    <div class="mb-4 text-sm text-gray-600">
                        <p><strong>Bác sĩ:</strong> <span id="modalDoctorName"></span></p>
                        <p><strong>Lịch khám:</strong> <span id="modalScheduleDate"></span> - <span id="modalScheduleTime"></span></p>
                        <p><strong>Giá khám:</strong> <span id="modalPrice"></span></p>
                        <p><strong>Họ tên:</strong> {{ Auth::user()->firstName }} {{ Auth::user()->lastName }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>Số điện thoại:</strong> {{ Auth::user()->phonenumber ?? 'Chưa cập nhật' }}</p>
                        <p><strong>Địa chỉ:</strong> {{ Auth::user()->address ?? 'Chưa cập nhật' }}</p>
                        <p><strong>Giới tính:</strong> {{ Auth::user()->genderRelation->valueVi ?? 'Chưa cập nhật' }}</p>
                    </div>
                    <form id="bookingForm" method="POST" action="">
                        @csrf
                        <input type="hidden" name="_method" value="POST">
                        <div class="mb-3">
                            <label for="reason" class="block text-sm text-gray-600 mb-1">Lý do khám:</label>
                            <textarea id="reason" name="reason" class="border rounded px-2 py-1 w-full" rows="3" placeholder="Nhập lý do khám (tùy chọn)"></textarea>
                        </div>
                        <div class="flex space-x-2">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Đặt lịch</button>
                            <button type="button" onclick="closeBookingModal()" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Hủy</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript cho modal và AJAX -->
    <script>
        function openBookingModal(scheduleDate, scheduleTime, price, doctorId, scheduleId) {
            // Lấy tên bác sĩ từ thẻ cha gần nhất
            const doctorContainer = document.querySelector(`div[data-doctor-id="${doctorId}"]`);
            const doctorName = doctorContainer ? doctorContainer.getAttribute('data-doctor-name') : 'N/A';
            
            console.log('Opening modal:', { doctorName, scheduleDate, scheduleTime, price, doctorId, scheduleId }); // Debug
            document.getElementById('modalDoctorName').textContent = doctorName;
            document.getElementById('modalScheduleDate').textContent = scheduleDate;
            document.getElementById('modalScheduleTime').textContent = scheduleTime;
            document.getElementById('modalPrice').textContent = price;
            const form = document.getElementById('bookingForm');
            form.action = `/book/${scheduleId}`; // Đặt action động dựa trên scheduleId
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.value = 'POST'; // Kiểm tra trước khi gán
            document.getElementById('bookingModal').classList.remove('hidden');
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
            document.getElementById('bookingForm').reset();
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('bookingModal')) {
                closeBookingModal();
            }
        }

        // Hàm lấy lịch qua AJAX
        function fetchSchedules(specialtyId, doctorId, selectedDate, containerId) {
            const container = document.getElementById(containerId);
            container.innerHTML = '<p class="text-gray-600 text-sm">Đang tải lịch...</p>';

            fetch(`/specialties/${specialtyId}/schedules?doctorId=${doctorId}&date=${selectedDate}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data); // Debug dữ liệu trả về
                if (data.error) {
                    console.error('Error from server:', data.error);
                    container.innerHTML = '<p class="text-red-600 text-sm">Có lỗi xảy ra khi tải lịch.</p>';
                    return;
                }

                if (data.schedules && data.schedules.length > 0) {
                    let html = `
                        <h6 class="text-md font-semibold text-gray-700 ${selectedDate == new Date().toISOString().split('T')[0] ? 'bg-cyan-100 p-2 rounded' : ''}">
                            ${data.dateFormatted}
                        </h6>
                        <div class="grid grid-cols-3 gap-2 mt-2">
                    `;
                    data.schedules.forEach(scheduleData => {
                        const schedule = scheduleData.schedule;
                        const isAvailable = scheduleData.isAvailable;
                        const price = data.priceRelation?.valueVi ?? '500.000đ';
                        const timeDisplay = schedule.time_type_relation?.valueVi ?? schedule.timeType ?? 'N/A';
                        console.log('Schedule:', schedule); // Debug mỗi lịch
                        html += `
                            <div>
                                ${isAvailable ?
                                    `<button onclick="openBookingModal(
                                        '${data.dateFormatted}',
                                        '${timeDisplay}',
                                        '${price}',
                                        '${doctorId}',
                                        '${schedule.id}'
                                    )" class="bg-gray-100 text-gray-800 text-sm py-2 rounded w-full hover:bg-green-100">
                                        ${timeDisplay}
                                    </button>` :
                                    `<button class="bg-gray-100 text-gray-800 text-sm py-2 rounded opacity-50 cursor-not-allowed w-full">
                                        ${timeDisplay}
                                    </button>`
                                }
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<p class="text-gray-600 text-sm">Không có lịch cho ngày này.</p>';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                container.innerHTML = '<p class="text-red-600 text-sm">Không thể tải lịch. Vui lòng thử lại.</p>';
            });
        }

        // AJAX để tải lịch khi chọn ngày
        document.querySelectorAll('select[id^="date-select-"]').forEach(select => {
            select.addEventListener('change', function() {
                const doctorId = this.getAttribute('data-doctor-id');
                const specialtyId = this.getAttribute('data-specialty-id');
                const selectedDate = this.value;
                fetchSchedules(specialtyId, doctorId, selectedDate, `schedule-container-${doctorId}`);
            });

            // Tải lịch ngay khi trang load với ngày mặc định
            const doctorId = select.getAttribute('data-doctor-id');
            const specialtyId = select.getAttribute('data-specialty-id');
            const selectedDate = select.value;
            fetchSchedules(specialtyId, doctorId, selectedDate, `schedule-container-${doctorId}`);
        });
    </script>
@endsection