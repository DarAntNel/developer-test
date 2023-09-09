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

        $lesson = Lesson::find(rand(0, 20));   

        $lesson_string = json_encode($lesson);
        $lesson_array = json_decode($lesson_string, true);
        $lesson_array["user_id"] = $user->id;

        $response = $this->json('POST', '/api/watch_lesson', $lesson_array);
        
        $this->assertDatabaseHas('lesson_users', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
        ]);
    }

    public function test_the_application_returns_a_correct_achievement_response(): void
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
}
