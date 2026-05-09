<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifiedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'alumni_id',
        'name',
        'email',
        'department',
        'batch',
        'role',
        'status',
    ];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isAlumni(): bool
    {
        return $this->role === 'alumni';
    }
}