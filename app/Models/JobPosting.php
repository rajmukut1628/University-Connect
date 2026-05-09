<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'company_name',
        'location',
        'type',
        'experience_level',
        'salary_range',
        'posted_by',
        'requirements',
        'benefits',
        'positions_available',
        'status',
        'deadline',
        'job_image',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function postedBy()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }
}
