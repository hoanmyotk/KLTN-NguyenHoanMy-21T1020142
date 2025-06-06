<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Markdown extends Model
{
    use HasFactory;

    protected $table = 'markdowns';

    protected $fillable = ['contentHTML', 'contentMarkdown', 'description', 'doctorId', 'specialtyId', 'clinicId'];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctorId');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class, 'specialtyId');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinicId');
    }
}
