<?php

namespace App\Events;

use App\Models\User;

class FriendshipCreated
{
    public function __construct(
        public User $user,
        public User $friend
    ) {
    }
}
