<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    use HasFactory;

    protected $table = 'social_accounts';

    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
        'provider_email',
        'nickname',
        'name',
        'avatar',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'raw',
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'raw' => 'array',
    ];

    protected $hidden = ['access_token', 'refresh_token', 'raw'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
