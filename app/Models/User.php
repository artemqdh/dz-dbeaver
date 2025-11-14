<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'avatar'
    ];

    protected $appends = [
        'avatar_url'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed'
        ];
    }

    public function profileImage(): HasOne
    {
        return $this->hasOne(UserImage::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function isAdmin(): bool
    {
        return $this->status === 'admin';
    }

    public function getAvatarUrlAttribute(): string
    {
        return isset($this->attributes['avatar']) && $this->attributes['avatar']
                ? url($this->attributes['avatar']) : url('/images/default-avatar.png');
    }

    public function setAvatarAttribute(?string $value): void
    {
        $this->attributes['avatar'] = $value ?? '/images/default-avatar.png';
    }
}