<script setup>
import { Head, Link } from '@inertiajs/vue3';
import 'remixicon/fonts/remixicon.css';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <div class="h-full bg-gray-50 text-black/50 dark:bg-black dark:text-white/50 bg-gradient-to-br from-green-200 to-slate-200">
        <img id="background" class="absolute -left-20 top-0 max-w-[877px]" />
        <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                    </div>
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end space-x-2">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="bg-indigo-500 rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Posts
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="bg-indigo-500 rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Log in
                            </Link>
                            <Link
                                :href="route('posts.index')"
                                class="bg-indigo-500 rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                            >
                                Posts
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        <div
                            href="https://laravel.com/docs"
                            id="docs-card"
                            class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                        >
                            <div class="relative flex items-center gap-6 lg:items-end">
                                <div id="docs-card-content" class="flex items-start gap-6 lg:flex-col">
                                    <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-12">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120" style="enable-background:new 0 0 120 120" xml:space="preserve"><switch><g><path d="M98.3 75.3v-5c9.2 0 16.7-7.5 16.7-16.7s-7.5-16.7-16.7-16.7v-5c11.9 0 21.7 9.7 21.7 21.7s-9.7 21.7-21.7 21.7z"/><path d="M100.8 107.2H83.1V0h17.8v107.2zm-12.8-5h7.8V5H88v97.2z"/><path d="M88 101.9 12.8 74.5V32.7L88 5.3v96.6zM17.8 71l65.3 23.8V12.4L17.8 36.2V71z"/><path d="M50.2 120H25.9l-6.4-44.4 4.9-.7 5.8 40.1h14.1l-5.4-32.3 4.9-.9zM17.8 68.9H0V38.3h17.8v30.6zM5 63.9h7.8V43.3H5v20.6z"/><path d="M26.2 102.2h18.9v5H26.2zM25.6 29.8h5v47.7h-5z"/></g></switch></svg>
                                    </div>

                                    <div class="pt-3 sm:pt-5 lg:pt-0">
                                        <h2 class="text-xl font-semibold text-black dark:text-white">About StoryTime</h2>
                                        <p class="mt-4 text-base text-black dark:text-white/80">
                                            <strong>StoryTime</strong> is my next application aimed at learning Vue as the frontend for Laravel. It is a responsively designed, simple forum with asynchronous elements. During development, I focused on:
                                        </p>
                                        <ul class="mt-2 list-disc list-inside text-base text-black dark:text-white/80">
                                            <li><strong>Data Security:</strong> Utilizing policies, gates, and the <code>$hidden</code> variable to protect data delivered from the backend.</li>
                                            <li><strong>KISS Principle:</strong> Avoiding over-engineering functionalities when they are not necessary.</li>
                                            <li><strong>Test-Driven Development (TDD):</strong> Writing integration and unit tests before implementing given functionalities.</li>
                                            <li><strong>Containerized Development Environment:</strong> Fully containerizing the project for better performance and management.</li>
                                            <li><strong>Authorization:</strong> Implementing full authorization using Jetstream.</li>
                                            <li><strong>Database Optimization:</strong> Enforced Eager Loading and database denormalization for optimizing the likes feature.</li>
                                            <li><strong>Resource API:</strong> Enhancing communication between frontend and backend.</li>
                                            <li><strong>JavaScript Promises:</strong> Controlling modal windows using JS promises.</li>
                                            <li><strong>SEO-friendly URLs:</strong> Using slugs for posts.</li>
                                            <li><strong>Customizable WYSIWYG Editor:</strong> Allowing users to edit content.</li>
                                            <li><strong>Responsive Design:</strong> Adapting to various devices and screen resolutions.</li>
                                        </ul>
                                        <p class="mt-4 text-base text-black dark:text-white/80">
                                            <strong>StoryTime</strong> offers full forum functionality, including CRUD operations for posts and comments, support for likes, search functionality, and planned support for Server-Side Rendering (SSR). The application is designed with simplicity and efficiency in mind, while also providing robust security and performance optimization.
                                        </p>
                                        <p class="mt-4 text-base text-black dark:text-white/80">
                                            If you have any questions or suggestions regarding the project, feel free to contact me!
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                        >
                            <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M21 8V20.9932C21 21.5501 20.5552 22 20.0066 22H3.9934C3.44495 22 3 21.556 3 21.0082V2.9918C3 2.45531 3.4487 2 4.00221 2H14.9968L21 8ZM19 9H14V4H5V20H19V9ZM8 7H11V9H8V7ZM8 11H16V13H8V11ZM8 15H16V17H8V15Z"></path></svg>
                            </div>

                            <div class="pt-3 sm:pt-5">
                                <h2 class="text-xl font-semibold text-black dark:text-white">Privacy Policy</h2>

                                <p class="mt-4 text-sm/relaxed">
                                    Introduction <br/>

                                    Welcome to StoryTime_demo. This is a demonstration application that does not collect or store any personal data from its users.
                                    <br/>

                                    Cookies <br/>

                                    We use only essential cookies to ensure the proper functioning of our application. These cookies enable basic features such as page navigation and access to secure areas of the site. By using our website, you consent to the use of these cookies.
                                    <br/>
                                    Changes to This Privacy Policy <br/>

                                    We may update this Privacy Policy from time to time. Any changes will be posted on this page with an updated revision date.
                                    <br/>
                                    Last Updated:December 16, 2024
                                </p>
                            </div>
                        </div>



                        <div class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
                            <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M21 8V20.9932C21 21.5501 20.5552 22 20.0066 22H3.9934C3.44495 22 3 21.556 3 21.0082V2.9918C3 2.45531 3.4487 2 4.00221 2H14.9968L21 8ZM19 9H14V4H5V20H19V9ZM8 7H11V9H8V7ZM8 11H16V13H8V11ZM8 15H16V17H8V15Z"></path></svg>
                            </div>

                            <div class="pt-3 sm:pt-5">
                                <h2 class="text-xl font-semibold text-black dark:text-white">Cookies</h2>

                                <p class="mt-4 text-sm/relaxed">
                                    Our website uses cookies to enhance your browsing experience. Cookies are small text files stored on your device that help us understand how you interact with our site. We use only essential cookies to ensure the website functions correctly. By continuing to use our website, you consent to the use of cookies in accordance with our Privacy Policy and Cookie Policy. You can manage your cookie preferences through your browser settings at any time.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#FF2D20] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]">
                            <div class="flex size-24 shrink-0 items-center justify-center rounded-full bg-[#FF2D20]/10 sm:size-16">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M21 8V20.9932C21 21.5501 20.5552 22 20.0066 22H3.9934C3.44495 22 3 21.556 3 21.0082V2.9918C3 2.45531 3.4487 2 4.00221 2H14.9968L21 8ZM19 9H14V4H5V20H19V9ZM8 7H11V9H8V7ZM8 11H16V13H8V11ZM8 15H16V17H8V15Z"></path></svg>
                            </div>

                            <div class="pt-3 sm:pt-5">
                                <h2 class="text-xl font-semibold text-black dark:text-white">Contact</h2>

                                <p class="mt-4 text-sm/relaxed">
                                    If you have any questions or concerns about this Privacy Policy, please contact us:
                                    <br>By Email: [oskarkobza@gmail.com]
                                    <br>By Phone: [+48 789 175 519]
                                </p>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="pt-16 pb-10 text-center text-sm text-black dark:text-white/70">
                    ©Oskar Kobza
                </footer>
            </div>
        </div>
    </div>
</template>
