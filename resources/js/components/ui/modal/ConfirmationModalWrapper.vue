<script setup>
import { useConfirm } from '@/composables/useConfirm.ts';
import ConfirmationModal from './ConfirmationModal.vue';
import { nextTick, watchEffect, ref} from 'vue';

const emit = defineEmits(['close']);

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const close = () => {
    emit('close');
};

const {state, confirm, cancel} = useConfirm();

const cancelButtonRef = ref(null);

watchEffect(async() => {
    if (state.show) {
        await nextTick();
        cancelButtonRef.value?.focus();
    }
        // Focus the confirm button when the modal is shown
    
});
</script>

<template>
<ConfirmationModal :show="state.show">
    <template #title>
        {{ state.title }}
    </template>
    <template #content>
        {{ state.message }}
    </template>
    <template #footer>
        <button
            type="button"
            class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            @click="cancel"
            ref="cancelButtonRef"
        >
            Cancel
        </button>
        <button
            type="button"
            class="inline-flex justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 ml-3"
            @click="confirm"
        >
            Confirm
        </button>
    </template>
</ConfirmationModal>
</template>