<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlockedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'blocked_by',
        'user_id',
        'reason',
        'unblocked_at',
    ];

    protected $casts = [
        'unblocked_at' => 'datetime',
    ];

    public function blockedByUser()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    public function blockedUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
