<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollment';

    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'session_id',
        'program_id',
        'offered_course_id',
        'section',
        'semester',
        'enrollment_date',
        'grade',
    ];



    // RELATIONSHIPS

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function offeredCourse()
    {
        return $this->belongsTo(OfferedCourse::class, 'offered_course_id');
    }
}
