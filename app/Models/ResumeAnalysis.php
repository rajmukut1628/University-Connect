<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resume_title',
        'original_file_name',
        'file_path',
        'file_type',
        'file_size',
        'score',
        'detected_skills',
        'missing_skills',
        'suggestions',
        'recommended_roles',
        'summary',
    ];

    protected $casts = [
        'detected_skills' => 'array',
        'missing_skills' => 'array',
        'suggestions' => 'array',
        'recommended_roles' => 'array',
        'score' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}