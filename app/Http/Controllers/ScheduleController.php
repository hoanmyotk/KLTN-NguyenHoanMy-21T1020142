<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Drug;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPrescriptionMail;
class ScheduleController extends Controller
{
    public function manage(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        $workingDays = $this->getWorkingDays();

        // Lấy danh sách booking theo ngày (giả sử bạn có logic lấy dữ liệu)
        $bookings = Booking::where('date', $selectedDate)
        ->where('statusId', 'S2')
        ->with('patient')
        ->get();

        return view('doctors.manage', compact('bookings', 'selectedDate', 'workingDays'));
    }
    public function showCreatePrescriptionForm($bookingId)
    {
        $booking = Booking::with('patient')->findOrFail($bookingId);
        $drugs = Drug::all(); // Lấy tất cả thuốc từ bảng drugs

        return view('doctors.create-prescription', compact('booking', 'drugs'));
    }

    private function getWorkingDays()
    {
        $workingDays = [];
        $currentDate = Carbon::today();

        // Tính toán 5 ngày làm việc
        while (count($workingDays) < 5) {
            // Chỉ thêm ngày nếu là thứ 2 đến thứ 6
            if ($currentDate->dayOfWeek >= 1 && $currentDate->dayOfWeek <= 5) {
                $workingDays[] = $currentDate->format('Y-m-d');
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }

    public function storePrescription(Request $request)
    {
        Log::info('Request data: ', $request->all());

        // Validate dữ liệu
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'drugs.*.drug_id' => 'required|exists:drugs,id',
            'drugs.*.form' => 'required|in:tablet,bottle,injection',
            'drugs.*.quantity' => 'required|integer|min:1',
            'drugs.*.instructions' => 'nullable|string',
            'additional_info' => 'nullable|string',
        ]);

        $booking = Booking::with('patient')->findOrFail($request->booking_id);

        $content = "ĐƠN THUỐC\n";
        $content .= "-----------------\n";
        $content .= "Thông tin bệnh nhân:\n";
        $content .= "Tên: " . $booking->patient->firstName . " " . $booking->patient->lastName . "\n";
        $content .= "Email: " . $booking->patient->email . "\n";
        $content .= "Thời gian khám: " . $booking->date . ", " . $booking->timeTypeRelation->valueVi . "\n";
        $content .= "-----------------\n";
        $content .= "Danh sách thuốc:\n";

        $drugs = Drug::whereIn('id', array_column($request->drugs, 'drug_id'))->get();
        if (empty($request->drugs)) {
            Log::warning('No drugs provided in request.');
            return redirect()->back()->with('error', 'Vui lòng thêm ít nhất một thuốc.');
        }
        foreach ($request->drugs as $index => $drugData) {
            $drug = $drugs->firstWhere('id', $drugData['drug_id']);
            $content .= "- Tên thuốc: " . ($drug ? $drug->name : 'Không xác định') . "\n";
            $content .= "  Hình thức: " . $drugData['form'] . "\n";
            $content .= "  Số lượng: " . $drugData['quantity'] . "\n";
            $content .= "  Hướng dẫn: " . ($drugData['instructions'] ?? 'Không có') . "\n";
        }

        $content .= "-----------------\n";
        $content .= "Thông tin bổ sung:\n";
        $content .= $request->additional_info ?? 'Không có' . "\n";

        $fileName = 'prescription_' . $booking->id . '_' . time() . '.txt';
        $filePath = 'public/prescription/' . $fileName;
        $absolutePath = storage_path('app/' . $filePath);

        // Tạo thư mục nếu chưa tồn tại
        $directory = storage_path('app/public/prescription');
        if (!file_exists($directory)) {
            if (!mkdir($directory, 0777, true)) {
                Log::error('Failed to create directory: ' . $directory);
                return redirect()->back()->with('error', 'Không thể tạo thư mục lưu trữ.');
            }
            Log::info('Directory created at: ' . $directory);
        }

        // Kiểm tra quyền ghi
        if (!is_writable($directory)) {
            Log::error('Directory not writable: ' . $directory);
            return redirect()->back()->with('error', 'Thư mục không có quyền ghi.');
        }

        // Lưu file bằng file_put_contents
        try {
            $bytesWritten = file_put_contents($absolutePath, $content);
            if ($bytesWritten === false) {
                Log::error('Failed to write file at: ' . $absolutePath);
                return redirect()->back()->with('error', 'Không thể lưu file.');
            }
            Log::info('File saved successfully at: ' . $absolutePath);

            if (file_exists($absolutePath)) {
                Log::info('File confirmed exists at: ' . $absolutePath);
            } else {
                Log::error('File not found at: ' . $absolutePath);
                return redirect()->back()->with('error', 'File không được tạo. Vui lòng kiểm tra quyền thư mục.');
            }
        } catch (\Exception $e) {
            Log::error('Error saving file: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi lưu file: ' . $e->getMessage());
        }

        return redirect()->route('admin.schedules.manage')->with('success', 'Đơn thuốc đã được tạo và lưu thành file: ' . $fileName);
    }

    public function sendPrescription(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'email' => 'required|email',
            'prescription_file' => 'required|file|mimes:txt,pdf|max:2048', // Giới hạn file 2MB
        ]);

        $booking = Booking::with('patient')->findOrFail($request->booking_id);
        $patientName = $booking->patient->firstName . ' ' . $booking->patient->lastName;

        // Lưu file tạm thời
        $file = $request->file('prescription_file');
        $filePath = $file->store('temp', 'public'); // Lưu vào storage/app/public/temp
        $absoluteFilePath = storage_path('app/public/' . $filePath);

        try {
            // Gửi email
            Mail::to($request->email)->send(new SendPrescriptionMail($patientName, $absoluteFilePath));

            // Xóa file tạm sau khi gửi
            unlink($absoluteFilePath);
            $booking->statusId = 'S3';
            $booking->save();
            return redirect()->route('admin.schedules.manage')
                ->with('success', 'Đơn thuốc đã được gửi thành công tới ' . $request->email);
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
            return redirect()->route('admin.schedules.manage')
                ->with('error', 'Lỗi khi gửi email: ' . $e->getMessage());
        }
    }
}