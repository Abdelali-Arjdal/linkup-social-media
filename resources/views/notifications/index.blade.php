@extends('layouts.app', ['title' => 'Notifications'])

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
    </div>

    @if($notifications->count() > 0)
        <div class="card divide-y divide-gray-100">
            @foreach($notifications as $notification)
                <div class="p-4 hover:bg-gray-50 transition {{ !$notification->is_read ? 'bg-primary-50 border-l-4 border-primary' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-gray-900 font-medium">{{ $notification->message }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="card text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <p class="text-gray-500 text-lg">No notifications yet</p>
        </div>
    @endif
</div>
@endsection

