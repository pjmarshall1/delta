<script setup>
import {useWindowSize} from "@vueuse/core";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";
import {useScanColumns} from "@/Composables/useScanColumns.js";

import {RiAddLine, RiCheckboxCircleFill, RiSettings4Line} from "vue-remix-icons";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/Card.vue";
import ImportScansModal from "@/Components/Modals/ImportScansModal.vue";
import Pagination from "@/Components/Pagination.vue";
import DataTable from "@/Components/DataTable.vue";
import ColumnsModal from "@/Components/Modals/ColumnsModal.vue";

const {height} = useWindowSize();
const {visibleColumns} = useScanColumns();

const props = defineProps({
    scans: {
        type: Object,
        required: true,
    }
});

const selectedScans = ref([]);
const showColumnsModal = ref(false);
const showImportModal = ref(false);

const handleScanSelected = (scanId) => {
    router.get(route('scans.show', {scan: scanId}));
}

const handleSortChanged = (sort) => {
    console.log(sort);
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
                <div class="w-full overflow-hidden">
                    <div class="h-12 w-full px-5 flex items-center justify-end">
                        <button
                            class="size-8 flex items-center justify-center text-gray-500 hover:text-gray-700 border border-gray-300 rounded hover:bg-gray-200"
                            @click="showColumnsModal = true">
                            <RiSettings4Line class="size-5"/>
                        </button>
                    </div>
                    <div class="w-full h-full overflow-y-auto" style="scrollbar-gutter: unset">
                        <div class="inline-block w-full align-middle">
                            <div class="relative">
                                <DataTable :columns="visibleColumns"
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

        <ColumnsModal :columns="visibleColumns"
                      :show="showColumnsModal"
                      @onCancel="showColumnsModal = false"
                      @onUpdate="showColumnsModal = false"/>

        <ImportScansModal :show="showImportModal"
                          @onCancel="showImportModal = false"
                          @onUpload="showImportModal = false"/>

    </AuthenticatedLayout>

</template>

<style scoped>

</style>
