<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Post $post)
    {
        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'content' => $request->validated()['content'],
        ]);

        // Load relationships for JSON response
        $comment->load('user');

        // Create notification for post owner (if not commenting on own post)
        if ($post->user_id !== $request->user()->id) {
            Notification::create([
                'user_id' => $post->user_id,
                'notifiable_id' => $request->user()->id,
                'type' => 'comment',
                'post_id' => $post->id,
                'comment_id' => $comment->id,
                'message' => $request->user()->name . ' commented on your post',
            ]);
        }

        // Reload post to get updated comment count
        $post->refresh();
        $commentCount = $post->comments()->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'avatar' => $comment->user->avatar ? Storage::url($comment->user->avatar) : null,
                    ],
                    'created_at' => $comment->created_at->diffForHumans(),
                    'created_at_raw' => $comment->created_at->toIso8601String(),
                ],
                'commentCount' => $commentCount,
                'canDelete' => auth()->id() === $comment->user_id,
                'message' => 'Comment added successfully!',
            ]);
        }

        return redirect()->route('feed')
            ->with('success', 'Comment added successfully!');
    }

    public function destroy(Request $request, Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $post = $comment->post;
        $comment->delete();

        // Reload post to get updated comment count
        $post->refresh();
        $commentCount = $post->comments()->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'commentCount' => $commentCount,
                'message' => 'Comment deleted successfully!',
            ]);
        }

        return redirect()->route('feed')
            ->with('success', 'Comment deleted successfully!');
    }
}
