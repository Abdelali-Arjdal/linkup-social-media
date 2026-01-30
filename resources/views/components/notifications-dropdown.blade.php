@php
    $notifications = auth()->user()->notifications()->with(['notifiable', 'post'])->latest()->take(10)->get();
    $unreadCount = auth()->user()->unreadNotifications()->count();
@endphp

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-primary transition rounded-lg hover:bg-gray-100">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 block h-2.5 w-2.5 rounded-full bg-secondary ring-2 ring-white"></span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" x-cloak
         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-medium border border-gray-200 z-50 max-h-96 overflow-y-auto">
        <div class="p-4 border-b border-gray-200 sticky top-0 bg-white">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Notifications</h3>
                @if($unreadCount > 0)
                    <span class="badge-primary">{{ $unreadCount }} new</span>
                @endif
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($notifications as $notification)
                <a href="{{ route('notifications.index') }}" class="block p-4 hover:bg-gray-50 transition {{ !$notification->is_read ? 'bg-primary-50' : '' }}">
                    <p class="text-sm text-gray-900 font-medium">{{ $notification->message }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <div class="p-8 text-center text-gray-500 text-sm">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p>No notifications yet</p>
                </div>
            @endforelse
            @if($notifications->count() > 0)
                <div class="p-4 border-t border-gray-200">
                    <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-primary font-medium hover:text-primary-600 transition">
                        View all notifications
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
