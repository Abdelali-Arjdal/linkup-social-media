<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Follow;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get IDs of users being followed
        $followingIds = Follow::where('follower_id', $user->id)
            ->pluck('following_id')
            ->toArray();

        // If user is following others, show posts from followed users + own posts
        // Otherwise, show all posts (for new users)
        if (count($followingIds) > 0) {
            $ids = array_unique(array_merge($followingIds, [$user->id]));
            $posts = Post::with(['user', 'comments.user', 'likes'])
                ->whereIn('user_id', $ids)
                ->latest()
                ->paginate(10);
        } else {
            // Show all posts if user is not following anyone yet
            $posts = Post::with(['user', 'comments.user', 'likes'])
                ->latest()
                ->paginate(10);
        }

        return view('feed.index', compact('posts'));
    }
}
