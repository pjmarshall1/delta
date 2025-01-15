<script setup>
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import {onMounted, onUnmounted, ref} from "vue";
import {useLocalStorage, useWindowSize} from "@vueuse/core";
import {useToast} from '@/Composables/useToast.js';
import {useScanFilterStore} from "@/Stores/ScanFilterStore.js";

import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import ImportScansModal from "@/Components/Modals/ImportScansModal.vue";
import Popover from "@/Components/Popover.vue";
import Separator from "@/Components/Separator.vue";
import ToastContainer from "@/Components/ToastContainer.vue";

import {
    RiAddLine,
    RiArrowLeftSLine,
    RiDashboardLine,
    RiLogoutCircleLine,
    RiQrScan2Line,
    RiUser3Line
} from "vue-remix-icons";

const {height} = useWindowSize();
const scanFilterStore = useScanFilterStore();
const sidebarOpen = useLocalStorage('sidebarOpen', true);
const {toast} = useToast();

const props = defineProps({
    title: String,
});

const showImportScanModal = ref(false);

const navigation = [
    {
        name: 'Dashboard',
        href: route('dashboard.index'),
        icon: RiDashboardLine,
        current: route().current('dashboard.index')
    },
    {
        name: 'Scans',
        href: route('scans.index', scanFilterStore.queryParams),
        icon: RiQrScan2Line,
        current: route().current('scans.index')
    }
];

const userNavigation = [
    {name: 'Profile', href: route('profile.edit'), icon: RiUser3Line, current: route().current('profile.edit')},
]

const logout = () => {
    router.post(route('logout'));
};

onMounted(() => {
    Echo.private(`user.${usePage().props.auth.user.id}`)
        .listen('SendToast', (event) => {
            if (event.type && event.message) {
                toast(event.message, event.type);
            }
        });
});

onUnmounted(() => {
    Echo.leave(`toast.${usePage().props.auth.user.id}`);
});

</script>

<template>
    <Head :title="props.title"/>
    <div class="min-h-screen bg-gray-100">
        <div :class="sidebarOpen ? 'w-64' : 'w-18'"
             class="fixed inset-0 z-10 flex flex-col duration-500 overflow-visible">

            <button
                class="h-8 w-8 absolute top-0 right-0 mt-4 -mr-4 z-20 flex items-center justify-center rounded-full border border-gray-200 shadow-md bg-white text-gray-800"
                v-on:click="sidebarOpen = !sidebarOpen;">
                <RiArrowLeftSLine :class="sidebarOpen ? 'rotate-0 -ml-0.5' : 'rotate-180'"
                                  class="h-5 w-5 stroke-3 duration-500"/>
            </button>

            <div class="flex flex-grow flex-col gap-y-5 overflow-y-auto bg-gray-900 duration-500">
                <div class="h-16 w-full flex shrink-0 items-center justify-center">
                    <ApplicationLogo/>
                </div>

                <nav class="flex flex-1 flex-col overflow-hidden">
                    <ul class="px-4 flex flex-1 flex-col gap-y-2.5" role="list">
                        <li>
                            <button
                                class="h-10 w-full p-2 flex items-center space-x-3 rounded-md text-gray-400 hover:text-white text-sm font-semibold  hover:bg-indigo-400/25"
                                type="button"
                                @click="showImportScanModal=true">
                                <RiAddLine aria-hidden="true" class="flex-shrink-0 size-6"/>

                                <span :class="sidebarOpen ? 'opacity-100' : 'opacity-0'"
                                      class="truncate overflow-hidden transition-opacity duration-500">Import Scans</span>
                            </button>

                        </li>
                        <Separator class="m-y-1 w-full" orientation="horizontal"/>
                        <li v-for="item in navigation" :key="item.name">
                            <Link
                                :class="[item.current ? 'bg-indigo-800 text-white' : 'text-gray-400 hover:bg-indigo-400/25 hover:text-white']"
                                :href="item.href"
                                class="p-2 flex items-center space-x-3 rounded-md text-sm font-semibold">
                                <component :is="item.icon" aria-hidden="true" class="size-6 shrink-0"/>
                                <span :class="sidebarOpen ? 'opacity-100' : 'opacity-0'"
                                      class="transition-opacity duration-500">{{ item.name }}</span>
                            </Link>
                        </li>
                    </ul>
                </nav>

                <div class="mb-4 mt-auto px-4">
                    <Popover :contain="false" popoverClasses="bottom-12 mb-3 w-56 6py-1 bg-gray-700">
                        <template #trigger="{ active }">
                            <div
                                :class="{'bg-indigo-400/25': active}"
                                class="h-10 p-1 flex items-center rounded-md space-x-2 hover:bg-indigo-400/25 cursor-pointer">
                                <img :alt="usePage().props.auth.user.name"
                                     :src="usePage().props.auth.user.profile_photo_url"
                                     class="h-8 w-8 rounded-full bg-gray-50"/>
                                <div :class="sidebarOpen ? 'opacity-100' : 'opacity-0'"
                                     class="flex flex-col truncate transition-opacity duration-500">
                        <span aria-hidden="true" class="text-left text-sm font-semibold leading-2 text-gray-400">
                            {{ usePage().props.auth.user.name }}
                        </span>
                                    <span aria-hidden="true" class="text-left text-xs leading-2 text-gray-400 truncate">
                            {{ usePage().props.auth.user.email }}
                        </span>
                                </div>
                            </div>
                        </template>
                        <template #content>
                            <ul class="py-2">
                                <li v-for="item in userNavigation" :key="item.name" class="px-2">
                                    <Link :href="item.href"
                                          class="w-full p-2 flex items-center space-x-3 text-gray-200 text-sm leading-6 font-semibold rounded-md hover:text-gray-700 hover:bg-gray-200">
                                        <component :is="item.icon" aria-hidden="true" class="size-5 shrink-0"/>
                                        <span>{{ item.name }}</span>
                                    </Link>
                                </li>
                                <li class="px-2">
                                    <button
                                        class="w-full p-2 flex items-center space-x-3 text-gray-200 text-sm leading-6 font-semibold rounded-md hover:text-gray-700 hover:bg-gray-200"
                                        v-on:click="logout">
                                        <RiLogoutCircleLine aria-hidden="true" class="size-5 shrink-0"/>
                                        <span class="ml-2">Sign out</span>
                                    </button>
                                </li>
                            </ul>
                        </template>
                    </Popover>
                </div>
            </div>
        </div>

        <div :class="sidebarOpen ? 'ml-64' : 'ml-18'"
             class="fixed inset-0 overflow-y-auto duration-500">

            <header class="sticky top-0 z-20 h-16 w-full px-6 flex items-center justify-between bg-white shadow">
                <h1 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ props.title }}
                </h1>

                <template v-if="$slots.toolbar">
                    <div class="h-12 flex items-center justify-end space-x-2">
                        <slot name="toolbar"/>
                    </div>
                </template>
            </header>

            <main :style="`height: ${height - 64}px`" class="w-full p-5 overflow-hidden">
                <slot/>
            </main>
        </div>
    </div>

    <ToastContainer :timeout="2500" position="top-right"/>

    <ImportScansModal :show="showImportScanModal"
                      @onCancel="showImportScanModal = false"
                      @onUpload="showImportScanModal = false"/>
</template>

