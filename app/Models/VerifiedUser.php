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
        'phone',
        'department',
        'batch',
        'role',
        'status',
        'notes',
        'created_by',
    ];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isAlumni(): bool
    {
        return $this->role === 'alumni';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}