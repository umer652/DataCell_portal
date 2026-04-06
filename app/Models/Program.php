<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'program';

    public $timestamps=false;

    protected $fillable = [
        'name',
        
    ];

    // A program has many course-scheme mappings
    public function programCourseSchemes()
    {
        return $this->hasMany(ProgramCourseScheme::class, 'program_id');
    }

    // Direct access to courses through scheme mapping
    public function courses()
    {
        return $this->hasManyThrough(
            Course::class,
            ProgramCourseScheme::class,
            'program_id',
            'course_id',
            'program_id',
            'course_id'
        );
    }

    // Scheme structure per program
    public function schemes()
    {
        return $this->hasMany(SchemeOfStudy::class, 'program_id');
    }
    public function offeredCourses()
{
    return $this->hasMany(OfferedCourse::class, 'program_id');
}

}
