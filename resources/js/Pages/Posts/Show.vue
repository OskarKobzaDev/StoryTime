<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import {computed, nextTick, ref} from "vue";
import Pagination from "@/Components/Pagination.vue";
import {relativeDate} from "@/Utilities/date.js";
import Comment from "@/Components/Comment.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router, useForm, Head, Link} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {useConfirm} from "@/Utilities/Composables/useConfirm.js";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import PageHeading from "@/Components/PageHeading.vue";
import Pill from "@/Components/Pill.vue";
import {HandThumbUpIcon, HandThumbDownIcon} from "@heroicons/vue/20/solid/index.js";
import DangerButton from "@/Components/DangerButton.vue";

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

    cancelEditComment();
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

const deletePost = async () => {
    if(! await confirmation('Are you sure you want to delete this comment? All comunity comments will be lost.')){
        return;
    }

    router.delete(route('posts.delete',{
        post: props.post.id
    }));
};

const editPost = () => {
    alert('edit')
};
</script>

<template>
    <Head>
        <link rel="canonical" :href="post.routes.show"/>
    </Head>
    <AppLayout :title="post.title">
        <Container>
            <Pill :href="route('posts.index', {topic: post.topic.slug})">{{ post.topic.name }}</Pill>
            <div class="flex justify-between">
                <PageHeading class="mt-2">{{post.title}}</PageHeading>
                <div class="flex flex-col space-y-1 text-xs md:text-base text-center">
                    <button
                        v-if="post.can.delete"
                        @click="deletePost()"
                        class="bg-red-500 px-2 py-2 min-w-20 rounded-md text-white hover:bg-red-600"
                    >Delete Post</button>
                    <Link v-if="post.can.update"
                          :href="route('posts.edit',{ post:post.id })"
                          class="bg-indigo-500 px-2 py-2  min-w-20 rounded-md text-white hover:bg-indigo-600"
                    >Edit Post</Link>
                </div>
            </div>


            <span class="text-sm block mt-1 text-gray-600">{{ foramattedDate }} by {{post.user.name}}</span>

            <div class="mt-4">
                <span class="text-green-500 font-bold">
                    {{ post.likes_count }} likes
                </span>
                <div v-if="$page.props.auth.user" class="flex ">
                    <Link v-if="post.can.like" :href="route('likes.store', ['post', post.id])"
                          method="post"
                          class="items-center h-10  flex inline-block bg-indigo-400 hover:bg-pink-500 transition-colors text-white py-1.5 px-3
                                rounded-full">
                        <HandThumbUpIcon class="size-4 mr-3"/>
                        Like Post
                    </Link>

                    <Link v-else :href="route('likes.destroy', ['post', post.id])"
                          method="delete"
                          class="items-center h-10 flex inline-block bg-indigo-400 hover:bg-pink-500 transition-colors text-white py-1.5 px-3
                                rounded-full">
                        <HandThumbDownIcon class="size-4 mr-3"/>
                        Unlike Post
                    </Link>
                </div>
            </div>

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
