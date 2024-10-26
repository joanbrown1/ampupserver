<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ConversationController extends Controller
{
    /**
     * Create a new conversation.
     */
    public function create(Request $request)
    {
        $request->validate([
            'sender_email' => 'required|email',
            'receiver_email' => 'required|email',
        ]);

        // Check if the email already exists
        $existingSender = Conversation::where('sender_email', $request->input('sender_email'))->first();

        if ($existingSender) {
            return response()->json(['message' => 'Email already exists. Please use a different email.'], 409);
        }

        // Proceed with registration if the user doesn't exist
        try {
            // Generate a unique convo_id using Str::random(16)
            $convo_id = Str::random(16);

            // Create the conversation
            $conversation = Conversation::create([
                'convo_id' => $convo_id,
                'sender_email' => $request->input('sender_email'),
                'receiver_email' => $request->input('receiver_email'),
            ]);

            // Emit a WebSocket event (assuming you're using a package like Laravel Echo)
            // broadcast(new \App\Events\NewConversation($conversation))->toOthers();

            return response()->json(['message' => 'Conversation created', 'conversation' => $conversation], 200);
        } catch (\Exception $e) {
            Log::error('Error creating conversation: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Get all conversations.
     */
    public function getAll()
    {
        try {
            $conversations = Conversation::orderBy('created_at', 'asc')->get();

            return response()->json($conversations, 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving conversations: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get conversations by sender email.
     */
    public function getBySenderEmail(Request $request)
    {
        $request->validate([
            'sender_email' => 'required|email',
        ]);

        try {
            $conversations = Conversation::where('sender_email', 'like', '%' . $request->sender_email . '%')->get();

            if ($conversations->isEmpty()) {
                return response()->json(['message' => 'No conversations found for the provided email'], 404);
            }

            return response()->json($conversations, 200);
        } catch (\Exception $e) {
            Log::error('Error searching for conversations: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Delete a conversation by ID.
     */
    public function delete($id)
    {
        try {
            $conversation = Conversation::where('convo_id', $id)->first();

            if (!$conversation) {
                return response()->json(['message' => 'Conversation not found'], 404);
            }

            $conversation->delete();

            // Emit a WebSocket event for deletion
            // broadcast(new \App\Events\DeleteConversation($id))->toOthers();

            return response()->json(['message' => 'Conversation deleted successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting conversation: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
