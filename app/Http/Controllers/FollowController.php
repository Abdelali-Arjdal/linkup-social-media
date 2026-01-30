<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class FollowController extends Controller
{
    public function toggle(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Prevent following yourself
        if ($currentUser->id === $user->id) {
            return redirect()->route('profile.show', $user->id)
                ->with('error', 'You cannot follow yourself.');
        }

        // Rate limiting: 50 follow toggles per hour per user
        $limit = RateLimiter::attempt(
            'follow-toggle:' . $currentUser->id,
            50,
            function () {},
            3600
        );

        if (!$limit) {
            return redirect()->route('profile.show', $user->id)
                ->with('error', 'Too many follow/unfollow actions. Please try again later.');
        }

        $follow = Follow::where('follower_id', $currentUser->id)
            ->where('following_id', $user->id)
            ->first();

        if ($follow) {
            $follow->delete();
            $message = 'You unfollowed ' . $user->name;
        } else {
            Follow::create([
                'follower_id' => $currentUser->id,
                'following_id' => $user->id,
            ]);

            // Create notification for followed user
            Notification::create([
                'user_id' => $user->id,
                'notifiable_id' => $currentUser->id,
                'type' => 'follow',
                'message' => $currentUser->name . ' started following you',
            ]);

            $message = 'You are now following ' . $user->name;
        }

        return redirect()->route('profile.show', $user->id)
            ->with('success', $message);
    }
}
