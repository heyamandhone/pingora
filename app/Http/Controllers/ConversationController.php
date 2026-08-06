<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConversationStoreRequest;
use App\Models\Conversation;
use App\Models\User;
use App\Http\Requests\ConversationUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConversationController extends Controller
{
    /**
     * Display listing.
     */
    public function index(Request $request)
    {
        $conversations = Auth::user()
            ->conversations()
            ->with([
                'participants:id,name,email',
                'messages' => function ($query) {
                    $query->latest()->limit(1);
                },
            ])
            ->latest()
            ->get();

        return Inertia::render(
            'conversations/Index',
            [
                'conversations' => $conversations,
            ]
        );
    }

    /**
     * Create page.
     */
    public function create()
    {
        return Inertia::render(
            'conversations/Create',
            [
                'users' => User::query()
                    ->whereKeyNot(Auth::id())
                    ->orderBy('name')
                    ->get([
                        'id',
                        'name',
                        'email',
                    ]),
            ]
        );
    }

    /**
     * Store conversation.
     */
    public function store(
        ConversationStoreRequest $request
    ) {
        $conversation = Conversation::create([
            'type' => $request->type,
            'name' => $request->name,
            'created_by' => Auth::id(),
        ]);

        $participants = collect(
            $request->participant_ids
        )
            ->push(Auth::id())
            ->unique();

        $conversation->participants()->sync($participants);

        return redirect()
            ->route(
                'conversations.show',
                $conversation
            )
            ->with(
                'success',
                'Conversation created successfully.'
            );
    }

    /**
     * Show conversation.
     */
    public function show(
        Conversation $conversation
    ) {
        $conversation->load([
            'participants:id,name,email',
            'messages' => function ($query) {
                $query
                    ->with('user:id,name')
                    ->oldest();
            },
        ]);

        return Inertia::render(
            'conversations/Show',
            [
                'conversation' => $conversation,
            ]
        );
    }

    /**
     * Edit page.
     */
    public function edit(
        Conversation $conversation
    ) {
        return Inertia::render(
            'conversations/Edit',
            [
                'conversation' => $conversation,

                'users' => User::orderBy('name')
                    ->get([
                        'id',
                        'name',
                        'email',
                    ]),
            ]
        );
    }

    /**
     * Update conversation.
     */
    public function update(
        ConversationUpdateRequest $request,
        Conversation $conversation
    ) {
        $conversation->update(
            $request->validated()
        );

        return redirect()
            ->route('conversations.index')
            ->with(
                'success',
                'Conversation updated successfully.'
            );
    }

    /**
     * Delete conversation.
     */
    public function destroy(
        Conversation $conversation
    ) {
        $conversation->delete();

        return redirect()
            ->route(
                'conversations.index'
            )
            ->with(
                'success',
                'Conversation deleted successfully.'
            );
    }
}
