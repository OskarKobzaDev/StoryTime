<script setup>


import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import MarkdownEditor from "@/Components/MarkdownEditor.vue";
import {isInProduction} from "@/Utilities/environment.js";

const form = useForm({
    title: '',
    body: '',
})

const createPost = () => {
    form.post(route('posts.store'));
};


const autofill = async () => {
    if (isInProduction()){
        return;
    }
    const response = await axios.get('/local/post-content');

    form.title = response.data.title;
    form.body = response.data.body;
}

</script>

<template>
    <AppLayout>
        <Container >
            <h1 class="text-2xl font-bold">Create a Post</h1>

            <form @submit.prevent="createPost">
                <div>
                    <InputLabel for="title">Title</InputLabel>
                    <TextInput id="title" class="w-full" v-model="form.title" placeholder="Give it a great title..." />
                    <InputError :message="form.errors.title" class="mt-1"/>
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
                    <PrimaryButton type="submit">Create Post</PrimaryButton>
                </div>
            </form>

        </Container>
    </AppLayout>
</template>

<style scoped>

</style>
