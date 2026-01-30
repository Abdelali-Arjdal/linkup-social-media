<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $users = collect();

        if ($query) {
            $users = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->where('id', '!=', $request->user()->id)
                ->withCount(['followers', 'following', 'posts'])
                ->paginate(20);
        }

        return view('search.index', compact('users', 'query'));
    }
}
