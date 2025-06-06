@extends('layouts.admin_layout')

@section('title', 'Tạo đơn thuốc')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-8">Tạo đơn thuốc</h1>

        <div class="table-data">
            <div class="schedule-table">
                <form action="{{ route('admin.schedules.store-prescription') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    <!-- Thông tin bệnh nhân -->
                    <div class="bg-light p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Thông tin bệnh nhân</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Email:</label>
                                <input type="email" name="email" value="{{ $booking->patient->email }}" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none" readonly>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Tên bệnh nhân:</label>
                                <input type="text" value="{{ $booking->patient->firstName }} {{ $booking->patient->lastName }}" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách thuốc -->
                    <div class="bg-light p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Danh sách thuốc</h2>
                        <div id="drug-list" class="space-y-4">
                            <!-- Input thuốc đầu tiên -->
                            <div class="drug-item flex items-center space-x-4 p-4 border border-gray-300 rounded-lg">
                                <select name="drugs[0][drug_id]" class="w-1/4 p-2 border border-gray-300 rounded-lg focus:outline-none">
                                    <option value="">Chọn thuốc</option>
                                    @foreach ($drugs as $drug)
                                        <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                                    @endforeach
                                </select>
                                <select name="drugs[0][form]" class="w-1/6 p-2 border border-gray-300 rounded-lg focus:outline-none">
                                    <option value="">Hình thức</option>
                                    <option value="tablet">Viên</option>
                                    <option value="bottle">Chai</option>
                                    <option value="injection">Xịt</option>
                                </select>
                                <input type="number" name="drugs[0][quantity]" placeholder="Số lượng" class="w-1/6 p-2 border border-gray-300 rounded-lg focus:outline-none" min="1">
                                <input type="text" name="drugs[0][instructions]" placeholder="Hướng dẫn sử dụng" class="w-1/3 p-2 border border-gray-300 rounded-lg focus:outline-none">
                                <button type="button" class="remove-drug text-red-600 hover:text-red-800 transition-colors" onclick="this.parentElement.remove()">×</button>
                            </div>
                        </div>
                        <button type="button" id="add-drug" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Thêm thuốc</button>
                    </div>

                    <!-- Thông tin bổ sung -->
                    <div class="bg-light p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Thông tin bổ sung</h2>
                        <textarea name="additional_info" placeholder="Nhập thông tin bổ sung cho đơn thuốc..." class="w-full p-4 border border-gray-300 rounded-lg focus:outline-none h-32 resize-none"></textarea>
                    </div>

                    <!-- Nút tạo -->
                    <div>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-md">Tạo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let drugIndex = 1;
        document.getElementById('add-drug').addEventListener('click', function() {
            const drugList = document.getElementById('drug-list');
            const newDrug = `
                <div class="drug-item flex items-center space-x-4 p-4 border border-gray-300 rounded-lg">
                    <select name="drugs[${drugIndex}][drug_id]" class="w-1/4 p-2 border border-gray-300 rounded-lg focus:outline-none">
                        <option value="">Chọn thuốc</option>
                        @foreach ($drugs as $drug)
                            <option value="{{ $drug->id }}">{{ $drug->name }}</option>
                        @endforeach
                    </select>
                    <select name="drugs[${drugIndex}][form]" class="w-1/6 p-2 border border-gray-300 rounded-lg focus:outline-none">
                        <option value="">Hình thức</option>
                        <option value="tablet">Viên</option>
                        <option value="bottle">Chai</option>
                        <option value="injection">Xịt</option>
                    </select>
                    <input type="number" name="drugs[${drugIndex}][quantity]" placeholder="Số lượng" class="w-1/6 p-2 border border-gray-300 rounded-lg focus:outline-none" min="1">
                    <input type="text" name="drugs[${drugIndex}][instructions]" placeholder="Hướng dẫn sử dụng" class="w-1/3 p-2 border border-gray-300 rounded-lg focus:outline-none">
                    <button type="button" class="remove-drug text-red-600 hover:text-red-800 transition-colors" onclick="this.parentElement.remove()">×</button>
                </div>
            `;
            drugList.insertAdjacentHTML('beforeend', newDrug);
            drugIndex++;
        });
    </script>
@endsection