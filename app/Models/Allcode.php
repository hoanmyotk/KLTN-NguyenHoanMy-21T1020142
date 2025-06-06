<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allcode extends Model
{
    use HasFactory;

    protected $table = 'allcodes';
    protected $fillable = ['keyMap', 'type', 'valueEn', 'valueVi'];

    public $timestamps = true;

    // Mối quan hệ: Một Allcode có nhiều lịch làm việc (qua timeType)
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'timeType', 'keyMap');
    }
}