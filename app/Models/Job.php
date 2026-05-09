<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'company_name',
        'company',
        'location',
        'category',
        'type',
        'salary',
        'description',
        'requirements',
        'status',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getCompanyDisplayAttribute()
    {
        return $this->company_name ?: $this->company ?: 'Company Not Specified';
    }
}