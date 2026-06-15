<script setup>
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { index, show} from '@/actions/App/Http/Controllers/PostController';
import { Link, useForm } from '@inertiajs/vue3';
import { relativeDate } from '@/utilities/date';

 const props = defineProps({
                posts: Object,
                topics: Object,
                selectedTopic: Object,
                filled:{
                        default: false
                }
        });
 
const formattedDate = (post) => {
        return relativeDate(post.created_at);
}; 

const searchForm = useForm({
        query: '',
});

const search = () => {
        searchForm.get(index({ query: searchForm.query }), {
                preserveState: true,
                replace: true,
        });
}
</script>

<template>

        <div class="flex flex-col gap-4">
                <div>
                        <Link v-if="selectedTopic" :href="index()" class="text-sm text-gray-600 mb-4 inline-block hover:text-indigo-500">Back to all posts</Link>
                        <h1 v-text="selectedTopic ? selectedTopic.name : 'All Topics'" class="text-2xl font-bold mb-4"></h1>
                        <p v-if="selectedTopic" class="text-sm text-gray-600 mb-4">Filtering by topic: <span class="font-medium">{{ selectedTopic.name }}</span></p>
                        <p v-if="selectedTopic" class="text-sm text-gray-600 mb-4">Description: <span class="font-medium">{{ selectedTopic.description }}</span></p>

                
                        <menu class="flex space-x-1 mt-3 overflow-x-auto pb-2 pt-1">
                                <li v-for="topic in topics" :key="topic.id">
                                         <Link
                                          :href="index({ topic: topic.slug })"
                                          :filled="selectedTopic && selectedTopic.id === topic.id"
                                        class="rounded-full px-2 py-1 text-sm mt-2 inline-block border border-pink-500"
                                        :class="{'bg-indego0-500 text-white': selectedTopic && selectedTopic.id === topic.id, 'text-gray-600 hover:bg-gray-100': !selectedTopic || selectedTopic.id !== topic.id}"
                                        >
                                                {{topic.name}}
                                        </Link>
                                </li>
                        </menu>

                        <form @submit.prevent="search" class="mt-4" >
                                <div>
                                        <label for="query" class="block text-sm font-medium text-gray-700">Search</label>
                                        <div class="flex space-x-2">
                                                <input v-model="searchForm.query" type="text" id="query"  class="mt-1 block w-full border rounded p-2" placeholder="Enter post title..." />
                                                <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded">Search</button>
                                        </div>
                                </div>
                        </form>
                </div>
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