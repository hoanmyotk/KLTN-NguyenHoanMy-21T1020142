@extends('layouts.admin_layout')

@section('title', isset($specialty) ? 'Sửa chuyên khoa' : 'Thêm chuyên khoa')

@section('content')
    <div class="head-title">
        <div class="left">
            <h1>{{ isset($specialty) ? 'Sửa chuyên khoa' : 'Thêm chuyên khoa' }}</h1>
            <ul class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="{{ route('admin.specialties.index') }}">Chuyên khoa</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">{{ isset($specialty) ? 'Sửa' : 'Thêm' }}</a></li>
            </ul>
        </div>
    </div>

    <div class="table-data">
        <div class="order">
            <div class="head">
                <h3>Form {{ isset($specialty) ? 'sửa' : 'thêm' }} chuyên khoa</h3>
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

            <form method="POST" action="{{ isset($specialty) ? route('admin.specialties.update', $specialty->id) : route('admin.specialties.store') }}" id="specialtyForm" enctype="multipart/form-data">
                @csrf
                @if (isset($specialty))
                    @method('PUT')
                @endif

                <!-- Hàng 1: name -->
                <div style="margin-bottom: 15px;">
                    <label for="name" style="display: block; margin-bottom: 5px; color: var(--dark);">Tên chuyên khoa</label>
                    <input type="text" name="name" id="name" value="{{ old('name', isset($specialty) ? $specialty->name : '') }}" required style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                    @error('name')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hàng 2: image -->
                <div style="margin-bottom: 15px;">
                    <label for="image" style="display: block; margin-bottom: 5px; color: var(--dark);">Ảnh chuyên khoa</label>
                    <input type="file" name="image" id="image" accept="image/*" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid var(--grey);">
                    @if (isset($specialty) && $specialty->image)
                        <div style="margin-top: 10px;">
                            <img src="{{ asset($specialty->image) }}" alt="Ảnh chuyên khoa" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                        </div>
                    @endif
                    @error('image')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hàng 3: descriptionMarkdown (CKEditor) -->
                <div style="margin-bottom: 15px;">
                    <label for="descriptionMarkdown" style="display: block; margin-bottom: 5px; color: var(--dark);">Mô tả chuyên khoa</label>
                    <textarea name="descriptionHTML" id="descriptionMarkdown" style="width: 100%; min-height: 300px;">{!! old('descriptionHTML', isset($specialty) ? $specialty->descriptionHTML : '') !!}</textarea>
                    <input type="hidden" name="descriptionMarkdown" id="descriptionMarkdownHidden">
                    @error('descriptionMarkdown')
                        <span style="color: var(--red); font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" style="padding: 10px 20px; background-color: var(--blue); color: var(--light); border: none; border-radius: 5px; cursor: pointer;">
                    {{ isset($specialty) ? 'Cập nhật' : 'Thêm' }}
                </button>
            </form>
        </div>
    </div>

    <!-- Thêm CKEditor và Turndown -->
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script src="https://unpkg.com/turndown@7.1.2/dist/turndown.js"></script>
    <script>
        // Tắt thông báo bảo mật toàn cục trước khi khởi tạo CKEditor
        window.CKEDITOR_DISABLE_VERSION_CHECK = true;

        // Khởi tạo CKEditor
        CKEDITOR.replace('descriptionMarkdown', {
            height: 300,
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
                { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize'] },
                { name: 'insert', items: ['Image'] }
            ],
            disableVersionCheck: true,
            allowedContent: true,
            autoParagraph: false,
            extraAllowedContent: 'img[src,alt]'
        });

        // Khởi tạo Turndown để chuyển HTML thành Markdown
        const turndownService = new TurndownService();

        // Khi submit form, chuyển HTML thành Markdown và gán vào hidden input
        document.getElementById('specialtyForm').addEventListener('submit', function(e) {
            const htmlContent = CKEDITOR.instances.descriptionMarkdown.getData();
            const markdownContent = turndownService.turndown(htmlContent);
            document.getElementById('descriptionMarkdownHidden').value = markdownContent;
        });
    </script>
@endsection