<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Achievement;
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
                $next_achievement_array[] = $next_achievement_details[0]->name;
            }else{
                $next_achievement_details = Achievement::where('achievement_type_id', $latest_achievement_type_id)->where('achievement_value', '>=', 0)->orderBy('achievement_value', 'asc')->take(1)->get();
                $next_achievement_array[] = $next_achievement_details[0]->name;
            }
        }

        return response()->json([
            'unlocked_achievements' => $achievement_name_array,
            'next_available_achievements' => $next_achievement_array,
            'current_badge' => '',
            'next_badge' => '',
            'remaing_to_unlock_next_badge' => 0
        ]);
    }
}
