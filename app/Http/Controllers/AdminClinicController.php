<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clinic;

class AdminClinicController extends Controller
{
    public function index(Request $request)
    {
        $query = Clinic::query();

        // Bộ lọc
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->address) {
            $query->where('address', 'like', '%' . $request->address . '%');
        }

        $clinics = $query->paginate(10);

        return view('admin.clinics.index', compact('clinics'));
    }

    public function create()
    {
        return view('admin.clinics.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'descriptionMarkdown' => 'nullable|string',
            'descriptionHTML' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Giới hạn kích thước ảnh 2MB
        ]);

        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'descriptionMarkdown' => $request->descriptionMarkdown,
            'descriptionHTML' => $request->descriptionHTML,
        ];

        // Xử lý upload hình ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName(); // Tạo tên file duy nhất
            $file->move(public_path('images/clinics'), $imageName); // Lưu trực tiếp vào public/images/clinics/
            $data['image'] = 'images/clinics/' . $imageName;
        }

        Clinic::create($data);

        return redirect()->route('admin.clinics.index')->with('success', 'Thêm bệnh viện thành công!');
    }

    public function edit($id)
    {
        $clinic = Clinic::findOrFail($id);
        return view('admin.clinics.form', compact('clinic'));
    }

    public function update(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'descriptionMarkdown' => 'nullable|string',
            'descriptionHTML' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'descriptionMarkdown' => $request->descriptionMarkdown,
            'descriptionHTML' => $request->descriptionHTML,
        ];

        // Xử lý upload hình ảnh mới (nếu có)
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($clinic->image) {
                $oldImagePath = public_path($clinic->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName(); // Tạo tên file duy nhất
            $file->move(public_path('images/clinics'), $imageName); // Lưu trực tiếp vào public/images/clinics/
            $data['image'] = 'images/clinics/' . $imageName;
        }

        $clinic->update($data);

        return redirect()->route('admin.clinics.index')->with('success', 'Cập nhật bệnh viện thành công!');
    }

    public function destroy($id)
    {
        $clinic = Clinic::findOrFail($id);

        // Xóa ảnh nếu có
        if ($clinic->image) {
            $imagePath = public_path($clinic->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $clinic->delete();

        return redirect()->route('admin.clinics.index')->with('success', 'Xóa bệnh viện thành công!');
    }
}