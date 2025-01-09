<script setup>
import {computed, nextTick, onMounted, reactive, watch} from 'vue';

import {RiArrowDropDownLine, RiCalendar2Fill, RiCloseLine} from "vue-remix-icons";

import Dropdown from "@/Components/Popover.vue";
import CalendarView from "@/Components/DatePicker/CalendarView.vue";
import QuickSelectView from "@/Components/DatePicker/QuickSelectView.vue";

const emit = defineEmits(['rangeCleared', 'rangeSelected']);

const props = defineProps({
    endDate: {
        type: String,
        default: null,
        validator: (value) => !value || dayjs(value, 'YYYY-MM-DD', true).isValid(),
    },
    minSelectableDate: {
        type: String,
        default: null,
        validator: (value) => !value || dayjs(value, 'YYYY-MM-DD', true).isValid(),
    },
    maxSelectableDate: {
        type: String,
        default: null,
        validator: (value) => !value || dayjs(value, 'YYYY-MM-DD', true).isValid(),
    },
    placeholder: {
        type: String,
        default: 'Date range',
    },
    startDate: {
        type: String,
        default: null,
        validator: (value) => !value || dayjs(value, 'YYYY-MM-DD', true).isValid(),
    }
})

const state = reactive({
    endDate: props.endDate,
    fromVisibleDate: dayjs().subtract(1, 'month'),
    hoveredDate: null,
    startDate: props.startDate,
    toVisibleDate: dayjs(),

});

const triggerLabel = computed(() => {
    if (state.startDate && state.endDate) {
        if (dayjs(state.startDate).isSame(state.endDate, 'day')) {
            return dayjs(state.startDate).format('MMM D, YYYY');
        }

        return `${dayjs(state.startDate).format('MMM D, YYYY')} - ${dayjs(state.endDate).format('MMM D, YYYY')}`;
    }

    return props.placeholder;
});

const handleQuickSelect = (range) => {
    state.startDate = range.startDate;
    state.endDate = range.endDate;
}

onMounted(() => {
    nextTick(() => {
        if (props.startDate && props.endDate) {
            if (dayjs(props.startDate).isSame(props.endDate, 'month')) {
                state.fromVisibleDate = dayjs(props.startDate);
                state.toVisibleDate = dayjs(props.endDate).add(1, 'month');
            } else {
                state.fromVisibleDate = dayjs(props.startDate);
                state.toVisibleDate = dayjs(props.endDate);
            }
        }

        if (props.startDate) {
            state.startDate = dayjs(props.startDate);
        }

        if (props.endDate) {
            state.endDate = dayjs(props.endDate);
        }
    });

    nextTick(() => {
        watch(() => state.endDate, (value) => {
            if (value && state.startDate && dayjs(value).isSameOrAfter(state.endDate)) {
                emit('rangeSelected', {startDate: state.startDate, endDate: value});
            }
        });
    });
})

</script>

<template>
    <Dropdown :autoClose="false"
              popoverClasses="right-0 mt-1 bg-white border border-gray-200 rounded-md shadow overflow-hidden">
        <template #trigger="{ active }">
            <div
                :class="active ? 'bg-gray-100 ' : 'bg-white'"
                class="h-9 min-w-40 relative flex justify-between items-center space-x-2 px-4 py-2 rounded-md shadow-sm hover:bg-gray-100 cursor-pointer transition ease-in-out duration-150 ring-1 ring-inset ring-gray-300 focus:outline-none"
            >
                <RiCalendar2Fill class="size-5 text-indigo-500"/>

                <span :class="triggerLabel ? 'text-gray-700' : 'text-gray-500'"
                      class="flex-1 w-full font-semibold text-left  text-xs">{{ triggerLabel }}</span>

                <button v-show="state.startDate && state.endDate"
                        class="size-5 flex items-center justify-center rounded-full text-gray-900 hover:bg-gray-300"
                        @click.stop="$emit('rangeCleared')">
                    <RiCloseLine aria-hidden="true" class="size-3.5"/>
                </button>

                <span class="flex items-center pointer-events-none">
                    <RiArrowDropDownLine
                        :class="[active ? 'rotate-180' : 'rotate-0','size-5 text-gray-500 transition-all duration-300']"/>
                </span>
            </div>
        </template>

        <template #content="active">
            <div class="flex space-x-px bg-gray-200">
                <div class="space-y-px bg-gray-200">
                    <div class="w-full px-4 py-3 bg-white flex items-center justify-between">
                        <div class="w-full text-center text-sm font-medium text-gray-900">
                            {{ state.startDate ? dayjs(state.startDate).format('MMMM DD, YYYY') : 'Start Date' }}
                        </div>

                        <svg class="-mt-0.5 h-4 w-10 text-gray-700" fill="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path
                                d="M1.99974 13.0001L1.9996 11.0002L18.1715 11.0002L14.2218 7.05044L15.636 5.63623L22 12.0002L15.636 18.3642L14.2218 16.9499L18.1716 13.0002L1.99974 13.0001Z"></path>
                        </svg>

                        <div class="w-full text-center text-sm font-medium text-gray-900">
                            {{ state.endDate ? dayjs(state.endDate).format('MMMM DD, YYYY') : 'End Date' }}
                        </div>
                    </div>
                    <div class="flex space-x-px">
                        <CalendarView v-model="state"
                                      :active="active.active"
                                      :maxSelectableDate="props.maxSelectableDate"
                                      :minSelectableDate="props.minSelectableDate"
                                      rangeFrom/>
                        <CalendarView v-model="state"
                                      :active="active.active"
                                      :maxSelectableDate="props.maxSelectableDate"
                                      :minSelectableDate="props.minSelectableDate"
                                      rangeTo/>
                    </div>

                </div>
                <QuickSelectView :state="state"
                                 @optionSelected="handleQuickSelect"/>
            </div>
        </template>
    </Dropdown>
</template>

<style scoped>

</style>
