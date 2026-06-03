<script setup>
import { useForm } from '@inertiajs/vue3';
import { store } from '@/actions/App/Http/Controllers/PostController';
import MarkdownEditor from '@/components/MarkdownEditor.vue';

const form = useForm({
    title: '',
    body: '',
});

const createPost = () => {
    form.post(store(), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <div class="container px-4 py-8 mx-auto">

        <h1 class="text-2xl font-bold mb-4">Create Post</h1>

        <form @submit.prevent="createPost" class="mb-6">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" v-model="form.title" class="mt-1 block w-full border rounded p-2" placeholder="Enter post title..." />
                <p v-if="form.errors.title" class="text-sm text-red-600 mt-2">{{ form.errors.title }}</p>
            </div>
            <div class="mb-4">
                <label for="body" class="block text-sm font-medium text-gray-700">Body</label>
                <MarkdownEditor v-model="form.body" />
                <textarea id="body" v-model="form.body" class="mt-2 block w-full border rounded p-2" placeholder="Enter post body..." rows="25"></textarea>
                <p v-if="form.errors.body" class="text-sm text-red-600 mt-2">{{ form.errors.body }}</p>
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded" >Create Post</button>
        </form>
    </div>
</template>