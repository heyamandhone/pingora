<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import categories from '@/routes/categories';

const page = usePage();

const category = page.props.category as {
    id: number;
    name: string;
    slug: string | null;
    description: string | null;
    parent_id: number | null;
};

const parentCategories = page.props.parentCategories as Array<{
    id: number;
    name: string;
}>;

const form = useForm({
    name: category.name,
    slug: category.slug ?? '',
    description: category.description ?? '',
    parent_id: category.parent_id ?? '',
});

function submit() {
    form.put(categories.update.url(category.id));
}
</script>

<template>
    <Head title="Edit Category" />

    <div class="space-y-6">

        <div>
            <h1 class="text-2xl font-semibold">
                Edit Category
            </h1>

            <p class="text-sm text-muted-foreground">
                Update the category details.
            </p>
        </div>

        <form
            class="space-y-6"
            @submit.prevent="submit"
        >

            <div class="grid gap-2">

                <Label for="name">
                    Name
                </Label>

                <Input
                    id="name"
                    v-model="form.name"
                    placeholder="Category name"
                />

                <InputError :message="form.errors.name" />

            </div>

            <div class="grid gap-2">

                <Label for="slug">
                    Slug
                </Label>

                <Input
                    id="slug"
                    v-model="form.slug"
                    placeholder="category-slug"
                />

                <InputError :message="form.errors.slug" />

            </div>

            <div class="grid gap-2">

                <Label for="parent_id">
                    Parent Category
                </Label>

                <select
                    id="parent_id"
                    v-model="form.parent_id"
                    class="rounded-md border border-input bg-background px-3 py-2"
                >
                    <option value="">
                        None
                    </option>

                    <option
                        v-for="parent in parentCategories"
                        :key="parent.id"
                        :value="parent.id"
                    >
                        {{ parent.name }}
                    </option>

                </select>

                <InputError :message="form.errors.parent_id" />

            </div>

            <div class="grid gap-2">

                <Label for="description">
                    Description
                </Label>

                <textarea
                    id="description"
                    v-model="form.description"
                    rows="5"
                    class="rounded-md border border-input bg-background px-3 py-2"
                    placeholder="Category description..."
                />

                <InputError :message="form.errors.description" />

            </div>

            <div class="flex justify-end gap-3">

                <Button
                    variant="outline"
                    as-child
                >
                    <Link :href="categories.index.url()">
                        Cancel
                    </Link>
                </Button>

                <Button
                    type="submit"
                    :disabled="form.processing"
                >
                    Save Changes
                </Button>

            </div>

        </form>

    </div>
</template>