<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramCourseScheme extends Model
{
    protected $table = 'program_course_scheme';

    protected $fillable = [
        'program_id',
        'course_id',
        'scheme_id',
        'credit_hrs'
    ];

    // Program relation
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    // Course relation
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Scheme relation (semester structure)
    public function scheme()
    {
        return $this->belongsTo(SchemeOfStudy::class, 'scheme_id');
    }
}
