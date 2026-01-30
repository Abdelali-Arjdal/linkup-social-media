<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class LikeController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $user = $request->user();

        // Rate limiting: 100 like toggles per hour per user
        $limit = RateLimiter::attempt(
            'like-toggle:' . $user->id,
            100,
            function () {},
            3600
        );

        if (!$limit) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please try again later.'
            ], 429);
        }

        $like = Like::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($like) {
            $like->delete();
            $isLiked = false;
            $message = 'Post unliked.';
        } else {
            Like::create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);

            // Create notification for post owner (if not liking own post)
            if ($post->user_id !== $user->id) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'notifiable_id' => $user->id,
                    'type' => 'like',
                    'post_id' => $post->id,
                    'message' => $user->name . ' liked your post',
                ]);
            }

            $isLiked = true;
            $message = 'Post liked!';
        }

        // Reload post to get updated like count
        $post->refresh();
        $likeCount = $post->likes()->count();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'isLiked' => $isLiked,
                'likeCount' => $likeCount,
                'message' => $message,
            ]);
        }

        return redirect()->route('feed')
            ->with('success', $message);
    }
}
