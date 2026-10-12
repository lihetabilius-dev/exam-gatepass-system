<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'reg_number', 
        'full_name', 
        'course',
        'passport_photo',  
    ];

  
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    
    public function nhifPayment()
    {
        return $this->hasOne(NhifPayment::class);
    }

    public function eligibility()
    {
        return $this->hasOne(Payment::class);
    }

    public function gatepasses()
    {
        return $this->hasMany(Gatepass::class);
    }
}