<script setup>
import {Link} from '@inertiajs/vue3';
import {RiArrowLeftSLine, RiArrowRightSLine} from "vue-remix-icons";

const props = defineProps({
    meta: {
        type: Object,
        required: true,
    },
    links: {
        type: Object,
        default: [],
    },
    only: {
        type: Array,
        default: [],
    },
    preserveScroll: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="flex items-center justify-between">
        <div class="flex flex-1 items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">
                    Showing
                    {{ ' ' }}
                    <span class="text-gray-700 font-semibold">{{ meta.from }}</span>
                    {{ ' ' }}
                    to
                    {{ ' ' }}
                    <span class="text-gray-700 font-semibold">{{ meta.to }}</span>
                    {{ ' ' }}
                    of
                    {{ ' ' }}
                    <span class="text-gray-700 font-semibold">{{ meta.total }}</span>
                    {{ ' ' }}
                    results
                </p>
            </div>
            <div>
                <nav aria-label="Pagination" class="isolate inline-flex -space-x-px bg-white rounded-md shadow-sm">
                    <template v-for="(link, index) in meta.links">
                        <Link :class="{
                                'text-gray-400' : link.url === null,
                                'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': link.active,
                                'text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-offset-0 hover:bg-gray-50 hover:text-gray-700': ! link.active && link.url
                              }" :disabled="link.url === null" :href="link.url ?? ''" :only="only"
                              :preserve-scroll="preserveScroll"
                              as="button"
                              class="relative inline-flex items-center px-4 py-2 first-of-type:rounded-l-md last-of-type:rounded-r-md text-sm font-semibold ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0">
                                <span v-if="index === 0">
                                    <RiArrowLeftSLine aria-hidden="true" class="h-5 w-5"/>
                                </span>
                            <span v-else-if="index === meta.links.length - 1">
                                    <RiArrowRightSLine aria-hidden="true" class="h-5 w-5"/>
                                </span>
                            <span v-else>
                                    {{ link.label }}
                                </span>
                        </Link>
                    </template>
                </nav>
            </div>
        </div>
    </div>
</template>
