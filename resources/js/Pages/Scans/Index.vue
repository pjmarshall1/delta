<script setup>
import {router} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import {useResizeObserver} from "@vueuse/core";
import {useScanColumns} from "@/Composables/useScanColumns.js";

import {RiCheckboxCircleFill, RiSettings4Line} from "vue-remix-icons";

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/Card.vue";
import Pagination from "@/Components/Pagination.vue";
import DataTable from "@/Components/DataTable.vue";
import ColumnsModal from "@/Components/Modals/ColumnsModal.vue";
import FilterBar from "@/Pages/Scans/partials/FilterBar.vue";

const {visibleColumns} = useScanColumns();

const cardElement = ref(null);
const cardHeight = ref(0);
useResizeObserver(cardElement, (entries) => {
    cardHeight.value = entries[0].contentRect.height;
});

const props = defineProps({
    scans: {
        type: Object,
        required: true,
    },
});

const data = computed(() => {
    return props.scans.data.map(scan => {
        return {
            ...scan,
            date: dayjs.utc(scan.timestamp).tz(dayjs.tz.guess()).format('MM-DD-YYYY HH:mm:ss'),
        };
    });
});

const showColumnsModal = ref(false);
const sort = ref({
    field: '',
    direction: '',
});

const handleScanSelected = (scanId) => {
    router.get(route('scans.show', {scan: scanId}));
}

const handleSortChanged = (sort) => {
    const urlParams = new URLSearchParams(window.location.search);
    const sortParam = urlParams.get('sort');

    if (sort.field === 'alerts_count') {
        sort.field = sortParam ? sortParam.replace(/^-/, '') : 'aftermarket';

        if (sort.direction === 'asc') {
            sort.field = sort.field === 'premarket' ? 'market' : sort.field === 'market' ? 'aftermarket' : 'premarket';
        }
    }

    const param = sort.direction === 'asc' ? sort.field : `-${sort.field}`;
    router.get(route('scans.index', {sort: param}));
}

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
                               :rows="data"
                               :sort="sort"
                               :sortable="true"
                               @rowSelected="handleScanSelected"
                               @sortChanged="handleSortChanged">
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
