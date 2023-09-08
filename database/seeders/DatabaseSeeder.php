<?php

namespace Database\Seeders;

use App\Models\Lesson;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $lessons = Lesson::factory()
            ->count(20)
            ->create();

        DB::table('users')->insert([
            [
                'name' => 'John',
                'email' => 'john@gmail.com',
                'email_verified_at' => date("Y-m-d h:i:s"),
                'password' => '',
                'remember_token' => '',
                'created_at' => date("Y-m-d h:i:s"),
                'updated_at' => date("Y-m-d h:i:s"),
            ],
            
        ]);

        DB::table('achievement_types')->insert([
            ['name' => 'Lessons'],
            ['name' => 'Comments'],
        ]);

        DB::table('achievements')->insert([
            ['name' => 'First Lesson Watched', 'achievement_type_id'=>1, 'achievement_value'=>1],
            ['name' => '5 Lessons Watched', 'achievement_type_id'=>1, 'achievement_value'=>5],
            ['name' => '10 Lessons Watched', 'achievement_type_id'=>1, 'achievement_value'=>10],
            ['name' => '25 Lessons Watched', 'achievement_type_id'=>1, 'achievement_value'=>25],
            ['name' => '50 Lessons Watched', 'achievement_type_id'=>1, 'achievement_value'=>50],
            ['name' => 'First Comment Written', 'achievement_type_id'=>2, 'achievement_value'=>1],
            ['name' => '3 Comments Written', 'achievement_type_id'=>2, 'achievement_value'=>3],
            ['name' => '5 Comments Written', 'achievement_type_id'=>2, 'achievement_value'=>5],
            ['name' => '10 Comments Written', 'achievement_type_id'=>2, 'achievement_value'=>10],
            ['name' => '20 Comments Written', 'achievement_type_id'=>2, 'achievement_value'=>20],
        ]);

        DB::table('badges')->insert([
            ['name' => 'Beginner', 'badge_value'=>0],
            ['name' => 'Intermediate', 'badge_value'=>4],
            ['name' => 'Advanced', 'badge_value'=>8],
            ['name' => 'Master', 'badge_value'=>10],
        ]);


    }
}
