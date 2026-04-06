<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'course'; 

    // CHANGE THIS - use 'id' not 'course_id'
    protected $primaryKey = 'id';  // ← FIXED

    public $timestamps = false; 

    protected $fillable = [
        'course_code',
        'course_title',
        'description',
    ];

    // Course belongs to Program
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function offeredCourses()
    {
        return $this->hasMany(OfferedCourse::class, 'course_id');
    }

    // Many-to-many: prerequisites (self relationship)
    public function prerequisites()
    {
        return $this->belongsToMany(
            Course::class,
            'course_prerequisite',
            'course_id',
            'prerequisite_id'
        );
    }

    // Reverse: courses where this is a prerequisite
    public function dependentCourses()
    {
        return $this->belongsToMany(
            Course::class,
            'course_prerequisite',
            'prerequisite_id',
            'course_id'
        );
    }
}