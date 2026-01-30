@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="max-w-4xl mx-auto">
    <div class="card">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Messages</h1>
        </div>

        @if($conversations->count() > 0)
            <div class="space-y-2">
                @foreach($conversations as $conversation)
                    @php
                        $otherUser = $conversation->getOtherUser(auth()->id());
                        $latestMessage = $conversation->latestMessage;
                        $unreadCount = $conversation->unreadCount(auth()->id());
                    @endphp
                    <a href="{{ route('messages.show', $otherUser->id) }}" class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gray-50 transition-all duration-200 border border-transparent hover:border-gray-200">
                        @if($otherUser->avatar)
                            <img src="{{ Storage::url($otherUser->avatar) }}" alt="{{ $otherUser->name }}" class="w-14 h-14 rounded-full object-cover flex-shrink-0">
                        @else
                            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="font-semibold text-gray-900">{{ $otherUser->name }}</h3>
                                @if($latestMessage)
                                    <span class="text-xs text-gray-500 flex-shrink-0 ml-2">
                                        {{ $latestMessage->created_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                            @if($latestMessage)
                                <p class="text-sm text-gray-600 truncate">
                                    {{ Str::limit($latestMessage->body, 60) }}
                                </p>
                            @else
                                <p class="text-sm text-gray-400 italic">No messages yet</p>
                            @endif
                        </div>
                        @if($unreadCount > 0)
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-primary rounded-full">
                                    {{ $unreadCount }}
                                </span>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $conversations->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No messages yet</h3>
                <p class="text-gray-500 mb-6">Start a conversation by visiting someone's profile and clicking "Message".</p>
            </div>
        @endif
    </div>
</div>
@endsection

