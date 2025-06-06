<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorInfor;
use App\Models\Markdown;

class AdminDoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = DoctorInfor::with('user', 'priceRelation', 'provinceRelation', 'paymentRelation');

        if ($request->name) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('firstName', 'like', '%' . $request->name . '%')
                  ->orWhere('lastName', 'like', '%' . $request->name . '%');
            });
        }
        if ($request->email) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->email . '%');
            });
        }

        $doctors = $query->paginate(10);

        return view('admin.doctors.index', compact('doctors'));
    }

    public function edit($id)
    {
        $doctor = DoctorInfor::with('user', 'markdown')->findOrFail($id);
        $prices = \App\Models\Allcode::where('type', 'PRICE')->get();
        $provinces = \App\Models\Allcode::where('type', 'PROVINCE')->get();
        $payments = \App\Models\Allcode::where('type', 'PAYMENT')->get();

        return view('admin.doctors.form', compact('doctor', 'prices', 'provinces', 'payments'));
    }

    public function update(Request $request, $id)
    {
        $doctor = DoctorInfor::findOrFail($id);

        $request->validate([
            'priceId' => 'required|exists:allcodes,keyMap',
            'provinceId' => 'required|exists:allcodes,keyMap',
            'paymentId' => 'required|exists:allcodes,keyMap',
            'addressClinic' => 'required|string|max:255',
            'nameClinic' => 'required|string|max:255',
            'description' => 'nullable|string|max:500', // Thêm xác thực cho description
            'contentMarkdown' => 'nullable|string',
            'contentMarkdownHTML' => 'nullable|string',
        ]);

        // Cập nhật thông tin bác sĩ (bảng doctor_infor)
        $doctor->update([
            'priceId' => $request->priceId,
            'provinceId' => $request->provinceId,
            'paymentId' => $request->paymentId,
            'addressClinic' => $request->addressClinic,
            'nameClinic' => $request->nameClinic,
        ]);

        // Cập nhật hoặc tạo thông tin markdown
        $markdown = $doctor->markdown;
        if ($markdown) {
            $markdown->update([
                'description' => $request->description, // Lưu description
                'contentMarkdown' => $request->contentMarkdown,
                'contentHTML' => $request->contentMarkdownHTML,
            ]);
        } else {
            Markdown::create([
                'doctorId' => $doctor->doctorId,
                'description' => $request->description, // Lưu description
                'contentMarkdown' => $request->contentMarkdown,
                'contentHTML' => $request->contentMarkdownHTML,
            ]);
        }

        return redirect()->route('admin.doctors.index')->with('success', 'Cập nhật bác sĩ thành công!');
    }

    public function destroy($id)
    {
        $doctor = DoctorInfor::findOrFail($id);
        $user = $doctor->user;

        if ($user->image) {
            $imagePath = public_path('images/users/' . $user->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $doctor->delete();
        $user->delete();

        return redirect()->route('admin.doctors.index')->with('success', 'Xóa bác sĩ thành công!');
    }
}