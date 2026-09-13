<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
        'attachment',
        'attachment_name',
        'attachment_type',
        'is_read',
        'read_at',
        'is_edited',
        'deleted_by_sender',
        'deleted_by_receiver',
        'reply_to',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_edited' => 'boolean',
        'deleted_by_sender' => 'boolean',
        'deleted_by_receiver' => 'boolean',
    ];

    public function sender()
    {
        return $this->belongsTo(
            User::class,
            'sender_id'
        );
    }

    public function recipient()
    {
        return $this->belongsTo(
            User::class,
            'recipient_id'
        );
    }

    public function receiver()
    {
        return $this->recipient();
    }

    public function replyTo()
    {
        return $this->belongsTo(
            Message::class,
            'reply_to'
        );
    }
}