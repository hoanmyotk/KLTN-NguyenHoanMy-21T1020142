<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorInfor extends Model
{
    use HasFactory;

    protected $table = 'doctor_infor';

    protected $fillable = ['doctorId', 'specialtyId', 'clinicId', 'priceId', 'provinceId', 'paymentId', 'addressClinic', 'nameClinic'];

    // Mối quan hệ: Thông tin bác sĩ thuộc về một chuyên khoa
    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialtyId');
    }

    // Mối quan hệ: Một bác sĩ có nhiều lịch làm việc
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'doctorId', 'doctorId');
    }

    // Mối quan hệ: Thông tin bác sĩ thuộc về một người dùng (users)
    public function user()
    {
        return $this->belongsTo(User::class, 'doctorId');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'doctorId')
            ->where('date', '>=', now()->toDateString())
            ->where('date', '<=', now()->addDays(6)->toDateString())
            ->orderBy('date', 'asc');
    }

    public function markdown()
    {
        return $this->hasOne(Markdown::class, 'doctorId', 'doctorId');
    }

    public function priceRelation()
    {
        return $this->belongsTo(Allcode::class, 'priceId', 'keyMap')->where('type', 'PRICE');
    }

    public function provinceRelation()
    {
        return $this->belongsTo(Allcode::class, 'provinceId', 'keyMap')->where('type', 'PROVINCE');
    }

    public function paymentRelation()
    {
        return $this->belongsTo(Allcode::class, 'paymentId', 'keyMap')->where('type', 'PAYMENT');
    }
}