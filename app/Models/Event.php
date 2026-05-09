<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
    'title',
    'description',
    'type',
    'location',
    'event_date',
    'start_date',
    'start_time',
    'end_date',
    'end_time',
    'capacity',
    'cover_image',
    'status',
    'created_by',
];

    protected $casts = [
        'event_date' => 'datetime',
        'start_date' => 'date',
        'start_time' => 'time',
        'end_date' => 'date',
        'end_time' => 'time',
        'capacity' => 'integer',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function approvedParticipants()
    {
        return $this->hasMany(EventParticipant::class)
            ->where('status', 'approved');
    }

    public function pendingParticipants()
    {
        return $this->hasMany(EventParticipant::class)
            ->where('status', 'pending');
    }

    public function rejectedParticipants()
    {
        return $this->hasMany(EventParticipant::class)
            ->where('status', 'rejected');
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['active', 'published']);
    }

    public function approvedParticipantsCount(): int
    {
        return $this->approvedParticipants()->count();
    }
}