<script setup>
import {computed, nextTick, onMounted, ref} from 'vue';

import {RiArrowDropDownLine, RiCloseLine, RiFilter2Fill} from "vue-remix-icons";
import Popover from "@/Components/Popover.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Listbox from "@/Components/Listbox.vue";

const emit = defineEmits(['onApply', 'onCancel', 'onClear']);

const props = defineProps({
    filters: {
        type: Object,
        required: true,
    },
    options: {
        type: Array,
        required: true,
    },
});

const pendingFilters = ref({});
const popoverRef = ref();
const popoverButtonRef = ref();

const appliedFiltersCount = computed(() => {
    return Object.keys(props.filters).filter(key => props.filters[key] !== null).length;
});
const popoverButtonText = computed(() => {
    return appliedFiltersCount.value > 0 ? (appliedFiltersCount.value === 1 ? '1 filter' : `${appliedFiltersCount.value} filters`) : 'Filters';
});


const handleClear = () => {
    emit('onClear');
};

const handleApply = () => {
    emit('onApply', pendingFilters.value);
};

const handleCancel = () => {
    emit('onCancel');

    nextTick(() => {
        popoverButtonRef.value.click();
        pendingFilters.value = JSON.parse(JSON.stringify(props.filters));
    });
};

onMounted(() => {
    pendingFilters.value = JSON.parse(JSON.stringify(props.filters));
});

</script>

<template>
    <Popover ref="popoverRef" :autoClose="false"
             popoverClasses="right-0 mt-1 w-[520px] bg-white border border-gray-200 rounded-md shadow">
        <template #trigger="{ active }">
            <div ref="popoverButtonRef" :class="active ? 'bg-gray-100 ' : 'bg-white'"
                 class="h-9 min-w-40 relative flex justify-between items-center space-x-2 px-4 py-2 rounded-md shadow-sm hover:bg-gray-100 cursor-pointer transition ease-in-out duration-150 ring-1 ring-inset ring-gray-300 focus:outline-none"
            >
                <RiFilter2Fill class="size-4 text-indigo-500"/>

                <span :class="popoverButtonText ? 'text-gray-700' : 'text-gray-500'"
                      class="flex-1 w-full font-semibold text-left text-xs truncate">{{ popoverButtonText }}</span>

                <button v-show="appliedFiltersCount > 0"
                        class="size-5 flex items-center justify-center rounded-full text-gray-900 hover:bg-gray-300"
                        @click.stop="handleClear">
                    <RiCloseLine aria-hidden="true" class="size-3.5"/>
                </button>

                <span class="flex items-center pointer-events-none">
                    <RiArrowDropDownLine
                        :class="[active ? 'rotate-180' : 'rotate-0','size-5 text-gray-500 transition-all duration-300']"/>
                </span>
            </div>
        </template>

        <template #content>
            <div class="p-4 text-sm text-gray-700 font-medium">
                <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                    <template v-for="option in options" :key="option.key">
                        <Listbox v-model="pendingFilters[option.key]"
                                 :autocomplete="option.autocomplete"
                                 :options="option.value"
                                 :placeholder="option.name"
                                 clearable/>
                    </template>
                </div>
            </div>
            <div class="p-4 flex items-center justify-end space-x-2">
                <SecondaryButton class="text-sm text-gray-700 font-medium"
                                 @click.stop="handleCancel">Cancel
                </SecondaryButton>
                <PrimaryButton class="text-sm text-indigo-500 font-medium"
                               @click.stop="handleApply">Apply
                </PrimaryButton>
            </div>
        </template>
    </Popover>
</template>
