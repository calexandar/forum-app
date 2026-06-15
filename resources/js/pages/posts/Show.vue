<script setup>
import { relativeDate } from '@/utilities/date';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { useForm, router, usePage, Head, Link } from '@inertiajs/vue3';
import Label from '@/components/ui/label/Label.vue';
import { store, update, destroy} from '@/actions/App/Http/Controllers/CommentController';
import { store as likeStore } from '@/actions/App/Http/Controllers/LikeController'; // Import the likeStore function from the LikeController';
import { destroy as likeDestroy } from '@/actions/App/Http/Controllers/LikeController'; // Import the likeStore function from the LikeController';
import { computed, defineEmits, ref } from 'vue';
import ConfirmationModalWrapper from '@/components/ui/modal/ConfirmationModalWrapper.vue';
import { useConfirm } from '@/composables/useConfirm.ts';
import MarkdownEditor from '@/components/MarkdownEditor.vue';
import { HandThumbUpIcon, HandThumbDownIcon } from '@heroicons/vue/20/solid';
import { Hand } from 'lucide-vue-next';



const props = defineProps({
    post: Object,
    comments: Object,
});



const formattedDate = relativeDate(props.post.created_at);

const commentForm = useForm({
    body: '',
});

const emit = defineEmits(['commentDeleted', 'commentEdit']);

const commentTextAreaRef = ref(null);
const commentIdBeingEdited = ref(null);

const commentBeingEdit = computed(() => {
    return props.comments.data.find(comment => comment.id === commentIdBeingEdited.value);
})
const commentEdit = (commentId) => {
    commentIdBeingEdited.value = commentId;

    commentForm.body = commentBeingEdit.value?.body;

    // Focus the textarea after setting the comment body
    commentTextAreaRef.value?.focus();
};

const cancelCommentEdit = () => {
    commentIdBeingEdited.value = null;
    commentForm.reset();
};

const submitComment = () => {
    commentForm.post(store({post: props.post.id }), {
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),
    });
};

const {confirmation} = useConfirm();

const updateComment = async() => {
    if (! await confirmation('Are you sure you want to update this comment?', 'Do you really want to perform this action? This process cannot be undone.')) {
        return;
    }
    
    commentForm.put(update({
        comment: commentIdBeingEdited.value,
        page: props.comments.meta.current_page
    }), {
        preserveScroll: true,
        onSuccess: cancelCommentEdit,
    });
};

const commentDeleted = async (commentId)  => {
    if (! await confirmation('Are you sure you want to delete this comment?', 'Do you really want to perform this action? This process cannot be undone.')) {
        return;
    }
    router.delete(destroy({comment: commentId, page: props.data.length >1 ? props.comments.meta.current_page :Math.max(props.comments.meta.current_page - 1, 1) }), {
         onSuccess: () => emit('commentDeleted', commentId),   
        
    }),
    {
        preserveScroll: true,
    };
};

</script>

<template>
    <Head title="Show post">

    </Head>

    <div class="container px-4 py-8 mx-auto">

            <h1 class="text-2xl font-bold mb-4">{{ post.title }}</h1>

        <span class="text-sm text-gray-500 mb-6 block">Published {{ formattedDate }} by {{ post.user.name }}</span>

        <div class="mt-4">
            <span class="text-pink-500 font-bold">{{ post.likes_count }} likes</span>
                <div class="text-white">
                    <Link v-if="post.can.like" :href="likeStore({type: 'post', id: props.post.id})" method="post" class="inline-block bg-indigo-600 hover:bg-pink-500 transition-colors text-white py-1.5 px-3 rounded-full">
                        <HandThumbUpIcon class="size-4 inline-block mr-1"/>
                        Like Post
                    </Link>
                    <Link v-else :href="likeDestroy({type: 'post', id: props.post.id})" method="delete" class="inline-block bg-indigo-600 hover:bg-pink-500 transition-colors text-white py-1.5 px-3 rounded-full">
                        <HandThumbDownIcon class="size-4 inline-block mr-1"/>
                        Unlike Post
                    </Link>
                </div>
        </div>

        <article class="mt-6 prose prose-sm max-w-none dark:prose-invert" v-html="post.html">
        </article>

        <div>
            <h2 class="text-xl font-semibold mt-8 mb-4">Comments</h2>

            <form v-if="$page.props.auth.user" @submit.prevent="() => commentIdBeingEdited ? updateComment() : submitComment()" class="mt-4">
                <Label class="mb-2 sr-only">Comment</Label>
                <MarkdownEditor ref="commentTextAreaRef" v-model="commentForm.body" class="w-full border rounded p-2 mb-2" placeholder="Add a comment..." editorClass="[min-h-100px]"/>
                <p v-if="commentForm.errors.body" class="text-sm text-red-600 mt-2">{{ commentForm.errors.body }}</p>
                <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded mt-2" :disabled="commentForm.processing" v-text="commentIdBeingEdited ? 'Update Comment' : 'Add Comment'"></button>
                <button v-if="commentIdBeingEdited" type="button" class="px-4 py-2 bg-gray-500 text-white rounded mt-2 ml-2" @click="cancelCommentEdit">Cancel</button>
            </form>
            <ul class="divide-y mt-4 flex-1">
                <li v-for="comment in comments.data" :key="comment.id" class="px-2 py-4 flex-1">
                    <div class="mb-2 prose prose-sm dark:prose-invert" v-html="comment.html"></div>
                    <p class="text-sm text-gray-600 ">{{  comment.user.name }}  commented {{ relativeDate(comment.created_at) }} ago | <span class="text-pink-500">{{ comment.likes_count }} likes</span></p>
                    <div  class="mt-2 flex space-x-4 justify-end"> 
                        <form v-if="comment.can?.update" @submit.prevent="commentEdit(comment.id)" class="inline">
                            <button type="submit" class="text-sm text-indigo-600 hover:font-extrabold hover:underline mr-2">Edit</button>
                        </form>
                        <form v-if="comment.can?.delete" @submit.prevent="commentDeleted(comment.id)" class="inline">
                            <button type="submit" class="text-sm text-red-600 hover:font-extrabold hover:underline mr-2">Delete</button>
                        </form>
                    </div>
                </li>
            </ul>

             <!-- Pagination for comments -->
             <Pagination :meta="comments.meta" :only="['comments']"/>
        </div>

    </div>
    <ConfirmationModalWrapper/>
</template>