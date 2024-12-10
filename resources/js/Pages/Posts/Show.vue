<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import {computed, nextTick, ref} from "vue";
import Pagination from "@/Components/Pagination.vue";
import {relativeDate} from "@/Utilities/date.js";
import Comment from "@/Components/Comment.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router, useForm, Head} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useConfirm} from "@/Utilities/Composables/useConfirm.js";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";

const props = defineProps(['post','comments']);
const commentIdBeingEdited = ref(null);
const commentTextAreaRef = ref(null);
const {confirmation} = useConfirm();

const commentBeingEdited = computed(() => props.comments.data.find(comment => comment.id === commentIdBeingEdited.value));
const foramattedDate = computed(() => relativeDate(props.post.created_at));

const commentForm = useForm({
    body: '',
});

const addComment = () => {
    commentForm.post(route('posts.comments.store', props.post.id),{
        preserveScroll: true,
        onSuccess: () => commentForm.reset(),

    });
};

const updateComment = async () => {
    if(
        ! (await confirmation('Are you sure you want to update this comment?'))
    ){
        setTimeout(() => commentTextAreaRef.value.focus(), 201);
        return;
    }

    commentForm.put(route('comments.update', {
        comment: commentIdBeingEdited.value,
        page: props.comments.meta.current_page,
    }), {
        preserveScroll: true,
        onSuccess: (cancelEditComment),
    })
}

const deleteComment = async (commentId) => {
    if(! await confirmation('Are you sure you want to delete this comment?')){
        return;
    }

    router.delete(route('comments.destroy', {
        comment: commentId,
        page: props.comments.data.length > 1
        ? props.comments.meta.current_page
        : Math.max(props.comments.meta.current_page - 1, 1)
    }),{
        preserveScroll: true,
    });
};

const editComment = (commentId) => {
    commentIdBeingEdited.value = commentId;
    commentForm.body = commentBeingEdited.value?.body;
    commentTextAreaRef.value?.focus();
};

const cancelEditComment = () =>{
    commentIdBeingEdited.value = null;
    commentForm.reset();
};
</script>

<template>
    <Head>
        <link rel="canonical" :href="post.routes.show"/>
    </Head>
    <AppLayout :title="post.title">
        <Container>
            <Pill :href="route('posts.index', {topic: post.topic.slug})">{{ post.topic.name }}</Pill>
            <PageHeading class="mt-2">{{post.title}}</PageHeading>

            <span class="text-sm block mt-1 text-gray-600">{{ foramattedDate }} by {{post.user.name}}</span>

            <article class="mt-6 prose prose-sm max-w-none" v-html="post.html">
            </article>

            <div class="mt-2">
                <h2 class="text-2xl mt-6 font-semibold">Comments</h2>

                <form v-if="$page.props.auth.user" @submit.prevent="() => commentIdBeingEdited ? updateComment() : addComment()" class="mt-10">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <MarkdownEditor ref="commentTextAreaRef" id="body" v-model="commentForm.body" placeholder="Speak your mind Spock..." editorClass="!min-h-[160px]"/>
                        <InputError :message="commentForm.errors.body" class="mt-1"/>
                    </div>

                    <PrimaryButton type="submit" class="mt-3" :disabled="commentForm.processing" v-text="commentIdBeingEdited ? 'Update Comment' : 'Add comment'"></PrimaryButton>
                    <SecondaryButton class="ml-1" v-if="commentIdBeingEdited" @click="cancelEditComment">Cancel</SecondaryButton>
                </form>

                <ul class="divide-y mt-4">
                    <li v-for="comment in comments.data" :key="comment.id" class="py-4 px-2">
                        <Comment @update="editComment" @delete="deleteComment" :comment="comment" />
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']" ></Pagination>
            </div>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>
