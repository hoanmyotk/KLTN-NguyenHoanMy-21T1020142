<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialty;

class AdminSpecialtyController extends Controller
{
    public function index(Request $request)
    {
        $query = Specialty::query();

        // Bộ lọc
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $specialties = $query->paginate(10);

        return view('admin.specialties.index', compact('specialties'));
    }

    public function create()
    {
        return view('admin.specialties.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'descriptionMarkdown' => 'nullable|string',
            'descriptionHTML' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Giới hạn kích thước ảnh 2MB
        ]);

        $data = [
            'name' => $request->name,
            'descriptionMarkdown' => $request->descriptionMarkdown,
            'descriptionHTML' => $request->descriptionHTML,
        ];

        // Xử lý upload hình ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName(); // Tạo tên file duy nhất
            $file->move(public_path('images/specialties'), $imageName); // Lưu vào public/images/specialties/
            $data['image'] = 'images/specialties/' . $imageName; // Lưu đường dẫn đầy đủ
        }

        Specialty::create($data);

        return redirect()->route('admin.specialties.index')->with('success', 'Thêm chuyên khoa thành công!');
    }

    public function edit($id)
    {
        $specialty = Specialty::findOrFail($id);
        return view('admin.specialties.form', compact('specialty'));
    }

    public function update(Request $request, $id)
    {
        $specialty = Specialty::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'descriptionMarkdown' => 'nullable|string',
            'descriptionHTML' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'descriptionMarkdown' => $request->descriptionMarkdown,
            'descriptionHTML' => $request->descriptionHTML,
        ];

        // Xử lý upload hình ảnh mới (nếu có)
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($specialty->image) {
                $oldImagePath = public_path($specialty->image); // Đường dẫn đầy đủ đã được lưu
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName(); // Tạo tên file duy nhất
            $file->move(public_path('images/specialties'), $imageName); // Lưu vào public/images/specialties/
            $data['image'] = 'images/specialties/' . $imageName; // Lưu đường dẫn đầy đủ
        }

        $specialty->update($data);

        return redirect()->route('admin.specialties.index')->with('success', 'Cập nhật chuyên khoa thành công!');
    }

    public function destroy($id)
    {
        $specialty = Specialty::findOrFail($id);

        // Xóa ảnh nếu có
        if ($specialty->image) {
            $imagePath = public_path($specialty->image); // Đường dẫn đầy đủ đã được lưu
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $specialty->delete();

        return redirect()->route('admin.specialties.index')->with('success', 'Xóa chuyên khoa thành công!');
    }
}