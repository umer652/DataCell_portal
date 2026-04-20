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
        'offered_courses_id',
        'section_id',
        'semester',
        'enrollment_date',
        'grade',
        'status',
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
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function offeredCourse()
    {
        return $this->belongsTo(OfferedCourse::class, 'offered_courses_id');
    }

    public function getResultAttribute()
    {
        return $this->total_marks >= 50 ? 'Pass' : 'Fail';
    }

    public function getResultColorAttribute()
    {
        return $this->total_marks >= 50 ? 'green' : 'red';
    }
    public function getGpaAttribute()
    {
        $total = (
            $this->homework_marks +
            $this->lab_marks +
            $this->midterm_marks +
            $this->final_marks
        );

        // safe guard
        if (!$this->total_marks || $this->total_marks == 0) {
            return 0;
        }

        $percentage = ($total / $this->total_marks) * 100;

        if ($percentage >= 80) return 4.0;
        if ($percentage >= 70) return 3.0;
        if ($percentage >= 60) return 2.0;
        if ($percentage >= 50) return 1.0;

        return 0.0;
    }
}
