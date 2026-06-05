<script setup>
import { Placeholder } from '@tiptap/extensions'
  import { Editor, EditorContent} from '@tiptap/vue-3'
  import StarterKit from '@tiptap/starter-kit'
  import { watch } from 'vue';
  import { Markdown } from 'tiptap-markdown';
  import 'remixicon/fonts/remixicon.css'

  
  const props = defineProps({
      modelValue: String,
      editorClass: String,
    });
    
    const emit = defineEmits(['update:modelValue']);
    
    const editor = new Editor({
      extensions: [
        StarterKit.configure({
          heading: {
            levels: [2, 3, 4],
          },
        }),
        Markdown,
        Placeholder.configure({
            placeholder: 'Enter some text...',
        })
      ],
        editorProps: {
            attributes: {
            class: `min-h-[200px] prose prose-sm max-w-none py-1.5 px-3 ${props.editorClass}`,
            },
        },
        onUpdate: ({ editor }) => {
          emit('update:modelValue', editor.storage.markdown.getMarkdown());
        },
    });

    defineExpose({
        focus: () => editor.chain().focus().run(),
    });

    watch(() => props.modelValue, (newValue) => {
      if (newValue === editor.storage.markdown.getMarkdown()) {
        return;
    }
    editor.commands.setContent(newValue);

    }, { immediate: true });

    const promptUserForHref = () => {
        if (editor.value?.isActive('link')) {
            return editor.value?.chain().focus().unsetLink().run();
        }

      const href = prompt('Enter the href');

      if (!href) {
        return editor.value?.chain().focus().run();
    }
    editor.value?.chain().focus().setLink({ href }).run();
    };

</script>

<style scoped >
:deep(.tiptap p.is-editor-empty:first-child::before ){
  color: #adb5bd;
  content: attr(data-placeholder);
  float: left;
  height: 0;
  pointer-events: none;
}

</style>

<template>
  <div v-if="editor" class="border rounded">
    <menu class="flex devide-x gap-2 mb-2">
        <li><button @click="() => editor.chain().focus().toggleBold().run()" class="font-bold px-3 py-2 rouded-tl-md "
            :class="[
               editor.isActive('bold') ? 'bg-indigo-500 text-white': 'hover:bg-gray-200'
            ]"
            >
            <i class="ri-bold"></i>
            </button>
        </li>
        <li><button @click="() => editor.chain().focus().toggleItalic().run()" class="italic px-3 py-2 rouded-tl-md "
            :class="[
               editor.isActive('italic') ? 'bg-indigo-500 text-white': 'hover:bg-gray-200'
            ]"
            >
            <i class="ri-italic"></i>
            </button>
        </li>
        <li><button @click="() => editor.chain().focus().toggleStrike().run()" class="px-3 py-2 rouded-tl-md "
            :class="[
               editor.isActive('strike') ? 'bg-indigo-500 text-white': 'hover:bg-gray-200'
            ]"
            >
            <i class="ri-strikethrough"></i>
            </button>
        </li>
        <li><button @click="() => editor.chain().focus().toggleBlockquote().run()" class="px-3 py-2 rouded-tl-md "
            :class="[
               editor.isActive('blockquote') ? 'bg-indigo-500 text-white': 'hover:bg-gray-200'
            ]"
            >
            <i class="ri-double-quotes-l"></i>
            </button>
        </li>
        <li><button @click="() => editor.chain().focus().toggleBulletList().run()" class="px-3 py-2 rouded-tl-md "
            :class="[
               editor.isActive('bulletList') ? 'bg-indigo-500 text-white': 'hover:bg-gray-200'
            ]"
            >
            <i class="ri-list-unordered"></i>
            </button>
        </li>
        <li><button @click="() => editor.chain().focus().toggleOrderedList().run()" class="px-3 py-2 rouded-tl-md "
            :class="[
               editor.isActive('orderedList') ? 'bg-indigo-500 text-white': 'hover:bg-gray-200'
            ]"
            >
            <i class="ri-list-ordered"></i>
            </button>
        </li>
        <li><button @click="promptUserForHref" class="px-3 py-2 rouded-tl-md "
            :class="[
               editor.isActive('link') ? 'bg-indigo-500 text-white': 'hover:bg-gray-200'
            ]"
            >
            <i class="ri-link"></i>
            </button>
        </li>
        <li><button @click="() => editor.chain().focus().toggleHeading({ level: 2 }).run()" class="px-3 py-2 rouded-tl-md "
            :class="[
               editor.isActive('heading', { level: 2 }) ? 'bg-indigo-500 text-white': 'hover:bg-gray-200'
            ]"
            >
            <i class="ri-h-1"></i>
            </button>
        </li>
        <li><button @click="() => editor.chain().focus().toggleHeading({ level: 3 }).run()" class="px-3 py-2 rouded-tl-md "
            :class="[
               editor.isActive('heading', { level: 3 }) ? 'bg-indigo-500 text-white': 'hover:bg-gray-200'
            ]"
            >
            <i class="ri-h-2"></i>
            </button>
        </li>
        <li><button @click="() => editor.chain().focus().toggleHeading({ level: 4 }).run()" class="px-3 py-2 rouded-tl-md "
            :class="[
               editor.isActive('heading', { level: 4 }) ? 'bg-indigo-500 text-white': 'hover:bg-gray-200'
            ]"
            >
            <i class="ri-h-3"></i>
            </button>
        </li>
        <slot name="toolbar" :editor="editor"/>
    </menu>
    <EditorContent :editor="editor" />
  </div>
</template>

