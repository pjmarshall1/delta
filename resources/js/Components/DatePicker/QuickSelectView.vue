<script setup>

const emit = defineEmits(['optionSelected']);

const props = defineProps({
    state: Object,
});

const options = [
    {label: 'Today', value: 'today'},
    {label: 'Yesterday', value: 'yesterday'},
    {label: 'This Week', value: 'this_week'},
    {label: 'Last Week', value: 'last_week'},
    {label: 'This Month', value: 'this_month'},
    {label: 'Last Month', value: 'last_month'},
    {label: 'Last 30 Days', value: 'last_30_days'},
    {label: 'This Quarter', value: 'this_quarter'},
    {label: 'Year To Date', value: 'year_to_date'},
];

const isMatching = (value) => {
    const startDate = props.state.startDate;
    const endDate = props.state.endDate;

    if (value === 'today') {
        return dayjs().isSame(startDate, 'day') && dayjs().isSame(endDate, 'day');
    }

    if (value === 'yesterday') {
        return dayjs().subtract(1, 'day').isSame(startDate, 'day') && dayjs().subtract(1, 'day').isSame(endDate, 'day');
    }

    if (value === 'this_week') {
        return dayjs().startOf('week').isSame(startDate, 'day') && dayjs().isSame(endDate, 'day');
    }

    if (value === 'last_week') {
        return dayjs().subtract(1, 'week').startOf('week').isSame(startDate, 'day') && dayjs().subtract(1, 'week').endOf('week').isSame(endDate, 'day');
    }

    if (value === 'this_month') {
        return dayjs().startOf('month').isSame(startDate, 'day') && dayjs().isSame(endDate, 'day');
    }

    if (value === 'last_month') {
        return dayjs().subtract(1, 'month').startOf('month').isSame(startDate, 'day') && dayjs().subtract(1, 'month').endOf('month').isSame(endDate, 'day');
    }

    if (value === 'last_30_days') {
        return dayjs().subtract(30, 'days').isSame(startDate, 'day') && dayjs().isSame(endDate, 'day');
    }

    if (value === 'this_quarter') {
        return dayjs().startOf('quarter').isSame(startDate, 'day') && dayjs().isSame(endDate, 'day');
    }

    if (value === 'year_to_date') {
        return dayjs().startOf('year').isSame(startDate, 'day') && dayjs().isSame(endDate, 'day');
    }

    return false;
};

const handleQuickSelect = (value) => {
    const range = {
        startDate: null,
        endDate: null,
    };
    switch (value) {
        case 'today':
            range.startDate = dayjs().format('YYYY-MM-DD');
            range.endDate = dayjs().format('YYYY-MM-DD');
            break;
        case 'yesterday':
            range.startDate = dayjs().subtract(1, 'day').format('YYYY-MM-DD');
            range.endDate = dayjs().subtract(1, 'day').format('YYYY-MM-DD');
            break;
        case 'this_week':
            range.startDate = dayjs().startOf('week').format('YYYY-MM-DD');
            if (dayjs().endOf('week').isAfter(dayjs())) {
                range.endDate = dayjs().format('YYYY-MM-DD');
            } else {
                range.endDate = dayjs().endOf('week').format('YYYY-MM-DD');
            }
            break;
        case 'last_week':
            range.startDate = dayjs().subtract(1, 'week').startOf('week').format('YYYY-MM-DD');
            range.endDate = dayjs().subtract(1, 'week').endOf('week').format('YYYY-MM-DD');
            break;
        case 'this_month':
            range.startDate = dayjs().startOf('month').format('YYYY-MM-DD');
            if (dayjs().endOf('month').isAfter(dayjs())) {
                range.endDate = dayjs().format('YYYY-MM-DD');
            } else {
                range.endDate = dayjs().endOf('month').format('YYYY-MM-DD');
            }
            break;
        case 'last_month':
            range.startDate = dayjs().subtract(1, 'month').startOf('month').format('YYYY-MM-DD');
            range.endDate = dayjs().subtract(1, 'month').endOf('month').format('YYYY-MM-DD');
            break;
        case 'last_30_days':
            range.startDate = dayjs().subtract(30, 'days').format('YYYY-MM-DD');
            range.endDate = dayjs().format('YYYY-MM-DD');
            break;
        case 'this_quarter':
            range.startDate = dayjs().startOf('quarter').format('YYYY-MM-DD');
            if (dayjs().endOf('quarter').isAfter(dayjs())) {
                range.endDate = dayjs().format('YYYY-MM-DD');
            } else {
                range.endDate = dayjs().endOf('quarter').format('YYYY-MM-DD');
            }
            break;
        case 'year_to_date':
            range.startDate = dayjs().startOf('year').format('YYYY-MM-DD');
            range.endDate = dayjs().format('YYYY-MM-DD');
            break;
    }

    emit('optionSelected', range);
}

</script>

<template>
    <div class="isolate w-36 py-1 flex flex-col bg-white">
        <template v-for="option in options" :key="option.value">
            <button :class="isMatching(option.value) ? 'text-gray-900 font-bold' : 'text-gray-700 font-medium'"
                    class="relative w-full px-4 py-2 inline-flex items-center text-xs bg-white hover:bg-indigo-100 focus:z-10"
                    type="button"
                    @click="handleQuickSelect(option.value)">
                {{ option.label }}
            </button>
        </template>
    </div>
</template>

<style scoped>

</style>
