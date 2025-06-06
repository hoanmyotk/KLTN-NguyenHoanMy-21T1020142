<?php
use App\Http\Controllers\MarkdownController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminScheduleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDoctorController;
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/markdowns', [MarkdownController::class, 'index']);
Route::post('/vnpay_payment', [PaymentController::class, 'vnpay_payment']);
Route::get('/vnpay_return', [PaymentController::class, 'vnpay_return']);

Route::get('/doctors/{id}', [DoctorController::class, 'show'])->name('doctors.show');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetPasswordLink'])->name('password.send-reset-link');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('redirect.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/specialties/{id}', [SpecialtyController::class, 'show'])->name('specialties.show');
Route::get('/specialties/{id}/schedules', [SpecialtyController::class, 'getSchedules'])->name('specialties.schedules');

Route::middleware(['web', 'auth'])->group(function () {
    
    Route::get('/chat', [ChatController::class, 'chatPage'])->name('chat');
    Route::post('/save-question', [ChatController::class, 'saveInitialQuestion'])->name('save.question');
    Route::get('/chat/history', [ChatController::class, 'getHistory']);
    Route::post('/api/save-message', [ChatController::class, 'saveMessage']);
    Route::delete('/conversation/{id}', [ChatController::class, 'deleteConversation']);
    Route::delete('/chat/history/clear', [ChatController::class, 'clearHistory']);
    Route::get('/api/conversation/{id}', [ChatController::class, 'getConversation']); // Route mới
    Route::get('/api/chat', [ChatController::class, 'chat']);
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/change-password', [AuthController::class, 'changePassword'])->name('profile.change-password');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    
    Route::middleware('check.permission:R1')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
            Route::get('schedules/create', [AdminScheduleController::class, 'create'])->name('admin.schedules.create');
            Route::resource('users', App\Http\Controllers\AdminController::class)
                ->names('admin.users')
                ->parameters(['users' => 'id'])
                ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
            Route::post('/schedules', [AdminScheduleController::class, 'store'])->name('admin.schedules.store');
            Route::resource('doctors', App\Http\Controllers\AdminDoctorController::class)
                ->names('admin.doctors');

            Route::resource('clinics', App\Http\Controllers\AdminClinicController::class)
                ->names('admin.clinics');

            Route::resource('specialties', App\Http\Controllers\AdminSpecialtyController::class)
                ->names('admin.specialties');
        });
    });
    Route::middleware('check.permission:R2')->group(function () {
        Route::get('/schedules/manage', [ScheduleController::class, 'manage'])->name('admin.schedules.manage');
        Route::get('/schedules/create-prescription/{bookingId}', [ScheduleController::class, 'showCreatePrescriptionForm'])->name('admin.schedules.create-prescription');
        Route::post('/schedules/store-prescription', [ScheduleController::class, 'storePrescription'])->name('admin.schedules.store-prescription');
        Route::post('/schedules/send-prescription', [ScheduleController::class, 'sendPrescription'])->name('admin.schedules.send-prescription');
    });
});


Route::post('/book/{scheduleId}', [App\Http\Controllers\BookingController::class, 'book'])->name('booking.book');
Route::get('/confirm-booking/{token}', [App\Http\Controllers\BookingController::class, 'confirmBooking'])->name('booking.confirm');


