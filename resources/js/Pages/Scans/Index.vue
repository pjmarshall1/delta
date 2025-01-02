<script setup>
import {useWindowSize} from "@vueuse/core";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";

import {RiAddLine, RiCheckboxCircleFill} from "vue-remix-icons";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/Card.vue";
import ImportScansModal from "@/Components/Modals/ImportScansModal.vue";
import Pagination from "@/Components/Pagination.vue";
import DataTable from "@/Components/DataTable.vue";

const {height} = useWindowSize();

const props = defineProps({
    scans: {
        type: Object,
        required: true,
    }
});

const selectedScans = ref([]);
const showImportModal = ref(false);

const handleScanSelected = (scanId) => {
    router.get(route('scans.show', {scan: scanId}));
}

const handleSortChanged = (sort) => {
    console.log(sort);
}

const columns = [
    {label: 'Date', field: 'date', type: 'date'},
    {label: 'Symbol', field: 'symbol', strong: true},
    {label: 'Price', field: 'price', type: 'currency'},
    {label: 'Gap %', field: 'gap_percent', type: 'percent', sentiment: true},
    {label: 'Float', field: 'float', type: 'scaledNumber'},
    {label: 'Short Int.', field: 'short_interest', type: 'scaledNumber'},
    {label: 'Alerts', field: 'alerts_count'},
    {label: 'Reviewed', field: 'reviewed'},
];

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
                <div class="w-full overflow-hidden">
                    <div class="w-full h-full overflow-y-auto" style="scrollbar-gutter: unset">
                        <div class="inline-block w-full align-middle">
                            <div class="relative">
                                <DataTable :columns="columns"
                                           :rows="props.scans.data"
                                           @rowSelected="handleScanSelected">
                                    <template #alerts_count="{data}">
                                        <div class="w-full flex items-center justify-center space-x-1">
                                            <span class="text-blue-700 text-xs font-medium">{{ data.p_count }}</span>
                                            <span class="-mt-0.5 text-xs text-gray-700">•</span>
                                            <span class="text-green-700 text-xs font-medium">{{ data.m_count }}</span>
                                            <span class="-mt-0.5 text-xs text-gray-700">•</span>
                                            <span class="text-red-700 text-xs font-medium">{{ data.a_count }}</span>
                                        </div>
                                    </template>
                                    <template #reviewed="{data}">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <RiCheckboxCircleFill
                                                :class="[data ? 'text-green-600' : 'text-gray-500', 'size-5']"/>
                                        </div>
                                    </template>
                                </DataTable>
                            </div>
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

<style scoped>

</style>
