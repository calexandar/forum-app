<script setup>
import { relativeDate } from '@/utilities/date';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import Label from '@/components/ui/label/Label.vue';
import { store } from '@/actions/App/Http/Controllers/CommentController';
import { destroy } from '@/actions/App/Http/Controllers/CommentController';
import { computed, defineEmits } from 'vue';



const props = defineProps({
    post: Object,
    comments: Object,
});



const formattedDate = relativeDate(props.post.created_at);

const commentForm = useForm({
    body: '',
});

const emit = defineEmits(['commentDeleted']);

const submitComment = () => {
    commentForm.post(store({post: props.post.id }), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),
    });
};

const commentDeleted = (commentId)  => {
    if (confirm('Are you sure you want to delete this comment?')) {
        router.delete(destroy({comment: commentId, page: props.comments.meta.current_page}), {
            preserveScroll: true,
        });
    }
};

</script>

<template>
    <div class="container px-4 py-8 mx-auto">

            <h1 class="text-2xl font-bold mb-4">{{ post.title }}</h1>

        <span class="text-sm text-gray-500 mb-6 block">Published {{ formattedDate }} ago by {{ post.user.name }}</span>

        <article class="mt-6 prose dark:prose-invert">
            <p>{{ post.body }}</p>
        </article>

        <div>
            <h2 class="text-xl font-semibold mt-8 mb-4">Comments</h2>

            <form v-if="$page.props.auth.user" @submit.prevent="submitComment" class="mt-4">
                <Label class="mb-2 sr-only">Comment</Label>
                <textarea v-model="commentForm.body" class="w-full border rounded p-2 mb-2" placeholder="Add a comment..."></textarea>
                <p v-if="commentForm.errors.body" class="text-sm text-red-600 mt-2">{{ commentForm.errors.body }}</p>
                <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded mt-2" :disabled="commentForm.processing">Add Comment</button>

            </form>
            <ul class="divide-y mt-4">
                <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                    <p class="text-sm mb-1 break-all">{{ comment.body }}</p>
                    <p class="text-sm text-gray-600 ">{{  comment.user.name }}  commented {{ relativeDate(comment.created_at) }} ago</p>
                    <div v-if="comment.can?.delete" class="mt-2">
                        <form  @submit.prevent="$emit('commentDeleted', comment.id)" class="inline">
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 mt-2">Delete</button>
                        </form>
                    </div>
                </li>
            </ul>

             <!-- Pagination for comments -->
             <Pagination :meta="comments.meta" :only="['comments']"/>
        </div>

    </div>
</template>