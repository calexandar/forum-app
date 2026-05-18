<script setup>
import { computed } from 'vue';
import { formatDistance, parseISO } from 'date-fns';



const props = defineProps({
    post: Object
});



const formattedDate = computed( () => formatDistance(parseISO(props.post.created_at), new Date()));
</script>

<template>
    <div class="container px-4 py-8 mx-auto">

            <h1 class="text-2xl font-bold mb-4">{{ post.title }}</h1>

        <span class="text-sm text-gray-500 mb-6 block">Published {{ formattedDate }} ago by {{ post.user.name }}</span>

        <article class="mt-6 prose dark:prose-invert">
            <p>{{ post.body }}</p>
        </article>

    </div>
</template>