<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonationManualPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'user_id',
        'payment_method',
        'account_number',
        'transaction_id',
        'amount',
        'screenshot',
        'note',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getScreenshotUrlAttribute(): ?string
    {
        return $this->screenshot
            ? asset('storage/' . $this->screenshot)
            : null;
    }
}