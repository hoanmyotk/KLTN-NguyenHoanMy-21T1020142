<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>
        {{ $doctor->user ? $doctor->user->firstName . ' ' . $doctor->user->lastName : 'N/A' }} - BookingCare
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <style>
        .custom-container {
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        .doctor-info h4 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
        }
        .doctor-info p {
            font-size: 0.875rem;
            color: #4b5563;
        }
    </style>
</head>
<body class="font-roboto bg-gray-50">
    <header class="bg-white shadow-md">
        <div class="custom-container flex items-center justify-between py-4 px-6">
            <div class="flex items-center">
                <button class="text-gray-600 text-2xl mr-4">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="text-2xl font-bold text-blue-500">
                    BookingCare
                </h1>
            </div>
            <nav class="flex space-x-6">
                <a class="text-gray-600 hover:text-blue-500" href="{{ route('specialties.index') }}">Chuyên khoa</a>
                <a class="text-gray-600 hover:text-blue-500" href="#">Cơ sở y tế</a>
                <a class="text-gray-600 hover:text-blue-500" href="#">Bác sĩ</a>
                <a class="text-gray-600 hover:text-blue-500" href="#">Gói khám</a>
            </nav>
        </div>
    </header>
    <main class="custom-container mt-6 px-6">
        <div class="text-sm text-gray-600 mb-4">
            <a class="hover:underline" href="{{ route('specialties.index') }}">Khám chuyên khoa</a>
            /
            <a class="hover:underline" href="{{ route('specialties.show', $doctor->specialtyId) }}">{{ $doctor->specialty->name }}</a>
            /
            <span>{{ $doctor->user ? $doctor->user->firstName . ' ' . $doctor->user->lastName : 'N/A' }}</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            {{ $doctor->user ? $doctor->user->firstName . ' ' . $doctor->user->lastName : 'N/A' }}
        </h2>
        <section class="mb-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <img alt="Doctor's profile picture" class="w-16 h-16 rounded-full mr-4" height="60" src="{{ $doctor->user->image ?? 'https://storage.googleapis.com/a1aa/image/kG2R3Cn3A78LNRb_wEctS9ARVL4si3oX1W7YTFkUH1k.jpg' }}" width="60"/>
                    <div class="doctor-info">
                        @if($doctor->markdown && $doctor->markdown->contentHTML)
                            {!! $doctor->markdown->contentHTML !!}
                        @else
                            <h4 class="text-lg font-semibold text-gray-800">
                                {{ $doctor->user ? $doctor->user->firstName . ' ' . $doctor->user->lastName : 'N/A' }}
                            </h4>
                            <p class="text-sm text-gray-600">
                                Bác sĩ có nhiều năm kinh nghiệm về {{ $doctor->specialty->name }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $doctor->user->positionId ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Bác sĩ nhận khám từ 7 tuổi trở lên
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $doctor->user->address ?? 'Hà Nội' }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="text-sm text-gray-600 mb-2">
                    <p>ĐỊA CHỈ KHÁM</p>
                    <p class="text-blue-500 hover:underline">
                        {{ $doctor->nameClinic ?? 'Phòng khám Spinetech Clinic' }}
                    </p>
                    <p>
                        {{ $doctor->addressClinic ?? 'Tòa nhà GP, 257 Giải Phóng, Phương Mai, Đống Đa, Hà Nội' }}
                    </p>
                </div>
                <div class="text-sm text-gray-600">
                    <p>
                        GIÁ KHÁM: {{ $doctor->priceRelation->valueVi ?? '500.000đ' }}
                        <a class="text-blue-500 hover:underline" href="#">Xem chi tiết</a>
                    </p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>