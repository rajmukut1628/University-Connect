<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationContribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'user_id',
        'amount',
        'donor_name',
        'donor_email',
        'message',
        'is_anonymous',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}