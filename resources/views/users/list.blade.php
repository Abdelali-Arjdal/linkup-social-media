@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="card mb-6">
        <div class="flex items-center space-x-4 mb-4">
            <a href="{{ route('profile.show', $user->id) }}" class="flex items-center space-x-3">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $title }}</p>
                </div>
            </a>
        </div>
    </div>

    <div class="card">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h2>

        @if($users->count() > 0)
            <div class="space-y-4">
                @foreach($users as $listUser)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                        <a href="{{ route('profile.show', $listUser->id) }}" class="flex items-center space-x-4 flex-1">
                            @if($listUser->avatar)
                                <img src="{{ Storage::url($listUser->avatar) }}" alt="{{ $listUser->name }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($listUser->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $listUser->name }}</p>
                                @if($listUser->bio)
                                    <p class="text-sm text-gray-600 mt-1">{{ $listUser->bio }}</p>
                                @endif
                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                    <span>{{ $listUser->posts_count }} posts</span>
                                    <span>{{ $listUser->followers_count }} followers</span>
                                    <span>{{ $listUser->following_count }} following</span>
                                </div>
                            </div>
                        </a>
                        @if(auth()->id() !== $listUser->id)
                            <form action="{{ route('follow.toggle', $listUser->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn {{ auth()->user()->isFollowing($listUser) ? 'btn-outline' : 'btn-primary' }}">
                                    {{ auth()->user()->isFollowing($listUser) ? 'Unfollow' : 'Follow' }}
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
            {{ $users->links() }}
        @else
            <div class="text-center py-12">
                <p class="text-gray-500">No {{ strtolower($title) }} yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection



