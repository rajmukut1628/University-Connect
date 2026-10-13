<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostExtensionRequest extends Model
{
    protected $fillable = ['post_type', 'post_id', 'user_id', 'dates', 'reason', 'status', 'reviewed_by', 'review_note', 'reviewed_at'];
    protected $casts = ['dates' => 'array', 'reviewed_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
}
