<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserListController extends Controller
{
    public function followers(User $user)
    {
        $followers = $user->followers()
            ->withCount(['followers', 'following', 'posts'])
            ->paginate(20);

        return view('users.list', [
            'users' => $followers,
            'title' => 'Followers',
            'user' => $user,
            'type' => 'followers'
        ]);
    }

    public function following(User $user)
    {
        $following = $user->following()
            ->withCount(['followers', 'following', 'posts'])
            ->paginate(20);

        return view('users.list', [
            'users' => $following,
            'title' => 'Following',
            'user' => $user,
            'type' => 'following'
        ]);
    }
}
