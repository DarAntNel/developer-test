<?php

namespace App\Listeners;
use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InsertComment
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
        $comment = $event->comment;
        $user = $event->user;
        $comment->user_id = $user->id;
        $comment->save();
    }
}
