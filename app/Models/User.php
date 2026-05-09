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
        'password',
        'role',

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
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'email_verified'    => 'boolean',
            'is_active'         => 'boolean',
            'is_blocked'        => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function officialStudent()
    {
        return $this->belongsTo(OfficialStudent::class, 'student_id');
    }

    public function officialAlumni()
    {
        return $this->belongsTo(OfficialAlumni::class, 'alumni_id');
    }

    public function mentorships()
    {
        return $this->hasMany(Mentorship::class, 'mentor_id');
    }

    public function mentees()
    {
        return $this->hasMany(Mentorship::class, 'student_id');
    }

    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function eventParticipations()
    {
        return $this->hasMany(EventParticipant::class, 'user_id');
    }

    public function postedJobs()
    {
        return $this->hasMany(JobPosting::class, 'posted_by');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'applicant_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function blockedUsers()
    {
        return $this->hasMany(BlockedUser::class, 'blocked_by');
    }

    public function blockedByUsers()
    {
        return $this->hasMany(BlockedUser::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Returns true for both Admin and Super Admin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    /**
     * Returns true only for Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Returns true only for General Admin.
     */
    public function isGeneralAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Returns true only for Student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Returns true only for Alumni.
     */
    public function isAlumni(): bool
    {
        return $this->role === 'alumni';
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Helpers
    |--------------------------------------------------------------------------
    */

    public function getProfileImageUrl()
    {
        return $this->profile_image
            ? asset('storage/' . $this->profile_image)
            : asset('images/default-avatar.png');
    }

    public function getCoverImageUrl()
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/default-cover.jpg');
    }
}