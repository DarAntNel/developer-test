<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserBadge;
use App\Models\Badge;

class UpdateBadge
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
        $badge_name = $event->badge_name;
        $user = $event->user;
        $record = Badge::where('name', $badge_name)->get("id");
        $badge_id = $record[0]->id;
        $user_badge = new UserBadge();
        $user_badge->user_id = $user->id;
        $user_badge->achievement_id = $badge_id;
        $user_badge->obtained = 1;
        $user_badge->save();
    }
}
