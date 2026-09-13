<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

        'contact_email',
        'contact_phone',
        'application_url',

        'status',

        'deadline',
        'job_image',
    ];


    protected $casts = [
        'deadline' => 'datetime',
        'positions_available' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function postedBy()
    {
        return $this->belongsTo(
            User::class,
            'posted_by'
        );
    }


    public function applications()
    {
        return $this->hasMany(
            JobApplication::class,
            'job_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }


    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }


    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}