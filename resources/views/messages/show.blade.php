@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="max-w-4xl mx-auto">
    <div class="card">
        <!-- Conversation Header -->
        <div class="flex items-center space-x-4 pb-4 border-b border-gray-200 mb-4">
            <a href="{{ route('profile.show', $user->id) }}" class="flex items-center space-x-3 hover:opacity-80 transition">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h2 class="font-semibold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                </div>
            </a>
        </div>

        <!-- Messages Container -->
        <div class="messages-container h-96 overflow-y-auto mb-4 space-y-4 pr-2 flex flex-col" id="messagesContainer">
            @forelse($messages as $message)
                <div class="flex items-start space-x-3 {{ $message->sender_id === auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                    @if($message->sender_id !== auth()->id())
                        @if($message->sender->avatar)
                            <img src="{{ Storage::url($message->sender->avatar) }}" alt="{{ $message->sender->name }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-accent to-primary flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                                {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                            </div>
                        @endif
                    @endif
                    <div class="flex-1 {{ $message->sender_id === auth()->id() ? 'text-right' : '' }}">
                        <div class="inline-block max-w-md px-4 py-2 rounded-2xl {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-gray-100 text-gray-900' }}">
                            <p class="text-sm whitespace-pre-wrap">{{ $message->body }}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 {{ $message->sender_id === auth()->id() ? 'text-right' : '' }}">
                            {{ $message->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-500">No messages yet. Start the conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Message Form -->
        <form action="{{ route('messages.store', $conversation->id) }}" method="POST" class="message-form flex items-center space-x-3 pt-4 border-t border-gray-200 mt-auto" data-conversation-id="{{ $conversation->id }}">
            @csrf
            <input 
                type="text" 
                name="body" 
                placeholder="Type a message..." 
                required
                class="message-input flex-1 px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all duration-200"
                autocomplete="off"
            >
            <button 
                type="submit"
                class="message-submit-btn btn-primary px-6 py-3"
            >
                Send
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.message-form');
    const input = form.querySelector('.message-input');
    const container = document.getElementById('messagesContainer');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Scroll to bottom on load
    container.scrollTop = container.scrollHeight;

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const body = input.value.trim();
        if (!body) return;

        const submitBtn = form.querySelector('.message-submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            });

            const data = await response.json();

            if (data.success && data.message) {
                // Clear input
                input.value = '';

                // Create message HTML
                const isCurrentUser = true; // Always true for sent messages
                const messageHTML = `
                    <div class="flex items-start space-x-3 flex-row-reverse space-x-reverse">
                        <div class="flex-1 text-right">
                            <div class="inline-block max-w-md px-4 py-2 rounded-2xl bg-primary text-white">
                                <p class="text-sm whitespace-pre-wrap">${escapeHtml(data.message.body)}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-right">
                                ${data.message.created_at}
                            </p>
                        </div>
                    </div>
                `;

                // Add message to container
                container.insertAdjacentHTML('beforeend', messageHTML);
                
                // Scroll to bottom
                container.scrollTop = container.scrollHeight;
            }
        } catch (error) {
            console.error('Error sending message:', error);
            form.submit(); // Fallback to form submission
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Send';
        }
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
</script>
@endpush
@endsection

