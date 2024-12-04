<script setup>


import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import TextArea from "@/Components/TextArea.vue";

const form = useForm({
    title: '',
    body: '',
})

const createPost = () => {
    form.post(route('posts.store'));
};
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
                    <TextArea rows="25" id="body" class="w-full" v-model="form.body" placeholder="Give it a great body..." />
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
