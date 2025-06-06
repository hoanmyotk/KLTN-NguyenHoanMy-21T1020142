@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <div class="container mx-auto p-4">
        <!-- Section: Dành cho bạn -->
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Dành cho bạn</h2>
            <div class="flex justify-center space-x-6">
                <div class="text-center">
                    <div class="w-32 h-32 rounded-full overflow-hidden bg-yellow-300 flex items-center justify-center shadow-md hover:shadow-lg transition-shadow">
                        <img alt="Bác sĩ" height="100" src="https://storage.googleapis.com/a1aa/image/cuohue3Kdq7y4T98UD9fZTR4zaKvb3-3fApCZiiLUXw.jpg" width="100" class="object-cover">
                    </div>
                    <p class="mt-3 text-gray-700 font-medium">Bác sĩ</p>
                </div>
                <div class="text-center">
                    <div class="w-32 h-32 rounded-full overflow-hidden bg-yellow-300 flex items-center justify-center shadow-md hover:shadow-lg transition-shadow">
                        <img alt="Chuyên khoa" height="100" src="https://storage.googleapis.com/a1aa/image/oOP17XAdjaYyx2TEIjMutOUw1aHSJNNvnAJcEZuU9_w.jpg" width="100" class="object-cover">
                    </div>
                    <p class="mt-3 text-gray-700 font-medium">Chuyên khoa</p>
                </div>
            </div>
        </div>

        <!-- Section: Dịch vụ toàn diện -->
        <div class="mb-12">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Dịch vụ toàn diện</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img alt="Khám Chuyên khoa" class="w-12 h-12 mr-4" height="40" src="https://storage.googleapis.com/a1aa/image/4igt5jkJ0-nj0wn6ctPTfByMjhYrlxM5y7l5_rB6pGQ.jpg" width="40">
                    <span class="text-gray-700">Khám Chuyên khoa</span>
                </div>
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img alt="Khám từ xa" class="w-12 h-12 mr-4" height="40" src="https://storage.googleapis.com/a1aa/image/j3ESdIWZ8LYb1hM-3__EWvTsLmMl7bHuZjxA_IZhjLc.jpg" width="40">
                    <span class="text-gray-700">Khám từ xa</span>
                </div>
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img alt="Khám tổng quát" class="w-12 h-12 mr-4" height="40" src="https://storage.googleapis.com/a1aa/image/wb-ZT-la404CAt2SHlK_7YG48T0VJ70L7L9lb3h4koQ.jpg" width="40">
                    <span class="text-gray-700">Khám tổng quát</span>
                </div>
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img alt="Xét nghiệm y học" class="w-12 h-12 mr-4" height="40" src="https://storage.googleapis.com/a1aa/image/-SNoCzCdxyqysUzP2jLeSsUmczQX F1qsCrXOAMBnAIs.jpg" width="40">
                    <span class="text-gray-700">Xét nghiệm y học</span>
                </div>
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img alt="Sức khỏe tinh thần" class="w-12 h-12 mr-4" height="40" src="https://storage.googleapis.com/a1aa/image/zAYdn74K3RSEsiyjXUR0n6otgIBXZx4mNYsPHhzl0VQ.jpg" width="40">
                    <span class="text-gray-700">Sức khỏe tinh thần</span>
                </div>
                <div class="flex items-center p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <img alt="Khám nha khoa" class="w-12 h-12 mr-4" height="40" src="https://storage.googleapis.com/a1aa/image/nRwfjz3eDmGHvgdW2RMHbiBBV_S_-uhjz7z4XORxYqU.jpg" width="40">
                    <span class="text-gray-700">Khám nha khoa</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Chuyên khoa -->
    <div class="container mx-auto p-4 mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Chuyên khoa</h2>
            <a href="" class="text-sm text-teal-500 hover:underline">Xem thêm</a>
        </div>
        <div class="relative">
            <button id="prevSpecialty" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full hover:bg-gray-300 transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <div class="overflow-hidden">
                <div id="specialtyCarousel" class="flex space-x-6 transition-transform duration-300 ease-in-out">
                    @foreach ($specialties as $specialty)
                        <a href="{{ route('specialties.show', $specialty->id) }}" class="block min-w-[300px] h-[320px] flex-shrink-0">
                            <div class="bg-white shadow-md rounded-lg p-4 flex flex-col items-center hover:bg-gray-100 transition h-full">
                                <img alt="Illustration of {{ $specialty->name }}" class="w-full h-48 object-cover mb-2 rounded" src="{{ asset($specialty->image) }}" height="600" width="300">
                                <p class="text-center font-medium text-gray-700 flex-1 flex items-center">{{ $specialty->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            <button id="nextSpecialty" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full hover:bg-gray-300 transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Section: Cơ sở y tế -->
    <div class="container mx-auto p-4 mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Cơ sở y tế</h2>
            <a href="" class="text-sm text-teal-500 hover:underline">Xem thêm</a>
        </div>
        <div class="relative">
            <button id="prevClinic" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full hover:bg-gray-300 transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <div class="overflow-hidden">
                <div id="clinicCarousel" class="flex space-x-6 transition-transform duration-300 ease-in-out">
                    @foreach ($clinics as $clinic)
                        <a href="" class="block min-w-[300px] h-[320px] flex-shrink-0">
                            <div class="bg-white shadow-md rounded-lg p-4 flex flex-col items-center hover:bg-gray-100 transition h-full">
                                <img alt="Logo of {{ $clinic->name }}" class="w-24 h-24 object-cover mb-2 rounded" src="{{ asset($clinic->image) }}" height="100" width="100">
                                <p class="text-center font-medium text-gray-700 flex-1 flex items-center">{{ $clinic->name }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            <button id="nextClinic" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full hover:bg-gray-300 transition-colors z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>

    <script>
        // Chuyên khoa
        let specialtyScrollPosition = 0;
        const specialtyCarousel = document.getElementById('specialtyCarousel');
        const specialtyItems = specialtyCarousel.children;
        const specialtyItemWidth = 300 + 24; // Chiều rộng mỗi item (300px) + khoảng cách (24px từ space-x-6)
        const specialtyVisibleItems = 3; // Số item hiển thị cùng lúc
        const specialtyMaxScroll = Math.max(0, (specialtyItems.length - specialtyVisibleItems) * specialtyItemWidth);

        document.getElementById('prevSpecialty').addEventListener('click', () => {
            if (specialtyScrollPosition > 0) {
                specialtyScrollPosition -= specialtyItemWidth * specialtyVisibleItems;
                if (specialtyScrollPosition < 0) specialtyScrollPosition = 0;
                specialtyCarousel.style.transform = `translateX(-${specialtyScrollPosition}px)`;
            }
        });

        document.getElementById('nextSpecialty').addEventListener('click', () => {
            if (specialtyScrollPosition < specialtyMaxScroll) {
                specialtyScrollPosition += specialtyItemWidth * specialtyVisibleItems;
                if (specialtyScrollPosition > specialtyMaxScroll) specialtyScrollPosition = specialtyMaxScroll;
                specialtyCarousel.style.transform = `translateX(-${specialtyScrollPosition}px)`;
            }
        });

        // Cơ sở y tế
        let clinicScrollPosition = 0;
        const clinicCarousel = document.getElementById('clinicCarousel');
        const clinicItems = clinicCarousel.children;
        const clinicItemWidth = 300 + 24; // Chiều rộng mỗi item (300px) + khoảng cách (24px từ space-x-6)
        const clinicVisibleItems = 3; // Số item hiển thị cùng lúc
        const clinicMaxScroll = Math.max(0, (clinicItems.length - clinicVisibleItems) * clinicItemWidth);

        document.getElementById('prevClinic').addEventListener('click', () => {
            if (clinicScrollPosition > 0) {
                clinicScrollPosition -= clinicItemWidth * clinicVisibleItems;
                if (clinicScrollPosition < 0) clinicScrollPosition = 0;
                clinicCarousel.style.transform = `translateX(-${clinicScrollPosition}px)`;
            }
        });

        document.getElementById('nextClinic').addEventListener('click', () => {
            if (clinicScrollPosition < clinicMaxScroll) {
                clinicScrollPosition += clinicItemWidth * clinicVisibleItems;
                if (clinicScrollPosition > clinicMaxScroll) clinicScrollPosition = clinicMaxScroll;
                clinicCarousel.style.transform = `translateX(-${clinicScrollPosition}px)`;
            }
        });
    </script>
@endsection