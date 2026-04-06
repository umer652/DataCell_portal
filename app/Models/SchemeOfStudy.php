<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeOfStudy extends Model
{
    use HasFactory;

    protected $table = 'scheme_of_study';
    public $timestamps=false;
    protected $fillable = [
        'title',
        'description',
        'credit_hrs',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function offeredCourses()
{
    return $this->hasMany(OfferedCourse::class, 'scheme_id');
}

}
