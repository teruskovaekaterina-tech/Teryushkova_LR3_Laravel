<?php

namespace App\Listeners;

use App\Events\FriendshipCreated;

class CreateMutualFriendship
{
    public function handle(FriendshipCreated $event): void
    {
        $event->friend->friends()->syncWithoutDetaching([$event->user->getKey()]);
    }
}
