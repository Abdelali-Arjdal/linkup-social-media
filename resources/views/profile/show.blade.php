@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Profile Header Card -->
    <div class="card">
        <div class="flex flex-col md:flex-row items-start md:items-center space-y-6 md:space-y-0 md:space-x-8">
            <!-- Avatar -->
            @if($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover shadow-lg">
            @else
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-primary via-accent to-secondary flex items-center justify-center text-white font-bold text-4xl flex-shrink-0 shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            
            <div class="flex-1 w-full">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $user->name }}</h1>
                        @if($user->bio)
                            <p class="text-gray-700 mb-4 max-w-md">{{ $user->bio }}</p>
                        @elseif($currentUser->id === $user->id)
                            <p class="text-gray-400 text-sm mb-4">Add a bio to tell people about yourself</p>
                        @endif
                        
                        <!-- Stats -->
                        <div class="flex items-center space-x-6">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-gray-900">{{ $posts->total() }}</p>
                                <p class="text-sm text-gray-600">Posts</p>
                            </div>
                            <a href="{{ route('users.followers', $user->id) }}" class="text-center hover:opacity-80 transition cursor-pointer">
                                <p class="text-2xl font-bold text-gray-900">{{ $user->followers()->count() }}</p>
                                <p class="text-sm text-gray-600">Followers</p>
                            </a>
                            <a href="{{ route('users.following', $user->id) }}" class="text-center hover:opacity-80 transition cursor-pointer">
                                <p class="text-2xl font-bold text-gray-900">{{ $user->following()->count() }}</p>
                                <p class="text-sm text-gray-600">Following</p>
                            </a>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-3">
                        @if($currentUser->id === $user->id)
                            <a href="{{ route('profile.edit') }}" class="btn-primary text-sm">
                                Edit Profile
                            </a>
                        @else
                            <form action="{{ route('follow.toggle', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button 
                                    type="submit"
                                    class="{{ $isFollowing ? 'btn-outline' : 'btn-primary' }}"
                                >
                                    {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                                </button>
                            </form>
                            <a href="{{ route('messages.show', $user->id) }}" class="btn-outline">
                                Message
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts Section -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Posts</h2>
        @if($posts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($posts as $post)
                    <a href="{{ route('feed') }}" class="card-hover group">
                        <p class="text-gray-700 text-sm line-clamp-4 mb-3 group-hover:text-gray-900 transition">{{ $post->content }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $post->created_at->diffForHumans() }}</span>
                            <div class="flex items-center space-x-3">
                                <span class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>{{ $post->likes->count() }}</span>
                                </span>
                                <span class="flex items-center space-x-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span>{{ $post->comments->count() }}</span>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            {{ $posts->links() }}
        @else
            <div class="card text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500">No posts yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
