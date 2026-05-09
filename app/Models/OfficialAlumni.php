<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficialAlumni extends Model
{
    use HasFactory;

    protected $table = 'official_alumni';

    protected $fillable = [
        'alumni_id',
        'name',
        'email',
        'department',
        'batch',
        'company',
        'designation',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'alumni_id');
    }
}