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
        $avatar = $this->attributes['avatar'] ?? null;
        
        if (!$avatar) {
            return $this->generateUiAvatar();
        }
        
        if (str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://')) {
            if (str_contains($avatar, 'via.placeholder.com')) {
                return $this->generateUiAvatar();
            }
            return $avatar;
        }
        
        if (str_starts_with($avatar, '/storage/') || str_starts_with($avatar, 'storage/')) {
            return asset($avatar);
        }
        
        if (str_contains($avatar, '.jpeg') || str_contains($avatar, '.jpg') || str_contains($avatar, '.png')) {
            $path = storage_path('app/public/profile_images/' . basename($avatar));
            if (file_exists($path)) {
                return asset('storage/profile_images/' . basename($avatar));
            }
        }

        return $this->generateUiAvatar();
    }

    protected function generateUiAvatar(): string
    {
        $name = urlencode($this->name);
        $backgroundColor = $this->getAvatarColor();
        
        return "https://ui-avatars.com/api/?name={$name}&background={$backgroundColor}&color=fff&bold=true&length=2";
    }

    protected function getAvatarColor(): string
    {
        $seed = $this->id ?? $this->email;
        
        $colors = ['3B82F6', 'EF4444', '10B981', 'F59E0B', '8B5CF6', 'EC4899'];
        $index = crc32($seed) % count($colors);
        return $colors[$index];
    }

    public function setAvatarAttribute(?string $value): void
    {
        $this->attributes['avatar'] = $value;
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }
}