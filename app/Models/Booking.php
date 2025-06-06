<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;


    // Các cột có thể điền (fillable)
    protected $fillable = [
        'doctorId',
        'patientId',
        'date',
        'timeType',
        'statusId',
        'token',
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

    // Quan hệ với bảng allcodes để lấy trạng thái
    public function status()
    {
        return $this->belongsTo(Allcode::class, 'statusId', 'keyMap')
            ->where('type', 'STATUS');
    }
    
    public function timeTypeRelation()
    {
        return $this->belongsTo(Allcode::class, 'timeType', 'keyMap')
            ->where('type', 'TIME');
    }
}