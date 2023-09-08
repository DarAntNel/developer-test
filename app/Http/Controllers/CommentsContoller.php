<?php

namespace App\Http\Controllers;
use App\Events\CommentWritten;
use App\Models\Comment;
use App\Models\User;
use App\Models\Achievement;
use App\Models\Badge;
use Illuminate\Http\Request;

class CommentsContoller extends Controller
{
    public function post_comment(Request $request)
    {        
        $comment = Comment::create([
            'id' => "",
            'user_id' => $request->user_id,
            'body' => $request->body,
            'created_at' => date("Y-m-d h:i:s"),
            'updated_at' => date("Y-m-d h:i:s"),
        ]);
       
        try {
            CommentWritten::dispatch($comment);
        } catch (Exception $e) {
            return "Failed to insert $comment";
        }
    
        $user = User::find($request->user_id);
        $number_of_comments_posted = $user->comments->count();

        $records = Achievement::where('achievement_type_id', 2)->where('achievement_value', $number_of_comments_posted)->get();

        return $records;
        if ($lessonsWatched == 0) {
            $user->unlockAchievement('First Lesson Watched');
        } elseif ($lessonsWatched == 4) {
            $user->unlockAchievement('5 Lessons Watched');
        } elseif ($lessonsWatched == 9) {
            $user->unlockAchievement('10 Lessons Watched');
        } elseif ($lessonsWatched == 24) {
            $user->unlockAchievement('25 Lessons Watched');
        } elseif ($lessonsWatched == 49) {
            $user->unlockAchievement('50 Lessons Watched');
        }


        return $commentsPosted;
        return $comment;
        
    }
}


