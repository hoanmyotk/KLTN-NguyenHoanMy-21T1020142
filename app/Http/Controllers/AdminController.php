<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Specialty;
use App\Models\User;
use App\Models\DoctorInfor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalBookings = Booking::count();
        $totalSpecialties = Specialty::count();
        $totalUsers = User::count();
        $recentBookings = Booking::with(['patient', 'status'])
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalBookings', 'totalSpecialties', 'totalUsers', 'recentBookings'));
    }

    public function indexSpecialties()
    {
        return view('admin.specialties.index');
    }

    // Danh sách và lọc người dùng
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('firstName', 'like', '%' . $request->name . '%')
                ->orWhere('lastName', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('roleId')) {
            $query->where('roleId', $request->roleId);
        }
        $users = $query->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Hiển thị form thêm người dùng
    public function create()
    {
        return view('admin.users.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'gender' => 'required|string|in:M,F',
            'phonenumber' => 'required|string|max:15|regex:/^[0-9]{10,15}$/',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ảnh: tối đa 2MB
            'roleId' => 'required|in:R1,R2,R3',
        ]);

        // Xử lý upload ảnh
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName(); // Tạo tên file duy nhất
            $file->move(public_path('images/users'), $imageName); // Lưu trực tiếp vào public/images/users/
            $imagePath = 'images/users/' . $imageName;
        }

        // Tạo người dùng mới
        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'phonenumber' => $request->phonenumber,
            'image' => $imagePath,
            'roleId' => $request->roleId,
        ]);

        // Nếu vai trò là Bác sĩ (R2), tạo bản ghi trong bảng doctor_infor
        if ($request->roleId === 'R2') {
            DoctorInfor::create([
                'doctorId' => $user->id,
                'priceId' => 'PRI1', // Giả định giá mặc định
                'provinceId' => 'PRO1', // Giả định tỉnh mặc định
                'paymentId' => 'PAY1', // Giả định phương thức thanh toán mặc định
                'createdAt' => now(),
                'updatedAt' => now(),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng thành công!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'gender' => 'required|string|in:M,F',
            'phonenumber' => 'required|string|max:15|regex:/^[0-9]{10,15}$/',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ảnh: tối đa 2MB
            'roleId' => 'required|in:R1,R2,R3',
        ]);

        // Xử lý upload ảnh
        $imagePath = $user->image;
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($user->image) {
                $oldImagePath = public_path($user->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName(); // Tạo tên file duy nhất
            $file->move(public_path('images/users'), $imageName); // Lưu trực tiếp vào public/images/users/
            $imagePath = 'images/users/' . $imageName;
        }

        $data = [
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'gender' => $request->gender,
            'phonenumber' => $request->phonenumber,
            'image' => $imagePath,
            'roleId' => $request->roleId,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Nếu vai trò thay đổi thành Bác sĩ (R2) và chưa có bản ghi trong doctor_infor
        if ($request->roleId === 'R2' && !$user->doctor) {
            DoctorInfor::create([
                'doctorId' => $user->id,
                'priceId' => '', 
                'provinceId' => '', 
                'paymentId' => '', 
                'createdAt' => now(),
                'updatedAt' => now(),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    // Xóa người dùng
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->image) {
            $imagePath = public_path('images/clinics/' . $user->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng thành công!');
    }
}