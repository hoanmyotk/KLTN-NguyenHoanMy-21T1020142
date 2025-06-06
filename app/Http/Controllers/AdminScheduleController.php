<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorInfor;
use App\Models\Schedule;
use App\Models\AllCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminScheduleController extends Controller
{
    public function create()
    {
        // Lấy danh sách bác sĩ
        $doctors = DoctorInfor::with('user')->get();

        // Lấy danh sách khung giờ từ bảng allcodes
        $timeTypes = AllCode::where('type', 'TIME')->get();

        // Tính toán 10 ngày làm việc gần nhất (thứ 2 đến thứ 6)
        $workingDays = $this->getWorkingDays();

        return view('admin.schedules.create', compact('doctors', 'timeTypes', 'workingDays'));
    }

    public function store(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'doctorId' => 'required|exists:doctor_infor,doctorId',
            'date' => 'required|date|after_or_equal:today',
            'timeTypes' => 'required|array|min:1',
            'timeTypes.*' => 'exists:allcodes,keyMap',
        ]);

        $doctorId = $request->input('doctorId');
        $date = Carbon::parse($request->input('date'))->format('Y-m-d');
        $timeTypes = $request->input('timeTypes');

        // Kiểm tra xem lịch đã tồn tại chưa
        $existingSchedules = Schedule::where('doctorId', $doctorId)
            ->where('date', $date)
            ->whereIn('timeType', $timeTypes)
            ->get();

        if ($existingSchedules->isNotEmpty()) {
            return redirect()->back()->with('error', 'Một số khung giờ đã tồn tại cho bác sĩ này vào ngày ' . $date . '.');
        }

        // Tạo lịch mới
        foreach ($timeTypes as $timeType) {
            Schedule::create([
                'doctorId' => $doctorId,
                'date' => $date,
                'timeType' => $timeType,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Tạo lịch khám thành công!');
    }

    /**
     * Tính toán 10 ngày làm việc gần nhất (thứ 2 đến thứ 6) từ ngày hiện tại.
     *
     * @return array
     */
    private function getWorkingDays()
    {
        $workingDays = [];
        $currentDate = Carbon::today();

        // Tính toán 10 ngày làm việc
        while (count($workingDays) < 10) {
            // Chỉ thêm ngày nếu là thứ 2 đến thứ 6
            if ($currentDate->dayOfWeek >= 1 && $currentDate->dayOfWeek <= 5) {
                $workingDays[] = $currentDate->format('Y-m-d');
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }
}