<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    // Các cột có thể điền (fillable)
    protected $fillable = [
        'doctorId',
        'patientId',
        'description',
        'drugs',
        'reason',
    ];

    // Quan hệ với bảng doctor_infor
    public function doctor()
    {
        return $this->belongsTo(DoctorInfor::class, 'doctorId');
    }

    // Quan hệ với bảng users (nếu có hệ thống người dùng)
    public function patient()
    {
        return $this->belongsTo(User::class, 'patientId');
    }
}