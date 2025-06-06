@extends('layouts.admin_layout')

@section('title', 'Sửa bác sĩ')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>Sửa bác sĩ</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a href="{{ route('admin.doctors.index') }}">Bác sĩ</a>
                </li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li>
                    <a class="active" href="#">Sửa</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Form sửa bác sĩ</h3>
            </div>
            @if (session('success'))
                <div class="alert alert-success" style="padding: 10px; margin-bottom: 15px; background-color: #d4edda; color: #155724;">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error" style="padding: 10px; margin-bottom: 15px; background-color: #f8d7da; color: #721c24;">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.doctors.update', $doctor->id) }}" id="doctorForm">
                @csrf
                @method('PUT')

                <!-- Hàng 1: priceId, provinceId, paymentId -->
                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label for="priceId" style="display: block; margin-bottom: 5px; color: var(--dark);">Giá khám</label>
                        <select name="priceId" id="priceId" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                            <option value="">Chọn giá</option>
                            @foreach ($prices as $price)
                                <option value="{{ $price->keyMap }}" {{ old('priceId', $doctor->priceId) == $price->keyMap ? 'selected' : '' }}>
                                    {{ $price->valueVi }}
                                </option>
                            @endforeach
                        </select>
                        @error('priceId')
                            <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div style="flex: 1;">
                        <label for="provinceId" style="display: block; margin-bottom: 5px; color: var(--dark);">Tỉnh</label>
                        <select name="provinceId" id="provinceId" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                            <option value="">Chọn tỉnh</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province->keyMap }}" {{ old('provinceId', $doctor->provinceId) == $province->keyMap ? 'selected' : '' }}>
                                    {{ $province->valueVi }}
                                </option>
                            @endforeach
                        </select>
                        @error('provinceId')
                            <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div style="flex: 1;">
                        <label for="paymentId" style="display: block; margin-bottom: 5px; color: var(--dark);">Phương thức thanh toán</label>
                        <select name="paymentId" id="paymentId" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                            <option value="">Chọn phương thức</option>
                            @foreach ($payments as $payment)
                                <option value="{{ $payment->keyMap }}" {{ old('paymentId', $doctor->paymentId) == $payment->keyMap ? 'selected' : '' }}>
                                    {{ $payment->valueVi }}
                                </option>
                            @endforeach
                        </select>
                        @error('paymentId')
                            <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Hàng 2: addressClinic, nameClinic -->
                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label for="addressClinic" style="display: block; margin-bottom: 5px; color: var(--dark);">Địa chỉ phòng khám</label>
                        <input type="text" name="addressClinic" id="addressClinic" value="{{ old('addressClinic', $doctor->addressClinic) }}" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                        @error('addressClinic')
                            <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div style="flex: 1;">
                        <label for="nameClinic" style="display: block; margin-bottom: 5px; color: var(--dark);">Tên phòng khám</label>
                        <input type="text" name="nameClinic" id="nameClinic" value="{{ old('nameClinic', $doctor->nameClinic) }}" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                        @error('nameClinic')
                            <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Hàng 3: description -->
                <div style="margin-bottom: 15px;">
                    <label for="description" style="display: block; margin-bottom: 5px; color: var(--dark);">Mô tả ngắn về bác sĩ</label>
                    <textarea name="description" id="description" rows="3" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey); resize: vertical;">{{ old('description', $doctor->markdown ? $doctor->markdown->description : '') }}</textarea>
                    @error('description')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hàng 4: contentMarkdown (CKEditor) -->
                <div style="margin-bottom: 15px;">
                    <label for="contentMarkdown" style="display: block; margin-bottom: 5px; color: var(--dark);">Nội dung giới thiệu bác sĩ</label>
                    <textarea name="contentMarkdownHTML" id="contentMarkdown" style="width: 100%; min-height: 300px;">{!! old('contentMarkdownHTML', $doctor->markdown ? $doctor->markdown->contentHTML : '') !!}</textarea>
                    <input type="hidden" name="contentMarkdown" id="contentMarkdownHidden">
                    @error('contentMarkdown')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" style="padding: 10px 20px; background-color: var(--blue); color: var(--light); border: none; border-radius: 5px; cursor: pointer;">
                    Cập nhật
                </button>
            </form>
        </div>
    </div>

    <!-- Thêm CKEditor và Turndown -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script src="https://unpkg.com/turndown@7.1.2/dist/turndown.js"></script>
    <script>
        // Khởi tạo CKEditor
        CKEDITOR.replace('contentMarkdown', {
            height: 300,
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
                { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize'] },
            ],
        });

        // Khởi tạo Turndown để chuyển HTML thành Markdown
        const turndownService = new TurndownService();

        // Khi submit form, chuyển HTML thành Markdown và gán vào hidden input
        document.getElementById('doctorForm').addEventListener('submit', function(e) {
            const htmlContent = CKEDITOR.instances.contentMarkdown.getData();
            const markdownContent = turndownService.turndown(htmlContent);
            document.getElementById('contentMarkdownHidden').value = markdownContent;
        });
    </script>
@endsection