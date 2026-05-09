<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'category',
        'target_amount',
        'collected_amount',
        'description',
        'image',
        'status',
        'deadline',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'deadline' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function contributions()
{
    return $this->hasMany(DonationContribution::class);
}

    public function getProgressAttribute(): int
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return min(100, (int) round(($this->collected_amount / $this->target_amount) * 100));
    }
}