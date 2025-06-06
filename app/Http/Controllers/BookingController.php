<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Schedule;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function book(Request $request, $scheduleId)
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000', // Validation cho reason
        ]);

        $schedule = Schedule::findOrFail($scheduleId);
        $doctorId = $schedule->doctorId;
        $date = \Carbon\Carbon::parse($schedule->date)->format('Y-m-d');
        $timeType = $schedule->timeType;

        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        // Tạo token xác nhận
        $confirmationToken = (string) Str::uuid();

        // Tạo mã khám bệnh ngẫu nhiên
        $appointmentCode = Str::random(8);

        // Tạo bản ghi trong bảng bookings với statusId = S1
        $bookingData = [
            'doctorId' => $doctorId,
            'patientId' => $user->id,
            'date' => $date,
            'timeType' => $schedule->timeType,
            'statusId' => 'S1', // Trạng thái "Mới"
            'token' => $confirmationToken,
            'reason' => $request->reason, // Thêm lý do khám
            'appointment_code' => $appointmentCode, // Thêm mã khám bệnh
        ];

        $booking = Booking::create($bookingData);

        // Tạo URL xác nhận
        $confirmationUrl = route('booking.confirm', $confirmationToken);

        // Chuẩn bị dữ liệu cho email
        $emailData = [
            'doctorId' => $doctorId,
            'date' => $date,
            'timeType' => $schedule->timeTypeRelation->valueVi,
            'reason' => $request->reason,
            'patientName' => $user->firstName . ' ' . $user->lastName,
            'appointmentCode' => $appointmentCode, // Thêm mã khám bệnh vào email
        ];

        // Gửi email xác nhận
        try {
            Mail::to($user->email)->send(new BookingConfirmation($emailData, $confirmationUrl));
            Log::info('Confirmation email sent to:', ['email' => $user->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send confirmation email:', ['error' => $e->getMessage()]);
            // Xóa bản ghi trong bookings nếu gửi email thất bại
            $booking->delete();
            return redirect()->back()->with('error', 'Không thể gửi email xác nhận. Vui lòng thử lại sau.');
        }

        return redirect()->back()->with('success', 'Một email xác nhận đã được gửi đến ' . $user->email . '. Vui lòng kiểm tra email để xác nhận đặt lịch.');
    }

    public function confirmBooking($token)
    {
        // Tìm bản ghi trong bảng bookings với token và statusId = S1
        $booking = Booking::where('token', $token)
            ->where('statusId', 'S1')
            ->first();

        if (!$booking) {
            Log::error('No booking found for token or already confirmed:', ['token' => $token]);
            return redirect()->route('home')->with('error', 'Liên kết xác nhận không hợp lệ hoặc đã được xác nhận.');
        }

        // Kiểm tra xem khung giờ còn hợp lệ không (chỉ kiểm tra trạng thái S2)
        $existingBooking = Booking::where('doctorId', $booking->doctorId)
            ->whereRaw('DATE(date) = ?', [$booking->date])
            ->where('timeType', $booking->timeType)
            ->where('id', '!=', $booking->id) // Loại trừ chính bản ghi hiện tại
            ->where('statusId', 'S2') // Chỉ kiểm tra trạng thái S2
            ->first();

        if ($existingBooking) {
            Log::warning('Schedule already booked by another user:', [
                'doctorId' => $booking->doctorId,
                'date' => $booking->date,
                'timeType' => $booking->timeType,
            ]);
            // Xóa bản ghi hiện tại
            $booking->delete();
            return redirect()->route('home')->with('error', 'Khung giờ này đã được đặt bởi người khác.');
        }

        // Cập nhật trạng thái sang S2
        $booking->statusId = 'S2'; // Trạng thái "Đã xác nhận"
        $booking->save();

        // Log bản ghi sau khi cập nhật
        Log::info('Updated booking status to S2:', $booking->toArray());

        return redirect()->route('home')->with('success', 'Đặt lịch đã được xác nhận! Trạng thái: Đã xác nhận');
    }
}