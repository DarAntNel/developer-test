<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\Badge;
use App\Models\AchievementType;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $achievement_name_array = array();
        $next_achievement_array = array();

        $latest_achievements = AchievementType::all()->pluck('id')->mapWithKeys(function ($value) {
            return [$value => ""];
        })->toArray();

        $achievements = $user->achievements;
        
        foreach($achievements as $achievement){
            $achievement_details = Achievement::where('id', $achievement->achievement_id)->get();
            $achievement_name_array[] = $achievement_details[0]->name;
            if($latest_achievements[$achievement_details[0]->achievement_type_id] <= $achievement_details[0]->achievement_value){
                $latest_achievements[$achievement_details[0]->achievement_type_id] = $achievement_details[0]->id;
            }
        }
    
        foreach($latest_achievements as $latest_achievement_type_id => $latest_achievement_id){
            if(!empty($latest_achievement_id)){
                $latest_achievement_value = Achievement::where('id', $latest_achievement_id)->get("achievement_value")[0]->achievement_value;
                $next_achievement_details = Achievement::where('achievement_type_id', $latest_achievement_type_id)->where('achievement_value', '>', $latest_achievement_value)->orderBy('achievement_value', 'asc')->take(1)->get();
                ($next_achievement_details->isEmpty())? $next_achievement_array[] = "You have unlocked all achievements": $next_achievement_array[] = $next_achievement_details[0]->name;
            }else{
                $next_achievement_details = Achievement::where('achievement_type_id', $latest_achievement_type_id)->where('achievement_value', '>=', 0)->orderBy('achievement_value', 'asc')->take(1)->get();
                ($next_achievement_details->isEmpty())? $next_achievement_array[] = "You have unlocked all achievements": $next_achievement_array[] = $next_achievement_details[0]->name;
            }
        }

        $badges = $user->badges;
        $current_badge_value = 0;
        $current_badge = "";

        foreach($badges as $badge){
            $badge_details = Badge::where('id', $badge->badge_id)->get();
            if ($badge_details[0]->badge_value >= $current_badge_value){
                $current_badge = $badge_details[0]->name;
                $current_badge_value = $badge_details[0]->badge_value;
            }
        }

        $next_badge_details = Badge::where('badge_value','>', $current_badge_value)->orderBy('badge_value', 'asc')->take(1)->get();
        if(!$next_badge_details->isEmpty()){
            $next_badge = $next_badge_details[0]->name;
            $number_of_achievements = $user->achievements->count();
            $remaing_to_unlock_next_badge = $next_badge_details[0]->badge_value - $number_of_achievements;
        }else{
            $next_badge = "You have unlocked all badges";
            $remaing_to_unlock_next_badge = 0;
        }
        
       
        return response()->json([
            'unlocked_achievements' => $achievement_name_array,
            'next_available_achievements' => $next_achievement_array,
            'current_badge' => $current_badge,
            'next_badge' => $next_badge,
            'remaing_to_unlock_next_badge' => $remaing_to_unlock_next_badge
        ]);
    }
}
