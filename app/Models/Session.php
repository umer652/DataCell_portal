<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

     protected $table = 'sessions';
     public $timestamps=false;
    protected $fillable = [
        'name',  // e.g., 2025, 2025-2026
        'active',
        'start_date', // optional
        'session_end_date',   // optional
        'semester_end_date'
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
    public function offeredCourses()
{
    return $this->hasMany(OfferedCourse::class, 'session_id');
}

}
