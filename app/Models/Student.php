<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'student';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'father_name',
        'roll_no',
        'app_no',
        'semester',
        'program_id',
        'comment',
        'new_student',
        'session_id',
        'enrollment_date',
        'section_id'
    ];

    // ================= RELATIONSHIPS =================

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    // ADD THIS RELATIONSHIP
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id', 'id');
    }
}