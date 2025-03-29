<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'inbox_url', // Add this line
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Boot function to auto-generate inbox_url when creating a user.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->inbox_url = static::generateUniqueInboxUrl();
        });
    }

    /**
     * Generate a unique inbox URL.
     */
    protected static function generateUniqueInboxUrl(): string
    {
        do {
            $uniqueUrl = Str::uuid()->toString();
        } while (self::where('inbox_url', $uniqueUrl)->exists());

        return $uniqueUrl;
    }
}
