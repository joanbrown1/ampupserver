<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    /**
     * Create a new message.
     */

     public function create(Request $request)
     {
        $request->validate([
            'convo_id' => 'required|string',
            'sender_email' => 'required|email',
            'message' => 'required|string',
        ]);

        try {
            
            $message = Message::create([
                'convo_id' => $request->input('convo_id'),
                'sender_email' => $request->input('sender_email'),
                'message' => $request->input('message'),
            ]);

            // Emit a WebSocket event (assuming you're using a package like Laravel Echo)
            // broadcast(new \App\Events\NewConversation($conversation))->toOthers();

            return response()->json($message, 200);
        } catch (\Exception $e) {
            Log::error('Error creating conversation: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    

    /**
     * Get all messages.
     */
    public function getAll()
    {
        try {
            $messages = Message::orderBy('created_at', 'asc')->get();

            return response()->json($messages, 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving messages: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get messages by convo_id.
     */
    public function getByConvoId(Request $request)
    {
        $request->validate([
            'convo_id' => 'required|string',
        ]);

        try {
            $messages = Message::where('convo_id', 'like', '%' . $request->convo_id . '%')->get();

            if ($messages->isEmpty()) {
                return response()->json(['message' => 'No messages found for the provided convo_id'], 404);
            }

            return response()->json($messages, 200);
        } catch (\Exception $e) {
            Log::error('Error searching for messages: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }

    /**
     * Delete all messages by convo_id.
     */
    public function deleteByConvoId($convoid)
    {
        try {
            // Delete all messages with the given convo_id
            $deletedCount = Message::where('convo_id', $convoid)->delete();

            if ($deletedCount > 0) {
                // Emit a WebSocket event for deletion (if needed)
                // broadcast(new \App\Events\DeleteMessages($convoid))->toOthers();

                return response()->json(['message' => 'Messages deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'No messages found for the provided convo_id'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting messages: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
