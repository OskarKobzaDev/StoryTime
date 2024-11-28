<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import Container from "@/Components/Container.vue";
import {computed} from "vue";
import {parseISO, formatDistance} from "date-fns";
import {Link} from "@inertiajs/vue3";

const props = defineProps(['post','comments']);
const foramattedDate = computed(() => formatDistance(parseISO(props.post.created_at), new Date()));

</script>

<template>
    <AppLayout :title="post.title">
        <Container>
            <h1 class="text-2xl font-bold">{{post.title}}</h1>

            <span class="text-sm block mt-1 text-gray-600">{{ foramattedDate }} ago by {{post.user.name}}</span>

            <article class="mt-6">
                <pre class="whitespace-pre-wrap font-sans ">{{ post.body }}</pre>
            </article>

            <div>
                <h2>Comments</h2>
                <ul class="divide-y">
                    <li v-for="comment in comments.data" :key="comment.id" class="group py-4 px-2">
                        <span class="font-bold text-xl block group-hover:text-green-600">
                            {{ comment.body }}
                        </span>
                        <span class="text-sm block pt-1 text-gray-600">{{ foramattedDate(comment) }} ago by {{comment.user.name}}</span>

                    </li>
                </ul>
            </div>
        </Container>
    </AppLayout>
</template>

<style scoped>

</style>
