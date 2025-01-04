<script setup>
import {computed, ref, watch} from 'vue';

import {RiArrowLeftSLine, RiArrowRightSLine} from "vue-remix-icons";

const emit = defineEmits(['rangeSelected']);

const state = defineModel();

const props = defineProps({
    active: {
        type: Boolean,
        default: false,
    },
    minSelectableDate: {
        type: String,
        default: null,
    },
    maxSelectableDate: {
        type: String,
        default: null,
    },
    rangeFrom: {
        type: Boolean,
        default: false,
    },
    rangeTo: {
        type: Boolean,
        default: false,
    },
});

const view = ref('days');
const visibleDate = ref(dayjs().startOf('day'));

const previousButtonDisabled = computed(() => {
    if (props.rangeTo)
        return state.value.fromVisibleDate.isSame(state.value.toVisibleDate.subtract(1, 'month'), 'month');

    return false
});

const nextButtonDisabled = computed(() => {
    if (props.rangeFrom)
        return state.value.toVisibleDate.isSame(state.value.fromVisibleDate.add(1, 'month'), 'month');

    return false
});

const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
const monthsOfYear = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

const years = computed(() => {
    const currentYear = visibleDate.value.year();
    const startDecade = currentYear - (currentYear % 10) - 1;

    return Array.from({length: 12}, (_, i) => startDecade + i);
});

const daysInMonth = computed(() => {
    const start = visibleDate.value.startOf('month').startOf('week');
    const end = visibleDate.value.endOf('month').endOf('week');
    const days = [];

    for (let day = start; day.isSameOrBefore(end, 'day'); day = day.add(1, 'day')) {
        days.push(day);
    }

    return days;
});

const headerLabel = computed(() => {
    if (view.value === 'days') {
        return visibleDate.value.format('MMMM YYYY');
    } else if (view.value === 'months') {
        return visibleDate.value.format('YYYY');
    } else {
        return `${years.value[1]} - ${years.value[10]}`;
    }
});

const dayContainerClass = (day) => {
    const baseClasses = ['size-8 flex items-center justify-center'];
    if (!isDisabled(day)) {
        if (state.value.startDate && state.value.endDate && day.isSame(state.value.startDate, 'day') && day.isSame(state.value.endDate, 'day')) {
            return [...baseClasses, 'bg-white rounded-full'];
        }

        if (state.value.startDate && state.value.endDate && day.isBetween(state.value.startDate, state.value.endDate, 'day')) {
            return [...baseClasses, 'bg-indigo-100'];
        }

        if (state.value.startDate && day.isSame(state.value.startDate, 'day')) {
            return [...baseClasses, 'bg-indigo-100 rounded-l-full'];
        }

        if (state.value.startDate && state.value.hoveredDate && day.isBetween(state.value.startDate, state.value.hoveredDate, 'day')) {
            return [...baseClasses, 'bg-indigo-100'];
        }

        if (state.value.endDate && day.isSame(state.value.endDate, 'day')) {
            return [...baseClasses, 'bg-indigo-100 rounded-r-full'];
        }

        if (state.value.hoveredDate && state.value.startDate && day.isSame(state.value.hoveredDate)) {
            return [...baseClasses, 'bg-indigo-100 rounded-r-full'];
        }

        if (state.value.hoveredDate && state.value.startDate
            && day.isBetween(state.value.startDate, state.value.hoveredDate, 'day') && day.isSame(visibleDate.value, 'month')) {
            return [...baseClasses, 'bg-indigo-100'];
        }
    }

    return baseClasses.push('text-gray-500 pointer-events-none');
};

const dayClass = (day) => {
    const baseClasses = ['relative size-8 flex items-center justify-center font-medium rounded-full disabled:hover:bg-transparent disabled:text-gray-500'];

    if (day.isToday()) {
        baseClasses.push('border border-indigo-500');
    }

    if (!isDisabled(day) && ((state.value.startDate && day.isSame(state.value.startDate, 'day')) || (state.value.endDate && day.isSame(state.value.endDate, 'day')))) {
        return [...baseClasses, 'text-white bg-indigo-500'];
    }

    return [...baseClasses, 'hover:bg-indigo-100'];
}

const isDisabled = (day) => {
    if (props.minSelectableDate && day.isBefore(dayjs(props.minSelectableDate))) {
        return true;
    } else if (props.maxSelectableDate && day.isAfter(dayjs(props.maxSelectableDate))) {
        return true;
    } else if (!day.isSame(visibleDate.value, 'month')) {
        return true;
    }

    return false;
};

const handlePreviousClick = () => {
    if (view.value === 'days') {
        visibleDate.value = dayjs(visibleDate.value).subtract(1, 'month');
    } else if (view.value === 'months') {
        visibleDate.value = dayjs(visibleDate.value).subtract(1, 'year');
    } else {
        visibleDate.value = dayjs(visibleDate.value).year(years.value[1] - 11);
    }
};

const handleNextClick = () => {
    if (view.value === 'days') {
        visibleDate.value = dayjs(visibleDate.value).add(1, 'month');
    } else if (view.value === 'months') {
        visibleDate.value = dayjs(visibleDate.value).add(1, 'year');
    } else {
        visibleDate.value = dayjs(visibleDate.value).add(12, 'year');
    }
};

