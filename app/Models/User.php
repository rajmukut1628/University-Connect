<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable Attributes
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'name',
        'email',
        'official_id',
        'password',
        'role',
        'is_owner',

        'phone',
        'department',
        'batch',
        'skills',
        'bio',
        'address',

        'profile_image',
        'cover_image',

        'email_verified',
        'is_active',
        'is_blocked',
        'blocked_reason',

        'alumni_id',
        'student_id',

        'alumni_since',
        'converted_from_student_at',
        'converted_by',

        'github_url',
        'linkedin_url',
        'portfolio_url',

        'current_company',
        'current_designation',
        'current_job_type',
        'work_experience_years',

        'previous_company',
        'previous_designation',
        'previous_job_details',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden Attributes
    |--------------------------------------------------------------------------
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Attribute Casting
    |--------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'email_verified_at'          => 'datetime',
            'password'                   => 'hashed',
            'email_verified'             => 'boolean',
            'is_active'                  => 'boolean',
            'is_blocked'                 => 'boolean',
            'is_owner'                   => 'boolean',
            'alumni_since'               => 'datetime',
            'converted_from_student_at'  => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function officialStudent()
    {
        return $this->belongsTo(
            OfficialStudent::class,
            'student_id'
        );
    }
    public function workExperiences()
{
    return $this->hasMany(
        AlumniWorkExperience::class,
        'user_id'
    )->orderByDesc('is_current')
      ->orderByDesc('start_date');
}
public function currentWorkExperience()
{
    return $this->hasOne(
        AlumniWorkExperience::class,
        'user_id'
    )
    ->where('is_current', true)
    ->latestOfMany();
}

    public function officialAlumni()
    {
        return $this->belongsTo(
            OfficialAlumni::class,
            'alumni_id',
            'alumni_id'
        );
    }

    public function convertedBy()
    {
        return $this->belongsTo(
            User::class,
            'converted_by'
        );
    }

    public function convertedUsers()
    {
        return $this->hasMany(
            User::class,
            'converted_by'
        );
    }

    public function mentorships()
    {
        return $this->hasMany(
            Mentorship::class,
            'mentor_id'
        );
    }

    public function mentees()
    {
        return $this->hasMany(
            Mentorship::class,
            'student_id'
        );
    }

    public function createdEvents()
    {
        return $this->hasMany(
            Event::class,
            'created_by'
        );
    }

    public function eventParticipations()
    {
        return $this->hasMany(
            EventParticipant::class,
            'user_id'
        );
    }

    public function postedJobs()
    {
        return $this->hasMany(
            JobPosting::class,
            'posted_by'
        );
    }

    public function jobApplications()
    {
        return $this->hasMany(
            JobApplication::class,
            'applicant_id'
        );
    }

    public function sentMessages()
    {
        return $this->hasMany(
            Message::class,
            'sender_id'
        );
    }

    public function receivedMessages()
    {
        return $this->hasMany(
            Message::class,
            'recipient_id'
        );
    }

    public function notifications()
    {
        return $this->hasMany(
            Notification::class,
            'user_id'
        );
    }

    public function activityLogs()
    {
        return $this->hasMany(
            ActivityLog::class,
            'user_id'
        );
    }

    public function blockedUsers()
    {
        return $this->hasMany(
            BlockedUser::class,
            'blocked_by'
        );
    }

    public function blockedByUsers()
    {
        return $this->hasMany(
            BlockedUser::class,
            'user_id'
        );
    }

    public function aiSuggestions()
    {
        return $this->hasMany(
            \App\Models\AISuggestion::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return in_array(
            $this->role,
            ['admin', 'super_admin'],
            true
        );
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'super_admin'
            && $this->is_owner === true;
    }

    public function isNormalSuperAdmin(): bool
    {
        return $this->role === 'super_admin'
            && !$this->is_owner;
    }

    public function isGeneralAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isAlumni(): bool
    {
        return $this->role === 'alumni';
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Helpers
    |--------------------------------------------------------------------------
    */

    public function getProfileImageUrl(): string
{
    if (empty($this->profile_image)) {
        return 'https://ui-avatars.com/api/?name='
            . urlencode($this->name)
            . '&background=6366f1&color=ffffff&size=256';
    }

    if (
        str_starts_with(
            $this->profile_image,
            'http://'
        )
        ||
        str_starts_with(
            $this->profile_image,
            'https://'
        )
    ) {
        return $this->profile_image;
    }

    if (
        str_starts_with(
            $this->profile_image,
            'private/encrypted/'
        )
    ) {
        return route(
            'secure.profile.image',
            $this
        );
    }

    /*
     * Old public image.
     * Keep working until migration.
     */
    return asset(
        'storage/' .
        ltrim(
            $this->profile_image,
            '/'
        )
    );
}
}