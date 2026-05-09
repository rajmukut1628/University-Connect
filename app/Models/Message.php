<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'read_at' => 'datetime',
        'is_edited' => 'boolean',
        'deleted_by_sender' => 'boolean',
        'deleted_by_receiver' => 'boolean',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}