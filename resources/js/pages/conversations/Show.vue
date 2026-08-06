<script setup lang="ts">
import { ref, nextTick } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

import messages from '@/routes/messages';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

interface User {
    id: number;
    name: string;
}

interface Message {
    id: number;
    user_id: number;
    message: string;
    created_at: string;
    user: User;
}

interface Participant {
    id: number;
    name: string;
    email: string;
}

interface Conversation {
    id: number;
    type: string;
    name: string | null;
    participants: Participant[];
    messages: Message[];
}

const props = defineProps<{
    conversation: Conversation;
}>();

const form = useForm({
    conversation_id: props.conversation.id,
    message: '',
});

const messagesContainer = ref<HTMLElement | null>(null);

function sendMessage() {
    form.post(
        messages.store.url(),
        {
            preserveScroll: true,

            onSuccess: () => {
                form.reset('message');

                nextTick(() => {
                    messagesContainer.value?.scrollTo({
                        top: messagesContainer.value.scrollHeight,
                        behavior: 'smooth',
                    });
                });
            },
        }
    );
}
</script>
<template>

    <Head title="Conversation" />

    <div class="flex h-[calc(100vh-120px)] flex-col rounded-lg border">

        <!-- Header -->

        <div class="border-b p-4">

            <h2 class="text-lg font-semibold">

                {{
                    conversation.type === 'group'
                        ? conversation.name
                        : conversation.participants[0]?.name
                }}

            </h2>

            <p class="text-sm text-muted-foreground">

                {{ conversation.participants.length }}
                Participant(s)

            </p>

        </div>

        <!-- Messages -->

        <div ref="messagesContainer" class="flex-1 space-y-4 overflow-y-auto p-4">

            <div v-if="conversation.messages.length === 0" class="text-center text-muted-foreground">

                No messages yet.

            </div>

            <div v-for="message in conversation.messages" :key="message.id" class="flex" :class="message.user_id === $page.props.auth.user.id
                    ? 'justify-end'
                    : 'justify-start'
                ">

                <div class="max-w-md rounded-lg px-4 py-2" :class="message.user_id === $page.props.auth.user.id
                        ? 'bg-primary text-primary-foreground'
                        : 'bg-muted'
                    ">

                    <div class="mb-1 text-xs font-semibold">

                        {{ message.user.name }}

                    </div>

                    <div>

                        {{ message.message }}

                    </div>

                    <div class="mt-1 text-right text-[10px] opacity-70">

                        {{
                            new Date(
                                message.created_at
                            ).toLocaleTimeString([], {
                                hour: '2-digit',
                                minute: '2-digit',
                            })
                        }}

                    </div>

                </div>

            </div>

        </div>

        <!-- Input -->

        <form class="flex gap-3 border-t p-4" @submit.prevent="sendMessage">

            <Input v-model="form.message" placeholder="Type a message..." />

            <Button type="submit" :disabled="form.processing ||
                !form.message.trim()
                ">

                Send

            </Button>

        </form>

    </div>

</template>