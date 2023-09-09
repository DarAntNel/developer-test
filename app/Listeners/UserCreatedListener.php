<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserBadge;

class UserCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;

        $user_badge = new UserBadge();
        $user_badge->user_id = $user->id;
        $user_badge->badge_id = 1;
        $user_badge->obtained = 1;
        $user_badge->save();
    }
}
