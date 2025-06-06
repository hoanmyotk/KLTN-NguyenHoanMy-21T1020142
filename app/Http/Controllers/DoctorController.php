<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorInfor;

class DoctorController extends Controller
{
    public function show($id)
    {
        // Lấy thông tin bác sĩ cùng với các mối quan hệ
        $doctor = DoctorInfor::with([
            'user',
            'schedules.timeTypeRelation',
            'priceRelation',
            'markdown' // Tải mối quan hệ markdown
        ])->findOrFail($id);

        return view('doctors.show', compact('doctor'));
    }
}