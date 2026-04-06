<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allocation extends Model
{
    protected $table = 'allocations';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'course_id',
        'scheme_id',
        'program_id',
        'session_id',
        'teacher_id',
        'section_id',
        'semester'
    ];

    // Relationships

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scheme()
    {
        return $this->belongsTo(SchemeOfStudy::class, 'scheme_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}