<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\UserAchievement;
use App\Models\Achievement;

class UpdateAchievement
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
        $achievement_name = $event->achievement_name;
        $user = $event->user;
        $record = Achievement::where('name', $achievement_name)->get("id");
        $achievement_id = $record[0]->id;
        $user_achievement = new UserAchievement();
        $user_achievement->user_id = $user->id;
        $user_achievement->achievement_id = $achievement_id;
        $user_achievement->obtained = 1;
        $user_achievement->save();
    }
}
