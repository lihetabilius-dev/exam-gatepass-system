<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EligibleStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'amount_paid',  
        'nhif_status',   
        'is_eligible',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
