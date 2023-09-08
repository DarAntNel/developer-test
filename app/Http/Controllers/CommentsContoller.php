<?php

namespace App\Http\Controllers;
use App\Events\CommentWritten;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentsContoller extends Controller
{
    public function post_comment(Request $request)
    {        
        $comment = Comment::create([
            'id' => "",
            'user_id' => $request->user_id,
            'body' => $request->body,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at,
        ]);
       
        try {
            CommentWritten::dispatch($csomment);
        } catch (Exception $e) {
            return "Failed to insert $comment";
        }
        
        return $comment;
        
    }
}


