<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class PostController extends Controller
{
    public function store(StorePostRequest $request)
    {
        // Rate limiting: 30 posts per hour per user
        $limit = RateLimiter::attempt(
            'post-create:' . $request->user()->id,
            30,
            function () {},
            3600
        );

        if (!$limit) {
            return redirect()->route('feed')
                ->with('error', 'You have posted too many times. Please try again later.');
        }

        Post::create([
            'user_id' => $request->user()->id,
            'content' => $request->validated()['content'],
        ]);

        return redirect()->route('feed')
            ->with('success', 'Post created successfully!');
    }

    public function destroy(Request $request, Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('feed')
            ->with('success', 'Post deleted successfully!');
    }
}
