<script setup>
import {watch} from 'vue';
import {router} from "@inertiajs/vue3";
import {useScanFilterStore} from "@/Stores/ScanFilterStore.js";

import DateRangePicker from "@/Components/DatePicker/DateRangePicker.vue";
import FilterPicker from "@/Components/FilterPicker.vue";

const scanFilterStore = useScanFilterStore();

const handleDateRangeSelected = (range) => {
    scanFilterStore.setDateRange(range);
}

const handleDateRangeCleared = () => {
    scanFilterStore.resetDateRange();
}

const handleApplyFilters = (value) => {
    scanFilterStore.setAdvancedFilters(value);
}

const handleClearFilters = () => {
    scanFilterStore.resetAdvancedFilters();
}
watch(() => scanFilterStore.filters, () => {
    router.get(route('scans.index'), scanFilterStore.queryParams);
}, {deep: true});

</script>

<template>
    <FilterPicker :filters="scanFilterStore.advancedFilters"
                  :options="scanFilterStore.options"
                  @onApply="handleApplyFilters"
                  @onClear="handleClearFilters"/>

    <DateRangePicker :endDate="scanFilterStore.dateRange.endDate"
                     :maxSelectableDate="dayjs().format('YYYY-MM-DD')"
                     :startDate="scanFilterStore.dateRange.startDate"
                     placeholder="Date Range"
                     @rangeCleared="handleDateRangeCleared"
                     @rangeSelected="handleDateRangeSelected"/>
</template>

<style scoped>

</style>
