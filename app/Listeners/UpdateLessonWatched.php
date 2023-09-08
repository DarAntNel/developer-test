<?php

namespace App\Listeners;
use App\Models\Lesson;
use App\Models\LessonUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateLessonWatched
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
        $lesson = $event->lesson;
        $user = $event->user;

        $lesson_user = new LessonUser();
        $lesson_user->user_id = $user->id;
        $lesson_user->lesson_id = $lesson->id;
        $lesson_user->watched = 1;
        $lesson_user->save();
    }
}
