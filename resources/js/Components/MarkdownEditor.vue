<script setup>

import {EditorContent, useEditor} from "@tiptap/vue-3";
import {StarterKit} from "@tiptap/starter-kit";
import {onMounted, watch} from "vue";
import {Markdown} from "tiptap-markdown";
import 'remixicon/fonts/remixicon.css';
import { Link } from '@tiptap/extension-link'
import {Placeholder} from "@tiptap/extension-placeholder";

const model = defineModel();

const props = defineProps({
    editorClass: '',
    placeholder: null,
})

const editor = useEditor({
    extensions: [
      StarterKit.configure({
          heading: {
              levels: [2,3,4],
          }
      }),
      Link,
      Markdown,
      Placeholder.configure({
          placeholder: props.placeholder,
      })
    ],
    editorProps: {
        attributes: {
            class: `min-h-[512px] prose prose-sm max-w-none py-1.5 px-3 rounded-md ${props.editorClass}`,
        },
    },
    onUpdate: () => model.value = editor.value?.storage.markdown.getMarkdown(),
});

defineExpose({focus: () => editor.value.commands.focus()});

onMounted(()=>{
    watch(
        model,
        (value) =>{
            if(value === editor.value?.storage.markdown.getMarkdown())
            {
                return;
            }
            editor.value?.commands.setContent(value);
        }, {immediate: true});
});

const promptUserForHref = () =>{
    if(editor.value?.isActive('link')){
        return editor.value?.chain().unsetLink().run();
    }
    const href = prompt('Where do you want to link to?')

    if(! href){
        return editor.value?.chain().focus().run();
    }

    return editor.value?.chain().focus().setLink({href}).run();

};

</script>

<template>
    <div v-if="editor" class="bg-white rounded-md border-0 shadow-sm ring-1
        ring-inset ring-gray-300 focus:ring-2 focus:ring-green-600 sm:text-sm sm:leading-6">
        <menu class="flex border-b flex-wrap">
            <li>
                <button @click="()=> editor.chain().focus().toggleBold().run()"
                        title="Bold" type="button"
                        class="px-3 py-2 rounded-tl-md min-w-12 border-r border-b"
                        :class="[ editor.isActive('bold') ? 'bg-green-500 text-white' : 'hover:bg-green-200 ']">
                    <i class="ri-bold"></i>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleItalic().run()"
                        title="Italic" type="button"
                        class="px-3 py-2 min-w-12 border-r border-b"
                        :class="[ editor.isActive('italic') ? 'bg-green-500 text-white' : 'hover:bg-green-200 ']">
                    <i class="ri-italic"></i>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleStrike().run()"
                        title="Strikethrough" type="button"
                        class="px-3 py-2 min-w-12 border-r border-b"
                        :class="[ editor.isActive('strike') ? 'bg-green-500 text-white' : 'hover:bg-green-200 ']">
                    <i class="ri-strikethrough"></i>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleBlockquote().run()"
                        title="Blockquote" type="button"
                        class="px-3 py-2 min-w-12 border-r border-b"
                        :class="[ editor.isActive('blockquote') ? 'bg-green-500 text-white' : 'hover:bg-green-200 ']">
                    <i class="ri-double-quotes-l"></i>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleBulletList().run()"
                        title="Bullet List" type="button"
                        class="px-3 py-2 min-w-12 border-r border-b"
                        :class="[ editor.isActive('bulletList') ? 'bg-green-500 text-white' : 'hover:bg-green-200 ']">
                    <i class="ri-list-unordered"></i>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleOrderedList().run()"
                        title="Numeric List" type="button"
                        class="px-3 py-2 min-w-12 border-r border-b"
                        :class="[ editor.isActive('orderedList') ? 'bg-green-500 text-white' : 'hover:bg-green-200 ']">
                    <i class="ri-list-ordered"></i>
                </button>
            </li>
            <li>
                <button @click="promptUserForHref"
                        title="Add link" type="button"
                        class="px-3 py-2 min-w-12 border-r border-b"
                        :class="[ editor.isActive('link') ? 'bg-green-500 text-white' : 'hover:bg-green-200 ']">
                    <i class="ri-link"></i>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleHeading({level: 2}).run()"
                        title="Heading 1" type="button"
                        class="px-3 py-2 min-w-12 border-r border-b"
                        :class="[ editor.isActive('heading', { level: 2}) ? 'bg-green-500 text-white' : 'hover:bg-green-200 ']">
                    <i class="ri-h-1"></i>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleHeading({level: 3}).run()"
                        title="Heading 2" type="button"
                        class="px-3 py-2 min-w-12 border-r border-b"
                        :class="[ editor.isActive('heading', { level: 3}) ? 'bg-green-500 text-white' : 'hover:bg-green-200 ']">
                    <i class="ri-h-2"></i>
                </button>
            </li>
            <li>
                <button @click="()=> editor.chain().focus().toggleHeading({level: 4}).run()"
                        title="Heading 3" type="button"
                        class="px-3 py-2 min-w-12 border-r border-b"
                        :class="[ editor.isActive('heading', { level: 4}) ? 'bg-green-500 text-white' : 'hover:bg-green-200 ']">
                    <i class="ri-h-3"></i>
                </button>
            </li>
            <slot name="toolbar" :editor="editor" />
        </menu>
        <EditorContent :editor="editor" />
    </div>
</template>

<style scoped>
:deep(.tiptap p.is-editor-empty:first-child::before) {
    @apply text-gray-400 float-left h-0 pointer-events-none;
    content: attr(data-placeholder);
}
</style>
