<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import Pagination from "@/Components/Pagination.vue";
import {Link} from "@inertiajs/vue3";
import {relativeDate} from "@/Utilities/date.js";

defineProps([
    'posts'
]);

const formattedDate = (post) => relativeDate(post.created_at);


</script>

<template>
    <AppLayout>
        <Container>
            <ul class="divide-y">
                <li v-for="post in posts.data" :key="post.id">
                    <Link :href="route('posts.show',post.id)" class="group py-4 px-2">
                        <span class="font-bold text-xl block group-hover:text-green-600">
                            {{ post.title }}
                        </span>
                        <span class="text-sm block pt-1 text-gray-600">{{ formattedDate(post) }} ago by {{post.user.name}}</span>
                    </Link>
                </li>
            </ul>

            <Pagination :meta="posts.meta" :only="['posts']"/>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>
