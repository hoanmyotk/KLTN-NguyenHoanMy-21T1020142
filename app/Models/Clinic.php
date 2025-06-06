<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $table = 'clinics';

    protected $fillable = ['name', 'address', 'descriptionMarkdown', 'descriptionHTML', 'image'];

    public function markdowns()
    {
        return $this->hasMany(Markdown::class, 'clinicId');
    }
}
