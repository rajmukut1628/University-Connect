<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CallSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'caller_id',
        'receiver_id',
        'type',
        'status',
        'offer',
        'answer',
        'caller_candidates',
        'receiver_candidates',
        'ended_at',
    ];

    protected $casts = [
        'caller_candidates' => 'array',
        'receiver_candidates' => 'array',
        'ended_at' => 'datetime',
    ];

    public function caller()
    {
        return $this->belongsTo(User::class, 'caller_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}