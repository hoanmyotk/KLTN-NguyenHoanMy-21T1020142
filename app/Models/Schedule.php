<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedules';

    protected $fillable = ['doctorId', 'date', 'timeType'];

    // Mối quan hệ: Lịch làm việc thuộc về một bác sĩ
    public function doctor()
    {
        return $this->belongsTo(DoctorInfor::class, 'doctorId', 'doctorId');
    }

    // Mối quan hệ: timeType liên kết với Allcode
    public function timeTypeRelation()
    {
        return $this->belongsTo(Allcode::class, 'timeType', 'keyMap')
            ->where('type', 'TIME');
    }
    
}