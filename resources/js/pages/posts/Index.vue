<script setup>
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { show } from 'App/Http/Controllers/PostController';
import { Link } from '@inertiajs/vue3';

 const props = defineProps({
                posts: Object
        });
 
const formattedDate = (post) => {
        return formatDistance(parseISO(post.created_at), new Date());
}; 
</script>

<template>

        <div class="flex flex-col gap-4">
                <div v-for="post in posts.data" :key="post.id">
                        <div class="p-4  rounded shadow">
                                        <Link :href="show(post.id)" class="text-gray-900 hover:text-gray-700">
                                                <h2 class="text-xl font-bold">{{ post.title }}</h2>
                                        </Link>
                                        <span class="text-sm text-gray-500 mb-6 block">Published {{ formattedDate }} ago by {{ post.user.name }}</span>

                        </div>
                </div>
        </div>

        <Pagination :meta="posts.meta"/>


</template>