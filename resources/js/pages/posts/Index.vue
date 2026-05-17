<script setup>
import Pagination from '@/components/ui/pagination/Pagination.vue';

        defineProps({
                posts: Object
        })
</script>

<template>

        <div class="flex flex-col gap-4">
                <div v-for="post in posts.data" :key="post.id">
                        <div class="p-4  rounded shadow">
                                <h2 class="text-xl font-bold">{{ post.title }}</h2>
                        </div>
                </div>
        </div>

        <Pagination :meta="posts.meta"/>


</template>