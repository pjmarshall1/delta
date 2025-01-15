<script setup>
import {ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import {useResizeObserver} from "@vueuse/core";
import {useScanColumns} from "@/Composables/useScanColumns.js";
import {useScanFilterStore} from "@/Stores/ScanFilterStore.js";

import {RiCheckboxCircleFill, RiSettings4Line} from "vue-remix-icons";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/Card.vue";
import ColumnsModal from "@/Components/Modals/ColumnsModal.vue";
import DataTable from "@/Components/DataTable.vue";
import FilterBar from "@/Pages/Scans/partials/FilterBar.vue";
import Pagination from "@/Components/Pagination.vue";

const {visibleColumns} = useScanColumns();
const scanFilterStore = useScanFilterStore();

const cardElement = ref(null);
const cardHeight = ref(0);
useResizeObserver(cardElement, (entries) => {
    cardHeight.value = entries[0].contentRect.height;
});

const props = defineProps({
    filterOptions: {
        type: Object,
        required: true,
    },
    scans: {
        type: Object,
        required: true,
    },
});

const showColumnsModal = ref(false);
const sort = ref(scanFilterStore.sorting);

const handleScanSelected = (scanId) => {
    router.get(route('scans.show', {scan: scanId}));
}

const handleSortChanged = (sort) => {
    scanFilterStore.setSorting(sort);
}

watch(() => props.filterOptions, (options) => {
    Object.keys(options).forEach(key => {
        scanFilterStore.options.forEach(option => {
            if (option.key === key) {
                option.value = options[key];
            }
        });
    });

}, {immediate: true});

</script>

<template>
    <AuthenticatedLayout title="Scan Log">
        <template v-slot:toolbar>
            <FilterBar/>
        </template>

        <Card ref="cardElement" class="h-full w-full flex flex-col">
            <div class="w-full overflow-hidden">
                <div class="h-12 w-full px-5 flex items-center justify-end">
                    <button
                        class="size-8 flex items-center justify-center text-gray-500 hover:text-gray-700 border border-gray-300 rounded hover:bg-gray-200"
                        @click="showColumnsModal = true">
                        <RiSettings4Line class="size-5"/>
                    </button>
                </div>
                <div :style="`height: ${cardHeight - 109}px`" class="w-full overflow-y-auto">
                    <DataTable v-if="visibleColumns?.length > 0"
                               :columns="visibleColumns"
                               :rows="scans.data"
                               :sort="sort"
                               :sortable="true"
                               @rowSelected="handleScanSelected"
                               @sortChanged="handleSortChanged">

                        <template #alerts_count="{data}">
                            <div class="w-full flex items-center justify-center space-x-1">
                                <span class="text-blue-700 text-xs font-medium">{{ data.pre_market }}</span>
                                <span class="-mt-0.5 text-xs text-gray-700">•</span>
                                <span class="text-green-700 text-xs font-medium">{{ data.open_market }}</span>
                                <span class="-mt-0.5 text-xs text-gray-700">•</span>
                                <span class="text-red-700 text-xs font-medium">{{ data.after_market }}</span>
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

            <Pagination :links="props.scans.links"
                        :meta="props.scans.meta"
                        :only="['scans']"
                        class="p-3 border-t"/>
        </Card>

        <ColumnsModal :columns="visibleColumns"
                      :show="showColumnsModal"
                      @onCancel="showColumnsModal = false"
                      @onUpdate="showColumnsModal = false"/>

    </AuthenticatedLayout>

</template>

<style scoped>

</style>
