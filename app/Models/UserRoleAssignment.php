<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRoleAssignment extends Model
{
    use HasFactory;

    protected $table = 'user_role_assignment';

    public $timestamps = false; 

    protected $fillable = [
        'user_id',
        'role_id',
        'start_date',
        'end_date',
    ];

   

    // Each assignment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each assignment belongs to a role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
