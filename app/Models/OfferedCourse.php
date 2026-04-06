<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferedCourse extends Model
{
    protected $table = 'offered_courses';

    protected $fillable = [
        'course_id',
        'scheme_id',
        'program_id',
        'session_id',
        'semester',
    ];

    // 🔗 Course relation
   public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }



    // 🔗 Program relation
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    // 🔗 Scheme relation
    public function scheme()
    {
        return $this->belongsTo(SchemeOfStudy::class, 'scheme_id');
    }

    // 🔗 Session relation
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
}
