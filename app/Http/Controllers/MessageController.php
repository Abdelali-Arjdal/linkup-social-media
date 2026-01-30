<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display inbox with all conversations
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get all conversations for the user
        $conversations = Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne', 'userTwo'])
            ->with(['latestMessage' => function ($query) {
                $query->with('sender');
            }])
            ->withCount(['messages as unread_count' => function ($query) use ($user) {
                $query->where('sender_id', '!=', $user->id)
                    ->where('is_read', false);
            }])
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        // Get the other user for each conversation
        foreach ($conversations as $conversation) {
            $conversation->other_user = $conversation->getOtherUser($user->id);
        }

        return view('messages.index', compact('conversations'));
    }

    /**
     * Show or create conversation with a specific user
     */
    public function show(Request $request, User $user)
    {
        $currentUser = $request->user();

        // Prevent messaging yourself
        if ($currentUser->id === $user->id) {
            return redirect()->route('messages.index')
                ->with('error', 'You cannot message yourself.');
        }

        // Find or create conversation
        $conversation = Conversation::where(function ($query) use ($currentUser, $user) {
            $query->where('user_one_id', $currentUser->id)
                ->where('user_two_id', $user->id);
        })->orWhere(function ($query) use ($currentUser, $user) {
            $query->where('user_one_id', $user->id)
                ->where('user_two_id', $currentUser->id);
        })->first();

        if (!$conversation) {
            // Create new conversation
            $conversation = Conversation::create([
                'user_one_id' => min($currentUser->id, $user->id),
                'user_two_id' => max($currentUser->id, $user->id),
            ]);
        }

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Load messages
        $messages = $conversation->messages()->with('sender')->get();

        return view('messages.show', compact('conversation', 'messages', 'user'));
    }

    /**
     * Store a new message
     */
    public function store(Request $request, Conversation $conversation)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $user = $request->user();

        // Verify user is part of the conversation
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => $request->body,
        ]);

        // Update conversation timestamp
        $conversation->touch();

        if ($request->expectsJson() || $request->ajax()) {
            $message->load('sender');
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name,
                        'avatar' => $message->sender->avatar,
                    ],
                    'created_at' => $message->created_at->diffForHumans(),
                ],
            ]);
        }

        return redirect()->route('messages.show', $conversation->getOtherUser($user->id))
            ->with('success', 'Message sent!');
    }
}
