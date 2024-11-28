<script setup>

import {relativeDate} from "@/Utilities/date.js";
import {router, usePage} from "@inertiajs/vue3";
import {computed} from "vue";

const props = defineProps(['comment'])

const deleteComment = () => router.delete(route('comments.destroy', props.comment.id),{
    preserveScroll: true,

});

</script>

<template>
    <div class="sm:flex">
        <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
            <img :src="comment.user.profile_photo_url" alt="profile_photo" class="h-10 w-10 rounded-full"/>
        </div>
        <div class="flex justify-between w-full">
            <p class="mt-1 break-all">
                {{ comment.body}}
                <span class="text-sm block pt-1 text-gray-600">by {{ comment.user.name }} {{relativeDate(comment.created_at)}}</span>
            </p>
            <div class="mx-4">
                <form v-if="comment.can?.delete" @submit.prevent="deleteComment">
                    <button type="submit">Delete</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
