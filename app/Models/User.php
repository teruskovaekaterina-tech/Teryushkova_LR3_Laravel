<?php

namespace App\Models;

use App\Events\FriendshipCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'username', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'username';
    }

    public function laureates(): HasMany
    {
        return $this->hasMany(Laureate::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'friend_user', 'user_id', 'friend_id')
            ->withTimestamps();
    }

    public function addFriend(self $friend): void
    {
        if ($this->is($friend)) {
            return;
        }

        DB::transaction(function () use ($friend) {
            $this->friends()->syncWithoutDetaching([$friend->getKey()]);
            event(new FriendshipCreated($this, $friend));
        });
    }

    public function removeFriend(self $friend): void
    {
        if ($this->is($friend)) {
            return;
        }

        DB::transaction(function () use ($friend) {
            $this->friends()->detach($friend->getKey());
            $friend->friends()->detach($this->getKey());
        });
    }
}
