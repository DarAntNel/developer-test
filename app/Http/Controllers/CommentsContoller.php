<?php

namespace App\Http\Controllers;
use App\Events\CommentWritten;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\Comment;
use App\Models\User;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\UserAchievement;
use App\Models\UserBadge;
use Illuminate\Http\Request;

class CommentsContoller extends Controller
{
    public function post_comment(Request $request)
    {        
        $user = User::find($request->user_id);
        
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->body = $request->body;
        $comment->created_at = $request->created_at;
        $comment->updated_at = $request->updated_at;

        event(new CommentWritten($comment, $user));   

        $number_of_comments_posted = $user->comments->count();
        $achievement_record = Achievement::where('achievement_type_id', 2)->where('achievement_value', $number_of_comments_posted)->get();

        if (!empty($achievement_record)) {
            $achievement_name = $achievement_record[0]->name;
            event(new AchievementUnlocked($achievement_name, $user));
        }

        $number_of_achievements = $user->achievements->count();

        $badge_record = Badge::where('badge_value', $number_of_achievements)->get();

        if (!empty($badge_record)) {
            $badge_name = $badge_record[0]->name;
            event(new BadgeUnlocked($badge_name, $user));
        }
      
        return $comment;
        
    }
}


