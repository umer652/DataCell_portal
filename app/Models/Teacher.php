<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Teacher extends Model
{
    protected $table = 'teacher';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id'
    ];

    // IMPORTANT FIX
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class, 'teacher_id');
    }
}
