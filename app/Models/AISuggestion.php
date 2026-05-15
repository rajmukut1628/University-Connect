<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AISuggestion extends Model
{
    use HasFactory;

    protected $table = 'ai_suggestions';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'icon',
        'priority',
        'score',
        'generated_by',
        'is_read',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_active' => 'boolean',
        'meta' => 'array',
        'priority' => 'integer',
        'score' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}