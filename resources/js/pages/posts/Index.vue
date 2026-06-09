<script setup>
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { index, show} from '@/actions/App/Http/Controllers/PostController';
import { Link } from '@inertiajs/vue3';
import { relativeDate } from '@/utilities/date';

 const props = defineProps({
                posts: Object
        });
 
const formattedDate = (post) => {
        return relativeDate(post.created_at);
}; 
</script>

<template>

        <div class="flex flex-col gap-4">
                <ul class="divide-y">
                        <li v-for="post in posts.data" :key="post.id" class="px-2 py-4">
                                <Link :href="post.routes.show" class="group">
                                        <span class="font-bold text-lg group-hover:text-indigo-500">{{ post.title }}</span>
                                        <span class="block mt-1 text-sm text-gray-600">Published {{ formattedDate(post) }} ago by {{ post.user.name }}</span>
                                </Link>
                                <Link :href="index({ topic: post.topic.slug })"
                                class="rounded-full px-2 py-1 text-sm mt-2 inline-block border border-pink-500">
                                         {{post.topic.name}}
                                </Link>
                        </li>
                </ul>
        </div>
        
        <Pagination :meta="posts.meta"/>


</template>