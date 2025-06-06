<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialty;
use App\Models\Clinic;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách chuyên khoa từ bảng specialties
        $specialties = Specialty::all();

        // Lấy danh sách bệnh viện từ bảng clinics
        $clinics = Clinic::all();


        // Truyền $specialties, $clinics và $user sang view
        return view('index', compact('specialties', 'clinics'));
    }
}