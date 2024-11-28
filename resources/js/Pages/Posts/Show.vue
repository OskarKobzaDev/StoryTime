<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import {computed} from "vue";
import Pagination from "@/Components/Pagination.vue";
import {relativeDate} from "@/Utilities/date.js";
import Comment from "@/Components/Comment.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps(['post','comments']);
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

</script>

<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="text-2xl font-bold">{{post.title}}</h1>

            <span class="text-sm block mt-1 text-gray-600">{{ foramattedDate }} ago by {{post.user.name}}</span>

            <article class="mt-6">
                <pre class="whitespace-pre-wrap font-sans ">{{ post.body }}</pre>
            </article>

            <div class="mt-2">
                <h2 class="text-2xl mt-6 font-semibold">Comments</h2>

                <form v-if="$page.props.auth.user" @submit.prevent="addComment" class="mt-10">
                    <div>
                        <InputLabel for="body" class="sr-only">Comment</InputLabel>
                        <TextArea id="body" v-model="commentForm.body" rows="4" placeholder="Speak your mind Spock..."/>
                        <InputError :message="commentForm.errors.body" class="mt-1"/>
                    </div>

                    <PrimaryButton type="submit" class="mt-3" :disabled="commentForm.processing">Add comment</PrimaryButton>
                </form>

                <ul class="divide-y mt-4">
                    <li v-for="comment in comments.data" :key="comment.id" class="py-4 px-2">
                        <Comment :comment="comment" />
                    </li>
                </ul>

                <Pagination :meta="comments.meta" :only="['comments']" ></Pagination>
            </div>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>
