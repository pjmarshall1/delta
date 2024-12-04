<script setup>
import {useWindowSize} from "@vueuse/core";
import {router} from "@inertiajs/vue3";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/Card.vue";
import Pagination from "@/Components/Pagination.vue";

import {RiAddLine} from "vue-remix-icons";
import {ref} from "vue";
import ImportScansModal from "@/Components/Modals/ImportScansModal.vue";

const {height} = useWindowSize();

const props = defineProps({
    scans: {
        type: Object,
        required: true,
    }
});

const showImportModal = ref(false);

const handleScanSelected = (scan) => {
    router.get(route('scans.show', {scan: scan.id}));
}

</script>

<template>
    <AuthenticatedLayout title="Scan Log">
        <template v-slot:header>
            <button
                class="relative flex justify-between items-center space-x-1 px-4 py-2 bg-indigo-500 rounded-md uppercase font-semibold text-xs text-white shadow-sm hover:bg-indigo-600 disabled:bg-gray-100 transition ease-in-out duration-150 ring-1 ring-inset ring-indigo-600 focus:outline-none"
                type="button"
                @click="showImportModal=true">
                <RiAddLine aria-hidden="true" class="-ml-1 h-5 w-5"/>
                <span class="w-full pl-1 pr-2">Import Scans</span>
            </button>
        </template>

        <div :style="`height: ${height - 104}px`" class="w-full">
            <Card class="h-full flex flex-col">
                <div class="w-full overflow-y-auto">
                    <div class="inline-block w-full align-middle">
                        <div class="relative">
                            <table class="w-full table-fixed">
                                <thead>
                                <tr>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">Date
                                    </th>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">
                                        Symbol
                                    </th>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">
                                        Price
                                    </th>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">
                                        Gap
                                    </th>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">
                                        Float
                                    </th>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">
                                        Short Int.
                                    </th>
                                    <th class="sticky top-0 px-3 py-5 bg-gray-200 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                                        scope="col">
                                        Alerts
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="scan in props.scans.data" :key="scan.id"
                                    class="hover:bg-gray-100 cursor-pointer">

                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-medium uppercase tracking-wide text-gray-500">
                                            {{ dayjs(scan.date).format('MM/DD/YYYY') }}
                                        </span>
                                    </td>
                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-extrabold uppercase tracking-wide text-gray-500">
                                            {{ scan.symbol }}
                                        </span>
                                    </td>
                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-medium uppercase tracking-wide text-gray-500">
                                            {{ numeral(scan.price / 10000).format('$0,0[.][0000]') }}
                                        </span>
                                    </td>
                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-medium uppercase tracking-wide text-gray-500">
                                            {{ numeral(scan.gap_percent / 10000).format('0[.][00]%') }}
                                        </span>
                                    </td>
                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-medium tracking-wide text-gray-500">
                                            {{ numeral(scan.float).format('0,0[.][00]a') }}
                                        </span>
                                    </td>
                                    <td class="h-12" @click="handleScanSelected(scan)">
                                        <span
                                            class="block w-full text-center text-xs font-medium tracking-wide text-gray-500">
                                            {{ numeral(scan.short_interest).format('0,0[.][00]a') }}
                                        </span>
                                    </td>
                                    <td class="h-12" @click="$emit('onScanSelected', scan)">
                                        <span
                                            class="block w-full text-center text-xs font-medium uppercase tracking-wide text-gray-500">
                                            <span class="text-blue-500 font-bold">{{ scan.p_count }}</span>
                                            <span>&nbsp;&middot;&nbsp;</span>
                                            <span class="text-green-500 font-bold">{{ scan.m_count }}</span>
                                            <span>&nbsp;&middot;&nbsp;</span>
                                            <span class="text-red-500 font-bold">{{ scan.a_count }}</span>
                                        </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <Pagination :links="props.scans.links"
                            :meta="props.scans.meta"
                            :only="['scans']"
                            class="p-3 border-t"/>
            </Card>
        </div>
    </AuthenticatedLayout>

    <ImportScansModal :show="showImportModal"
                      @onCancel="showImportModal = false"
                      @onUpload="showImportModal = false"/>
</template>
