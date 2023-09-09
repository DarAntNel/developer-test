<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Comment;
use App\Models\Lesson;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertStatus(200);
    }
    
    public function test_the_application_posts_comments(): void
    {
        $user = User::factory()->create();
        
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->body = "Comment Body";
        $comment->created_at = date("Y-m-d h:i:s");
        $comment->updated_at = date("Y-m-d h:i:s");
        $comment_string = json_encode($comment);
        $comment_array = json_decode($comment_string, true);

        $response = $this->json('POST', '/api/post_comment', $comment_array);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'body' => "Comment Body",
        ]);
    }

    public function test_the_application_watched_lesson(): void
    {
        $user = User::factory()->create();

        $lesson = Lesson::find(rand(1, 20));   

        $lesson_string = json_encode($lesson);
        $lesson_array = json_decode($lesson_string, true);
        $lesson_array["user_id"] = $user->id;

        $response = $this->json('POST', '/api/watch_lesson', $lesson_array);
        
        $this->assertDatabaseHas('lesson_users', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);
    }

    public function test_the_application_first_comment_written(): void
    {

        $user = User::factory()->create();
        
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->body = "Comment Body";
        $comment->created_at = date("Y-m-d h:i:s");
        $comment->updated_at = date("Y-m-d h:i:s");
        $comment_string = json_encode($comment);
        $comment_array = json_decode($comment_string, true);

        $response = $this->json('POST', '/api/post_comment', $comment_array);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'body' => "Comment Body",
        ]);
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJson([
            'unlocked_achievements' => ['First Comment Written'],
            'next_available_achievements' => ['First Lesson Watched', '3 Comments Written'],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaing_to_unlock_next_badge' => 3,
        ]);
        
    }

    public function test_the_application_first_lesson_watched(): void
    {

        $user = User::factory()->create();
        
        $lesson = Lesson::find(rand(1, 20));   

        $lesson_string = json_encode($lesson);
        $lesson_array = json_decode($lesson_string, true);
        $lesson_array["user_id"] = $user->id;

        $response = $this->json('POST', '/api/watch_lesson', $lesson_array);
        
        $this->assertDatabaseHas('lesson_users', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched'],
            'next_available_achievements' => ['5 Lessons Watched', 'First Comment Written'],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaing_to_unlock_next_badge' => 3,
        ]);
        
    }

    public function test_the_application_20_comments_written(): void
    {

        $user = User::factory()->create();
        
        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->created_at = date("Y-m-d h:i:s");
        $comment->updated_at = date("Y-m-d h:i:s");
        $comment_string = json_encode($comment);
        $comment_array = json_decode($comment_string, true);

        foreach (range(1, 20) as $comment_number) {
            
            $comment_array["body"] = "Comment Body - $comment_number";

            $response = $this->json('POST', '/api/post_comment', $comment_array);

            $this->assertDatabaseHas('comments', [
                'user_id' => $user->id,
                'body' => $comment_array["body"],
            ]);
        }
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJson([
            'unlocked_achievements' => ['First Comment Written', '3 Comments Written', '5 Comments Written', '10 Comments Written', '20 Comments Written'],
            'next_available_achievements' => ['First Lesson Watched', 'You have unlocked all achievements'],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaing_to_unlock_next_badge' => 3,
        ]);
        
    }

    public function test_the_application_50_lessons_watched(): void
    {

        $user = User::factory()->create();
        
        foreach (range(1, 50) as $x) {

            $lesson = Lesson::find(rand(1, 20));   

            $lesson_string = json_encode($lesson);
            $lesson_array = json_decode($lesson_string, true);
            $lesson_array["user_id"] = $user->id;

            $response = $this->json('POST', '/api/watch_lesson', $lesson_array);
        
            $this->assertDatabaseHas('lesson_users', [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ]);
        }
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched', '50 Lessons Watched'],
            'next_available_achievements' => ['You have unlocked all achievements', 'First Comment Written'],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaing_to_unlock_next_badge' => 3,
        ]);
        
    }

    public function test_the_application_50_lessons_watched_and_10_comments_written(): void
    {

        $user = User::factory()->create();
        
        foreach (range(1, 50) as $x) {

            $lesson = Lesson::find(rand(1, 20));   

            $lesson_string = json_encode($lesson);
            $lesson_array = json_decode($lesson_string, true);
            $lesson_array["user_id"] = $user->id;

            $response = $this->json('POST', '/api/watch_lesson', $lesson_array);
        
            $this->assertDatabaseHas('lesson_users', [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ]);
        }

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->created_at = date("Y-m-d h:i:s");
        $comment->updated_at = date("Y-m-d h:i:s");
        $comment_string = json_encode($comment);
        $comment_array = json_decode($comment_string, true);

        foreach (range(1, 10) as $comment_number) {
            
            $comment_array["body"] = "Comment Body - $comment_number";

            $response = $this->json('POST', '/api/post_comment', $comment_array);

            $this->assertDatabaseHas('comments', [
                'user_id' => $user->id,
                'body' => $comment_array["body"],
            ]);
        }
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched', '50 Lessons Watched', 'First Comment Written', '3 Comments Written', '5 Comments Written', '10 Comments Written'],
            'next_available_achievements' => ['You have unlocked all achievements', '20 Comments Written'],
            'current_badge' => 'Advanced',
            'next_badge' => 'Master',
            'remaing_to_unlock_next_badge' => 1,
        ]);
        
    }

    public function test_the_application_25_lessons_watched_and_10_comments_written(): void
    {

        $user = User::factory()->create();
        
        foreach (range(1, 25) as $x) {

            $lesson = Lesson::find(rand(1, 20));   

            $lesson_string = json_encode($lesson);
            $lesson_array = json_decode($lesson_string, true);
            $lesson_array["user_id"] = $user->id;

            $response = $this->json('POST', '/api/watch_lesson', $lesson_array);
        
            $this->assertDatabaseHas('lesson_users', [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ]);
        }

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->created_at = date("Y-m-d h:i:s");
        $comment->updated_at = date("Y-m-d h:i:s");
        $comment_string = json_encode($comment);
        $comment_array = json_decode($comment_string, true);

        foreach (range(1, 10) as $comment_number) {
            
            $comment_array["body"] = "Comment Body - $comment_number";

            $response = $this->json('POST', '/api/post_comment', $comment_array);

            $this->assertDatabaseHas('comments', [
                'user_id' => $user->id,
                'body' => $comment_array["body"],
            ]);
        }
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched', 'First Comment Written', '3 Comments Written', '5 Comments Written', '10 Comments Written'],
            'next_available_achievements' => ['50 Lessons Watched', '20 Comments Written'],
            'current_badge' => 'Advanced',
            'next_badge' => 'Master',
            'remaing_to_unlock_next_badge' => 2,
        ]);
        
    }

    public function test_the_application_10_lessons_watched_and_10_comments_written(): void
    {

        $user = User::factory()->create();
        
        foreach (range(1, 10) as $x) {

            $lesson = Lesson::find(rand(1, 20));   

            $lesson_string = json_encode($lesson);
            $lesson_array = json_decode($lesson_string, true);
            $lesson_array["user_id"] = $user->id;

            $response = $this->json('POST', '/api/watch_lesson', $lesson_array);
        
            $this->assertDatabaseHas('lesson_users', [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ]);
        }

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->created_at = date("Y-m-d h:i:s");
        $comment->updated_at = date("Y-m-d h:i:s");
        $comment_string = json_encode($comment);
        $comment_array = json_decode($comment_string, true);

        foreach (range(1, 10) as $comment_number) {
            
            $comment_array["body"] = "Comment Body - $comment_number";

            $response = $this->json('POST', '/api/post_comment', $comment_array);

            $this->assertDatabaseHas('comments', [
                'user_id' => $user->id,
                'body' => $comment_array["body"],
            ]);
        }
        
        $response = $this->get("/users/{$user->id}/achievements");

        $response->assertJson([
            'unlocked_achievements' => ['First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', 'First Comment Written', '3 Comments Written', '5 Comments Written', '10 Comments Written'],
            'next_available_achievements' => ['25 Lessons Watched', '20 Comments Written'],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaing_to_unlock_next_badge' => 1,
        ]);
        
    }


}
