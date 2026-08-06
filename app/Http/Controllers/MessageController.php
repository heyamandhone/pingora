<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStoreRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MessageController extends Controller
{
    /**
     * Display messages of a conversation.
     */
    public function index(Conversation $conversation)
    {
        $conversation->load([
            'participants:id,name,email',
            'messages.user:id,name',
        ]);

        return Inertia::render(
            'messages/Index',
            [
                'conversation' => $conversation,
            ]
        );
    }

    /**
     * Store message.
     */
    public function store(
        MessageStoreRequest $request
    ) {
        Message::create([
            'conversation_id' => $request->conversation_id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back()->with(
            'success',
            'Message sent successfully.'
        );
    }

    /**
     * Show single message.
     */
    public function show(Message $message)
    {
        return response()->json($message);
    }

    /**
     * Edit page.
     */
    public function edit(Message $message)
    {
        return response()->json($message);
    }

    /**
     * Update message.
     */
    public function update(
        MessageUpdateRequest $request,
        Message $message
    ) {
        abort_if(
            $message->user_id !== Auth::id(),
            403
        );

        $message->update(
            $request->validated()
        );

        return back()->with(
            'success',
            'Message updated successfully.'
        );
    }

    /**
     * Delete message.
     */
    public function destroy(
        Message $message
    ) {
        abort_if(
            $message->user_id !== Auth::id(),
            403
        );

        $message->delete();

        return back()->with(
            'success',
            'Message deleted successfully.'
        );
    }
}
