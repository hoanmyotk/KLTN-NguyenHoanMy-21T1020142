<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $table = 'specialties';

    protected $fillable = ['id', 'name', 'descriptionHTML', 'descriptionMarkdown', 'image'];

    public $timestamps = true;

    // Một chuyên khoa có nhiều bác sĩ
    public function doctors()
    {
        return $this->hasMany(DoctorInfor::class, 'specialtyId');
    }
}