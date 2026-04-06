<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePrerequisite extends Model
{
    protected $table = 'course_prerequisite';
    public $timestamps = false;

    protected $fillable = [
        'course_id',
        'prerequisite_course_id',
        'scheme_id',
        'program_id',
    ];

    // RELATIONS - using 'id' from course table
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');  // course.course_id in pivot references course.id
    }

    public function prerequisiteCourse()
    {
        return $this->belongsTo(Course::class, 'prerequisite_course_id', 'id');
    }

    public function sos()
    {
        return $this->belongsTo(SchemeOfStudy::class, 'scheme_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
} 