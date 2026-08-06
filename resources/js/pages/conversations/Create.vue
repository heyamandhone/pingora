<script setup lang="ts">
import { computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

import conversations from '@/routes/conversations';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import InputError from '@/components/InputError.vue';

interface UserOption {
    id: number;
    name: string;
    email: string;
}

const props = defineProps<{
    users: UserOption[];
}>();

const form = useForm({
    type: 'private',

    name: '',

    participant_ids: [] as number[],
});

const isGroup = computed(() => {
    return form.type === 'group';
});

function submit() {
    form.post(
        conversations.store.url()
    );
}
</script>
<template>

    <Head title="Create Conversation" />

    <form class="space-y-8" @submit.prevent="submit">

        <div>

            <h1 class="text-2xl font-semibold">
                Create Conversation
            </h1>

            <p class="text-sm text-muted-foreground">
                Start a new private or group conversation.
            </p>

        </div>

        <div class="rounded-lg border p-6">

            <div class="grid gap-6">

                <div class="space-y-2">

                    <Label>
                        Conversation Type
                    </Label>

                    <select v-model="form.type" class="w-full rounded-md border bg-background px-3 py-2">

                        <option value="private">
                            Private
                        </option>

                        <option value="group">
                            Group
                        </option>

                    </select>

                    <InputError :message="form.errors.type" />

                </div>

                <div v-if="isGroup" class="space-y-2">

                    <Label>
                        Group Name
                    </Label>

                    <Input v-model="form.name" placeholder="Laravel Team" />

                    <InputError :message="form.errors.name" />

                </div>

                <div class="space-y-2">

                    <Label>
                        Participants
                    </Label>

                    <div class="space-y-3 rounded-md border p-4">

                        <label v-for="user in props.users" :key="user.id" class="flex items-center gap-3">

                            <input v-model="form.participant_ids" :value="user.id" type="checkbox" />

                            <div>

                                <div class="font-medium">
                                    {{ user.name }}
                                </div>

                                <div class="text-sm text-muted-foreground">
                                    {{ user.email }}
                                </div>

                            </div>

                        </label>

                    </div>

                    <InputError :message="form.errors.participant_ids" />

                </div>

            </div>

        </div>

        <div class="flex justify-end gap-3">

            <Button type="button" variant="outline" @click="$inertia.visit(conversations.index.url())">
                Cancel
            </Button>

            <Button type="submit" :disabled="form.processing">
                Create Conversation
            </Button>

        </div>

    </form>

</template>