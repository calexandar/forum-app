<script setup>
  import { Editor, EditorContent} from '@tiptap/vue-3'
  import StarterKit from '@tiptap/starter-kit'
  import { watch } from 'vue';
  import { Markdown } from 'tiptap-markdown';

  
  const props = defineProps({
      modelValue: String,
    });
    
    const emit = defineEmits(['update:modelValue']);
    
    const editor = new Editor({
      extensions: [
        StarterKit,
        Markdown,
      ],
        editorProps: {
            attributes: {
            class: 'min-h-[200px] prose prose-sm max-w-none py-1.5 px-3',
            },
        },
        onUpdate: ({ editor }) => {
          emit('update:modelValue', editor.storage.markdown.getMarkdown());
        },
    });

    watch(() => props.modelValue, (newValue) => {
      if (newValue === editor.storage.markdown.getMarkdown()) {
        return;
    }
    editor.commands.setContent(newValue);

    }, { immediate: true });

</script>

<template>
  <editor-content :editor="editor" />
</template>