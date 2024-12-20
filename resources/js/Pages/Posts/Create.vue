<script setup>


import {router, useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import {isInProduction} from "@/Utilities/environment.js";
import PageHeading from "@/Components/PageHeading.vue";
import {computed} from "vue";

const props = defineProps({
    topics: {
        type: Array,
        required: true,
    },
    post: {
        type: Object,
        default: null,
    },
});

const form = useForm({
    title: props.post ? props.post.title : '',
    topic_id: props.topics[0].id,
    body: props.post ? props.post.body : '',
})

const submitForm = () => {
    if(props.post){
        form.put(route('posts.update', props.post.id));
    }else{
        form.post(route('posts.store'));
    }
};
const cancelCreate = () =>{
    history.back();
};
const autofill = async () => {
    if (isInProduction()){
        return;
    }
    const response = await axios.get('/local/post-content');

    form.title = response.data.title;
    form.body = response.data.body;
}
const pageTitle = computed(() => {
    return editOrCreate ? 'Edit' : 'Create';
});
const editOrCreate = computed(()=>{
    return Boolean(props.post && props.post.title);
})

</script>

<template>
    <AppLayout>
        <Container >
            <PageHeading>{{ pageTitle }} Post</PageHeading>

            <form @submit.prevent="submitForm">
                <div>
                    <InputLabel for="title">Title</InputLabel>
                    <TextInput id="title" class="w-full" v-model="form.title" placeholder="Give it a great title..." />
                    <InputError :message="form.errors.title" class="mt-1"/>
                </div>
                <div>
                    <InputLabel for="topic_id">Select a Topic</InputLabel>
                    <select v-model="form.topic_id" id="topic_id" class="mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option v-for="topic in topics" :key="topic.id" :value="topic.id">
                            {{ topic.name }}
                        </option>
                    </select>
                    <InputError :message="form.errors.topic_id" class="mt-1"/>
                </div>
                <div>
                    <InputLabel for="body">Body</InputLabel>
                    <MarkdownEditor v-model="form.body" >
                        <template #toolbar="{ editor }">
                            <li v-if="! isInProduction()">
                                <button @click="autofill"
                                        title="Autofill" type="button"
                                        class="px-3 py-2">
                                    <i class="ri-article-line"></i>
                                </button>
                            </li>
                        </template>
                    </MarkdownEditor>
                    <InputError :message="form.errors.body" class="mt-1"/>
                </div>
                <div class="mt-4 flex justify-between">
                    <SecondaryButton @click="cancelCreate">Cancel</SecondaryButton>
                    <PrimaryButton type="submit">{{editOrCreate ? 'Save' : 'Create Post'}}</PrimaryButton>
                </div>
            </form>

        </Container>
    </AppLayout>
</template>

<style scoped>

</style>
