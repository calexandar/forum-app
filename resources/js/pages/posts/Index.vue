<script setup>
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { show } from '@/actions/App/Http/Controllers/PostController';
import { Link } from '@inertiajs/vue3';
import { formatDistance, parseISO } from 'date-fns';

 const props = defineProps({
                posts: Object
        });
 
const formattedDate = (post) => {
        return formatDistance(parseISO(post.created_at), new Date());
}; 
</script>

<template>

        <div class="flex flex-col gap-4">
                <ul class="divide-y">
                        <li v-for="post in posts.data" :key="post.id" class="px-2 py-4">
                                <Link :href="show(post)" class="group">
                                        <span class="font-bold text-lg group-hover:text-indigo-500">{{ post.title }}</span>
                                        <span class="block mt-1 text-sm text-gray-600">Published {{ formattedDate(post) }} ago by {{ post.user.name }}</span>
                                </Link>
                        </li>
                </ul>
        </div>
        
        <!-- <Pagination :meta="posts.meta"/> -->


</template>