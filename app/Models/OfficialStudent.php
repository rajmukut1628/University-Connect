<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficialStudent extends Model
{
    use HasFactory;

    protected $table = 'official_students';

    protected $fillable = [
        'student_id',
        'name',
        'email',
        'department',
        'batch',
    ];
}