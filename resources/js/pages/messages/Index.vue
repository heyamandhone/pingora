<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

import conversations from '@/routes/conversations';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';

interface Participant {
    id: number;
    name: string;
    email: string;
}

interface Message {
    id: number;
    message: string;
    created_at: string;
}

interface Conversation {
    id: number;
    type: 'private' | 'group';
    name: string | null;
    participants: Participant[];
    messages: Message[];
    created_at: string;
}

const props = defineProps<{
    conversations: Conversation[];
}>();

const conversationList = computed(() => props.conversations);

function openConversation(id: number) {
    router.visit(
        conversations.show(id).url()
    );
}
</script>
<template>

    <Head title="Conversations" />

    <div class="space-y-6">

        <div class="flex items-center justify-between">

            <div>

                <h1 class="text-2xl font-semibold">
                    Conversations
                </h1>

                <p class="text-sm text-muted-foreground">
                    Manage your conversations.
                </p>

            </div>

            <Link :href="conversations.create()">

                <Button>
                    New Conversation
                </Button>

            </Link>

        </div>

        <div class="rounded-lg border">

            <div v-if="conversationList.length === 0" class="py-16 text-center">

                <p class="text-muted-foreground">
                    No conversations found.
                </p>

            </div>

            <div v-else class="divide-y">

                <div v-for="conversation in conversationList" :key="conversation.id"
                    class="cursor-pointer p-4 transition hover:bg-muted/50" @click="openConversation(conversation.id)">

                    <div class="flex items-center justify-between">

                        <div>

                            <h2 class="font-semibold">

                                {{
                                    conversation.type === 'group'
                                        ? conversation.name
                                        : conversation.participants[0]?.name
                                }}

                            </h2>

                            <p class="mt-1 line-clamp-1 text-sm text-muted-foreground">

                                {{
                                    conversation.messages.length
                                        ? conversation.messages[0].message
                                        : 'No messages yet'
                                }}

                            </p>

                        </div>

                        <div class="text-xs text-muted-foreground">

                            {{
                                conversation.messages.length
                                    ? new Date(
                                        conversation.messages[0].created_at
                                    ).toLocaleDateString()
                                    : ''
                            }}

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</template>