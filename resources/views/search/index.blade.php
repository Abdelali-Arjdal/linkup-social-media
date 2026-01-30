@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Search Users</h1>
        <form action="{{ route('search') }}" method="GET" class="flex items-center space-x-3">
            <div class="flex-1">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ $query }}"
                    placeholder="Search by name or email..." 
                    class="input w-full"
                    autofocus
                >
            </div>
            <button type="submit" class="btn-primary">Search</button>
        </form>
    </div>

    @if($query)
        <div class="card">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                Search Results for "{{ $query }}"
                @if($users->count() > 0)
                    <span class="text-sm font-normal text-gray-500">({{ $users->total() }} found)</span>
                @endif
            </h2>

            @if($users->count() > 0)
                <div class="space-y-4">
                    @foreach($users as $user)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                            <a href="{{ route('profile.show', $user->id) }}" class="flex items-center space-x-4 flex-1">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    @if($user->bio)
                                        <p class="text-sm text-gray-600 mt-1">{{ $user->bio }}</p>
                                    @endif
                                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                        <span>{{ $user->posts_count }} posts</span>
                                        <span>{{ $user->followers_count }} followers</span>
                                        <span>{{ $user->following_count }} following</span>
                                    </div>
                                </div>
                            </a>
                            @if(auth()->id() !== $user->id)
                                <form action="{{ route('follow.toggle', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn {{ auth()->user()->isFollowing($user) ? 'btn-outline' : 'btn-primary' }}">
                                        {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
                {{ $users->links() }}
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-gray-500">No users found matching "{{ $query }}"</p>
                </div>
            @endif
        </div>
    @else
        <div class="card text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <p class="text-gray-500">Enter a name or email to search for users</p>
        </div>
    @endif
</div>
@endsection


