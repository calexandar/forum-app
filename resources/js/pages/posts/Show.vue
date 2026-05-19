<script setup>
import { relativeDate } from '@/utilities/date';
import Pagination from '@/components/ui/pagination/Pagination.vue';



const props = defineProps({
    post: Object,
    comments: Object,
});



const formattedDate = relativeDate(props.post.created_at);
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
            <ul class="divide-y mt-4">
                <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4">
                    <p class="text-sm mb-1">{{ comment.body }}</p>
                    <p class="text-sm text-gray-600 ">{{  comment.user.name }}  commented {{ relativeDate(comment.created_at) }} ago</p>
                </li>
            </ul>

             <!-- Pagination for comments -->
             <Pagination :meta="comments.meta" :only="['comments']"/>
        </div>

    </div>
</template>