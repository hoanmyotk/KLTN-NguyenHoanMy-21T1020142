<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users'; 
    protected $fillable = ['email', 'password', 'firstName', 'lastName', 'image', 'address', 'gender', 'roleId', 'phonenumber', 'reset_token', 'reset_token_expires_at'];
    public function markdowns()
    {
        return $this->hasMany(Markdown::class, 'doctorId');
    }

    public function doctor()
    {
        return $this->hasOne(DoctorInfor::class, 'doctorId');
    }

    public function roleRelation()
    {
        return $this->hasOne(Allcode::class, 'keyMap', 'roleId')->where('type', 'ROLE');
    }

    public function genderRelation()
    {
        return $this->hasOne(Allcode::class, 'keyMap', 'gender')->where('type', 'GENDER');
    }
}