<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialty;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SpecialtyController extends Controller
{
    public function show(Request $request, $id)
    {
        // Lấy dữ liệu chuyên khoa và danh sách bác sĩ
        $specialty = Specialty::with([
            'doctors.user',
            'doctors.priceRelation'
        ])->findOrFail($id);

        // Tính toán 5 ngày làm việc (thứ 2 đến thứ 6) từ ngày hiện tại
        $workingDays = $this->getNextFiveWorkingDays();

        // Chuẩn bị dữ liệu bác sĩ với danh sách 5 ngày làm việc
        $doctorsData = [];
        foreach ($specialty->doctors as $doctor) {
            $doctorsData[$doctor->doctorId] = [
                'doctor' => $doctor,
                'dates' => $workingDays, // Sử dụng 5 ngày làm việc trực tiếp
                'selectedDate' => $workingDays[0] ?? now()->format('Y-m-d'), // Ngày mặc định là ngày đầu tiên
            ];
        }

        return view('specialties.show', compact('specialty', 'doctorsData'));
    }

    public function getSchedules(Request $request, $id)
    {
        Log::info('Request received: specialtyId=' . $id . ', doctorId=' . $request->input('doctorId') . ', date=' . $request->input('date'));
        $specialty = Specialty::findOrFail($id);
        $doctorId = $request->input('doctorId');
        $selectedDate = $request->input('date');

        Log::info('Doctor ID: ' . $doctorId . ', Selected Date: ' . $selectedDate);
        $doctor = $specialty->doctors()->where('doctorId', $doctorId)->first();
        if (!$doctor) {
            Log::error('Doctor not found for doctorId: ' . $doctorId);
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        // Lấy schedules theo doctorId và date
        $schedules = $doctor->schedules()
            ->whereDate('date', $selectedDate)
            ->with('timeTypeRelation')
            ->get();


        $schedulesWithStatus = [];
        foreach ($schedules as $schedule) {
            $scheduleDate = Carbon::parse($selectedDate)->format('Y-m-d');
            $scheduleTimeType = $schedule->timeType;

            $existingBooking = Booking::where('doctorId', $doctor->doctorId)
                ->whereRaw('DATE(date) = ?', [$scheduleDate])
                ->where('timeType', $scheduleTimeType)
                ->whereNot('statusId', 'S1')
                ->first();

            $isAvailable = !$existingBooking;

            $schedulesWithStatus[] = [
                'schedule' => $schedule,
                'isAvailable' => $isAvailable,
            ];
        }

        Log::info('Schedules with status: ' . json_encode($schedulesWithStatus));
        return response()->json([
            'schedules' => $schedulesWithStatus,
            'selectedDate' => $selectedDate,
            'dateFormatted' => Carbon::parse($selectedDate)->locale('vi')->translatedFormat('l - d/m'),
        ]);
    }

    private function getNextFiveWorkingDays()
    {
        $workingDays = [];
        $currentDate = Carbon::today();

        // Tính toán 5 ngày làm việc
        while (count($workingDays) < 5) {
            if ($currentDate->dayOfWeek >= 1 && $currentDate->dayOfWeek <= 5) {
                $workingDays[] = $currentDate->format('Y-m-d');
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }
}