<?php

namespace App\Http\Controllers;
use App\Events\LessonWatched;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Models\User;
use App\Models\Lesson;
use App\Models\LessonUser;
use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\UserAchievement;
use App\Models\UserBadge;

class LessonsContoller extends Controller
{
    public function watch_lesson(Request $request)
    {        
        $user = User::find($request->user_id);
        
        $lesson = new Lesson();
        $lesson->id = $request->id;
        $lesson->title = $request->title;
        $lesson->created_at = $request->created_at;
        $lesson->updated_at = $request->updated_at;
    
        event(new LessonWatched($lesson, $user));
           
        $number_of_lessons_watched = $user->watched->count();
        $achievement_record = Achievement::where('achievement_type_id', 1)->where('achievement_value', $number_of_lessons_watched)->get();

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
      
        return $lesson;
        
    }
}
