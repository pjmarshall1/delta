<script setup>
import {watch} from 'vue';
import {router} from "@inertiajs/vue3";
import {useScanFilterStore} from "@/Stores/ScanFilterStore.js";

import DateRangePicker from "@/Components/DatePicker/DateRangePicker.vue";

const scanFilterStore = useScanFilterStore();
const filters = scanFilterStore.filters;

const handleDateRangeSelected = (range) => {
    filters.startDate = range.startDate;
    filters.endDate = range.endDate;
}

const handleDateRangeCleared = () => {
    filters.startDate = null;
    filters.endDate = null;
}

watch(() => scanFilterStore, () => {
    router.get(route('scans.index'), scanFilterStore.queryParams);
}, {deep: true});

</script>

<template>
    <DateRangePicker :endDate="filters.endDate" :maxSelectableDate="dayjs().format('YYYY-MM-DD')"
                     :startDate="filters.startDate"
                     placeholder="Date Range"
                     @rangeCleared="handleDateRangeCleared"
                     @rangeSelected="handleDateRangeSelected"/>
</template>

<style scoped>

</style>
