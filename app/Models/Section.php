<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';
    protected $fillable = [
        'name', // e.g., A, B, C
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
