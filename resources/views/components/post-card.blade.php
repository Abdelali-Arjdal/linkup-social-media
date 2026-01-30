@props(['post'])

@php
    use Illuminate\Support\Facades\Storage;
    $isLiked = $post->likes->contains('user_id', auth()->id());
@endphp

<div class="card mb-6" data-post-id="{{ $post->id }}">
    <!-- Post Header -->
    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('profile.show', $post->user->id) }}" class="flex items-center space-x-3 hover:opacity-80 transition">
            @if($post->user->avatar)
                <img src="{{ Storage::url($post->user->avatar) }}" alt="{{ $post->user->name }}" class="w-12 h-12 rounded-full object-cover">
            @else
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-semibold">
                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <p class="font-semibold text-gray-900">{{ $post->user->name }}</p>
                <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
            </div>
        </a>
        
        @can('delete', $post)
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-gray-400 hover:text-secondary transition p-2 rounded-lg hover:bg-red-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </form>
        @endcan
    </div>

    <!-- Post Content -->
    <div class="mb-4">
        <p class="text-gray-900 whitespace-pre-wrap leading-relaxed">{{ $post->content }}</p>
    </div>

    <!-- Post Actions -->
    <div class="flex items-center space-x-6 py-4 border-t border-gray-100">
        <!-- Like Button -->
        <form action="{{ route('likes.toggle', $post->id) }}" method="POST" class="inline like-form" data-post-id="{{ $post->id }}">
            @csrf
            <button type="submit" class="flex items-center space-x-2 text-gray-600 hover:text-secondary transition group like-button">
                @if($isLiked)
                    <svg class="w-6 h-6 text-secondary like-icon-filled" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                    </svg>
                    <svg class="w-6 h-6 group-hover:text-secondary like-icon-outline hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                @else
                    <svg class="w-6 h-6 text-secondary like-icon-filled hidden" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                    </svg>
                    <svg class="w-6 h-6 group-hover:text-secondary like-icon-outline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                @endif
                <span class="text-sm font-medium like-count">{{ $post->likes->count() }}</span>
            </button>
        </form>

        <!-- Comment Icon -->
        <div class="flex items-center space-x-2 text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <span class="text-sm font-medium comment-count">{{ $post->comments->count() }}</span>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="comments-container pt-4 border-t border-gray-100 space-y-3">
        @if($post->comments->count() > 0)
            @foreach($post->comments->take(5) as $comment)
                <div class="comment-item flex items-start space-x-3" data-comment-id="{{ $comment->id }}">
                    @if($comment->user->avatar)
                        <img src="{{ Storage::url($comment->user->avatar) }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-accent to-primary flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1 bg-gray-50 rounded-xl px-4 py-2">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <span class="font-semibold text-sm text-gray-900">{{ $comment->user->name }}</span>
                                <span class="text-sm text-gray-700 ml-2">{{ $comment->content }}</span>
                            </div>
                            @can('delete', $comment)
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="ml-2 delete-comment-form" data-comment-id="{{ $comment->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-secondary transition text-xs p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
            @if($post->comments->count() > 5)
                <p class="text-xs text-gray-500 pl-11 view-all-comments">View all <span class="comment-count-display">{{ $post->comments->count() }}</span> comments</p>
            @endif
        @endif
    </div>

    <!-- Add Comment Form -->
    <div class="pt-4 border-t border-gray-100 mt-4">
        <form action="{{ route('comments.store', $post->id) }}" method="POST" class="flex items-center space-x-3 comment-form" data-post-id="{{ $post->id }}">
            @csrf
            @if(auth()->user()->avatar)
                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
            @else
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <input 
                type="text" 
                name="content" 
                placeholder="Write a comment..." 
                required
                class="comment-input flex-1 px-4 py-2 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
            >
            <button 
                type="submit"
                class="comment-submit-btn text-primary hover:text-primary-600 font-semibold text-sm px-4 py-2 transition"
            >
                Post
            </button>
        </form>
    </div>
</div>
