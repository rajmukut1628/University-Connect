<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlumniConversionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'graduation_year',
        'current_company',
        'designation',
        'supporting_document',
        'status',
        'student_note',
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}