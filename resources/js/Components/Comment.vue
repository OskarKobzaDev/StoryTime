<script setup>

import {relativeDate} from "@/Utilities/date.js";
import {Link, router, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import {HandThumbUpIcon,HandThumbDownIcon} from "@heroicons/vue/20/solid/index.js";

const props = defineProps(['comment'])

const emit = defineEmits(['delete','update'])



</script>

<template>
    <div class="sm:flex">
        <div class="mb-4 flex-shrink-0 sm:mb-0 sm:mr-4">
            <img
                :src="comment.user.profile_photo_url"
                class="h-10 w-10 rounded-full"
            />
        </div>
        <div class="flex-1">
            <div
                class="prose prose-sm mt-1 max-w-none"
                v-html="comment.html"
            ></div>
            <span
                class="block pt-1 text-xs text-gray-600 first-letter:uppercase"
            >By {{ comment.user.name }}
                {{ relativeDate(comment.created_at) }} | <span class="text-green-500"> {{ comment.likes_count }} likes</span>
            </span
            >
            <div class="mt-2 flex justify-end space-x-3 empty:hidden">
                <div v-if="$page.props.auth.user" class="flex ">
                    <Link v-if="comment.can.like" preserve-scroll :href="route('likes.store', ['comment', comment.id])"
                          method="post"
                          class="items-center h-10  flex inline-block text-gray-700 hover:text-pink-500 transition-colors
                                rounded-full">
                        <HandThumbUpIcon class="size-4 mr-3"/>
                    </Link>

                    <Link v-else preserve-scroll :href="route('likes.destroy', ['comment', comment.id])"
                          method="delete"
                          class="items-center h-10 flex inline-block text-gray-700 hover:text-pink-500 transition-colors
                                rounded-full">
                        <HandThumbDownIcon class="size-4"/>
                    </Link>
                </div>
                <form v-if="comment.can?.delete" @submit.prevent="$emit('delete', comment.id)">
                    <button class="font-bold text-white hover:bg-red-800 bg-red-700 py-1 px-3 rounded-lg " type="submit">Delete</button>
                </form>
                <form v-if="comment.can?.update" @submit.prevent="$emit('update', comment.id)">
                    <button class="font-bold text-white hover:bg-blue-800 bg-blue-700 py-1 px-3 rounded-lg " type="submit">Edit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