const handleHeaderClick = () => {
    view.value = view.value === 'days' ? 'months' : (view.value === 'months' ? 'years' : 'days');
};

const handleDayClick = (day) => {
    const formattedDay = day.format('YYYY-MM-DD');

    if (!state.value.startDate) {
        state.value.startDate = formattedDay;
        return;
    }

    if (state.value.startDate && state.value.endDate) {
        state.value.startDate = formattedDay;
        state.value.endDate = null;
        return;
    }

    if (state.value.startDate && day.isBefore(state.value.startDate)) {
        state.value.startDate = formattedDay;
        return;
    }

    if (state.value.startDate && day.isAfter(state.value.startDate)) {
        state.value.endDate = formattedDay;
        emit('rangeSelected');
        return;
    }

    if (!state.value.endDate) {
        state.value.endDate = formattedDay;
        return;
    }

    if (day.isBefore(state.value.startDate)) {
        state.value.startDate = formattedDay;
        return;
    }

    if (day.isAfter(state.value.endDate)) {
        state.value.endDate = formattedDay;
        return;
    }

    return null;
};

const handleMonthClick = (month) => {
    visibleDate.value = visibleDate.value.set('month', monthsOfYear.indexOf(month));
    view.value = 'days';
};

const handleYearClick = (year) => {
    visibleDate.value = visibleDate.value.set('year', year);
    view.value = 'months';
};

const handleDayMouseEnter = (day) => {
    if (state.value.startDate && !state.value.endDate) {
        if (day.isAfter(state.value.startDate)) {
            state.value.hoveredDate = day;
        } else {
            state.value.hoveredDate = null;
        }
    } else {
        state.value.hoveredDate = null;
    }
};

watch(() => props.active, (value) => {
    if (value) {
        if (props.rangeTo) {
            visibleDate.value = dayjs(state.value.toVisibleDate);
        }

        if (props.rangeFrom) {
            visibleDate.value = dayjs(state.value.fromVisibleDate);
        }
    }
});

watch(() => visibleDate.value, (value) => {
    if (props.rangeTo) {
        state.value.toVisibleDate = dayjs(value);
    }

    if (props.rangeFrom) {
        state.value.fromVisibleDate = dayjs(value);
    }
});


</script>

<template>
    <div class="w-[252px] flex flex-col bg-white p-4">
        <div class="p-2 flex items-center justify-between text-gray-900">
            <button
                :disabled="previousButtonDisabled"
                class="-m-1.5 flex flex-none items-center justify-center p-1.5 rounded-full text-gray-900 hover:bg-gray-100 disabled:text-gray-400 disabled:hover:bg-transparent"
                type="button"
                @click="handlePreviousClick">
                <span class="sr-only">Previous month</span>
                <RiArrowLeftSLine aria-hidden="true" class="size-5"/>
            </button>
            <button class="text-sm font-medium text-gray-900" @click="handleHeaderClick">
                {{ headerLabel }}
            </button>
            <button
                :disabled="nextButtonDisabled"
                class="-m-1.5 flex flex-none items-center justify-center p-1.5 rounded-full text-gray-900 hover:bg-gray-100 disabled:text-gray-400 disabled:hover:bg-transparent"
                type="button"
                @click="handleNextClick">
                <span class="sr-only">Next month</span>
                <RiArrowRightSLine aria-hidden="true" class="size-5"/>
            </button>
        </div>
        <div v-show="view === 'days'" class="flex flex-auto flex-col">
            <div
                class="grid grid-cols-7 gap-px text-center text-xs font-semibold leading-6 text-gray-700 lg:flex-none">
                <template v-for="day in daysOfWeek" :key="day">
                    <div class="bg-white py-1">{{ day }}</div>
                </template>
            </div>
            <div class="flex text-xs leading-6 flex-auto">
                <div class="w-full grid grid-cols-7">
                    <template v-for="day in daysInMonth">
                        <div :class="dayContainerClass(day)" @mouseenter="handleDayMouseEnter(day)">
                            <button :class="dayClass(day)" :disabled="isDisabled(day)"
                                    type="button" @click="handleDayClick(day)">
                                <span class="size-8 flex items-center justify-center">
                                    {{ day.format('D') }}
                                </span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <div v-show="view === 'months'" class="flex flex-auto">
            <div class="w-full grid grid-cols-4 gap-px text-center text-xs font-semibold leading-6 text-gray-900">
                <template v-for="month in monthsOfYear">
                    <button class="relative aspect-square flex items-center justify-center rounded hover:bg-gray-100"
                            type="button"
                            @click="handleMonthClick(month)">
                            <span class="size-8 flex items-center justify-center font-medium ">
                                {{ month }}
                            </span>
                    </button>
                </template>
            </div>
        </div>
        <div v-show="view === 'years'" class="flex flex-auto">
            <div class="w-full grid grid-cols-4 gap-px text-xs font-semibold leading-6">
                <template v-for="(year, index) in years">
                    <button
                        :class="index === 0 || index === 11 ? 'text-gray-500' : 'text-gray-900'"
                        class="relative aspect-square flex items-center justify-center rounded hover:bg-gray-100"
                        type="button"
                        @click="handleYearClick(year)">
                    <span class="size-8 flex items-center justify-center font-medium ">
                        {{ year }}
                    </span>
                    </button>
                </template>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
