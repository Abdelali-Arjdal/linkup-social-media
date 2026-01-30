@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Create Post Card -->
    <div class="card">
        <div class="flex items-start space-x-4">
            @if(auth()->user()->avatar)
                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-12 h-12 rounded-full object-cover flex-shrink-0">
            @else
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-semibold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <form action="{{ route('posts.store') }}" method="POST" class="flex-1">
                @csrf
                <textarea 
                    name="content" 
                    rows="4" 
                    placeholder="What's on your mind, {{ auth()->user()->name }}?" 
                    required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none transition-all duration-200"
                ></textarea>
                @error('content')
                    <p class="text-secondary text-sm mt-2">{{ $message }}</p>
                @enderror
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center space-x-2 text-gray-500 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Add photo</span>
                    </div>
                    <button 
                        type="submit"
                        class="btn-primary"
                    >
                        Post
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Posts Feed -->
    @forelse($posts as $post)
        <x-post-card :post="$post" />
    @empty
        <div class="card text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No posts yet</h3>
            <p class="text-gray-500 mb-6">Be the first to share something with your friends!</p>
            <p class="text-sm text-gray-400">Start by creating a post above or following other users to see their content.</p>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($posts->hasPages())
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
