<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->back();
        }

        return view('auth.login');
    }

    // Xử lý đăng nhập bằng form
    public function login(Request $request)
    { 
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Tìm người dùng
        $user = User::where('email', $request->email)->first();

        // Kiểm tra email và mật khẩu
        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'Email hoặc mật khẩu không đúng.');
        }

        // Đăng nhập người dùng bằng Auth
        Auth::login($user);

        // Chuyển hướng tùy theo role
        if ($user->roleId === 'R1') {
            return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập thành công!');
        } elseif ($user->roleId === 'R3') {
            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        } else {
            return redirect()->route('admin.schedules.manage')->with('success', 'Đăng nhập thành công!');
        }

        // Nếu role không khớp, đăng xuất và chuyển về login
        Auth::logout();
        return redirect()->route('login')->with('error', 'Role không hợp lệ.');
    }

    // Hiển thị form đăng ký
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->back();
        }

        return view('auth.register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Tạo người dùng mới
        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roleId' => 'R3', // Gán role mặc định là Patient
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký thành công!');
    }

    // Đăng nhập bằng Google OAuth2
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Tách full name từ Google thành first name và last name
            $nameParts = explode(' ', $googleUser->getName());
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            // Tìm hoặc tạo người dùng mới
            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'image' => $googleUser->getAvatar(),
                    'roleId' => 'R3',
                    'password' => Hash::make(uniqid()),
                ]
            );

            // Đăng nhập người dùng
            Auth::login($user);

            return redirect()->route('home')->with('success', 'Đăng nhập bằng Google thành công!');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Đăng nhập bằng Google thất bại. Vui lòng thử lại.');
        }
    }

    // Đăng xuất
    public function logout()
    {
        // Đăng xuất người dùng bằng Auth
        Auth::logout();

        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }

    public function profile()
    {
        $user = Auth::user();

        if ($user->roleId === 'R3') {
            return view('user.profile');
        }

        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phonenumber' => 'nullable|string|max:15',
            'gender' => 'nullable|in:MALE,FEMALE,OTHER',
            'image' => 'nullable|image|max:2048',
        ]);

        $user = User::findOrFail(Auth::id());
        $data = $request->only(['firstName', 'lastName', 'address', 'phonenumber', 'gender']);

        // Xử lý upload ảnh
        $imagePath = $user->image; // Giữ đường dẫn ảnh cũ làm mặc định
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            $file = $request->file('image');
            $imageName = time() . '_' . $file->getClientOriginalName(); // Tạo tên file duy nhất
            $file->move(public_path('images/users'), $imageName); // Lưu trực tiếp vào public/images/users/
            $imagePath = 'images/users/' . $imageName; // Cập nhật đường dẫn mới
        }

        // Gán đường dẫn ảnh vào dữ liệu
        $data['image'] = $imagePath;

        // Cập nhật thông tin người dùng
        $user->update($data);

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail(Auth::id());

        // Kiểm tra mật khẩu cũ
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Mật khẩu cũ không đúng.']);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile')->with('success', 'Đổi mật khẩu thành công!');
    }

    // Hiển thị form "Quên mật khẩu"
    public function showForgotPasswordForm()
    {
        if (Auth::check()) {
            return redirect()->back()->with('info', 'Bạn đã đăng nhập. Không cần đặt lại mật khẩu.');
        }
        return view('auth.forgot-password');
    }

    // Xử lý gửi email đặt lại mật khẩu
    public function sendResetPasswordLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.']);
        }

        // Tạo token đặt lại mật khẩu
        $token = Str::random(60);
        $user->reset_token = $token;
        $user->reset_token_expires_at = now()->addHours(2);
        $user->save();

        // Gửi email
        $resetLink = route('password.reset', ['token' => $token, 'email' => $user->email]);
        Mail::raw("Nhấn vào liên kết để đặt lại mật khẩu: $resetLink", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Đặt lại mật khẩu - BookingCare');
        });

        return redirect()->route('login')->with('success', 'Đã gửi liên kết đặt lại mật khẩu. Vui lòng kiểm tra email.');
    }

    // Hiển thị form đặt lại mật khẩu
    public function showResetPasswordForm(Request $request, $token)
    {
        if (Auth::check()) {
            return redirect()->back()->with('info', 'Bạn đã đăng nhập. Không cần đặt lại mật khẩu.');
        }

        $user = User::where('reset_token', $token)
                    ->where('reset_token_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['token' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    // Xử lý đặt lại mật khẩu
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('reset_token', $request->token)
                    ->where('email', $request->email)
                    ->where('reset_token_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['token' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.']);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->password);
        $user->reset_token = null; // Xóa token sau khi sử dụng
        $user->reset_token_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.');
    }
}