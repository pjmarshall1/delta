<script setup>
import {computed, nextTick, ref, watch} from "vue";
import {RiArrowDropDownLine, RiCheckLine, RiCloseLine} from "vue-remix-icons";
import Popover from "@/Components/Popover.vue";

const model = defineModel();

const props = defineProps({
    autocomplete: {type: Boolean, default: false},
    clearable: {type: Boolean, default: false},
    multiSelect: {type: Boolean, default: false},
    options: {type: Array, default: () => []},
    placeholder: {type: String, default: "Select..."},
});

const popoverButtonRef = ref(null);
const inputRef = ref(null);
const contentRef = ref(null);
const search = ref(null);
const activeIndex = ref(-1);
const filteredOptions = ref(null);

const popoverButtonText = computed(() => {
    if (props.multiSelect) {
        return model.value.length
            ? model.value.map(value => props.options.find(option => option.value === value).name).join(', ')
            : props.placeholder;
    }
    return props.autocomplete ? search.value : props.options.find(option => option.value === model.value)?.name || props.placeholder;
});

const isClearable = computed(() => props.clearable && (props.multiSelect ? model.value.length : model.value));

const isSelected = option => props.multiSelect ? model.value.includes(option.value) : model.value === option.value;

const handleSelect = option => {
    if (props.multiSelect) {
        model.value = model.value.includes(option.value)
            ? model.value.filter(item => item !== option.value)
            : [...model.value, option.value];
    } else {
        model.value = option.value;
    }
};

const handleClear = () => {
    search.value = null;
    model.value = props.multiSelect ? [] : null;
};

const scrollToActiveOption = () => {
    nextTick(() => {
        const activeOption = contentRef.value?.querySelector(".active");
        if (activeOption) {
            contentRef.value.scrollTop = activeOption.offsetTop - contentRef.value.clientHeight / 2 - activeOption.clientHeight / 2;
        }
    });
};

const handlePopoverKeyDown = e => {
    const {key} = e;
    if (key === "ArrowDown" || key === "ArrowUp") {
        e.preventDefault();
        activeIndex.value += key === "ArrowDown" ? 1 : -1;
        scrollToActiveOption();
    } else if (key === "Enter" || key === " " || key === "Space") {
        e.preventDefault();
        const optionToSelect = activeIndex.value >= 0
            ? filteredOptions.value[activeIndex.value]
            : props.options.find(option => option.name.toLowerCase().includes(search.value?.toLowerCase()));
        if (optionToSelect) handleSelect(optionToSelect);
        popoverButtonRef.value.click();
    } else {
        filteredOptions.value = props.options.filter(option => option.name.toLowerCase().includes(search.value?.toLowerCase()));
    }
};

const handlePopoverOpen = () => {
    filteredOptions.value = props.options;
    activeIndex.value = filteredOptions.value.findIndex(option => props.multiSelect ? model.value.includes(option.value) : model.value === option.value);
    nextTick(() => {
        if (props.autocomplete) {
            const selectedOption = contentRef.value.querySelector(".selected");
            if (selectedOption) {
                contentRef.value.scrollTop = selectedOption.offsetTop - contentRef.value.clientHeight / 2 + selectedOption.clientHeight / 2;
            }
        }
    });
};

watch(() => model.value, value => {
    if (props.autocomplete) {
        search.value = value;
    }
}, {immediate: true});

</script>

<template>
    <Popover :autoClose="!props.multiSelect"
             popoverClasses="inset-x-0 mt-1 bg-white border border-gray-200 rounded-md shadow"
             @onOpen="handlePopoverOpen">
        <template #trigger="{ active }">
            <div ref="popoverButtonRef" :class="active ? 'bg-gray-100 ' : 'bg-white'"
                 class="h-9 w-full relative flex justify-between items-center space-x-2 px-4 py-2 rounded-md shadow-sm hover:bg-gray-100 cursor-pointer transition ease-in-out duration-150 ring-1 ring-inset ring-gray-300 focus:outline-none"
                 tabindex="0" @keydown="handlePopoverKeyDown">
                <input v-if="props.autocomplete" ref="inputRef" v-model="search"
                       :class="(props.multiSelect ? model.length : model) ? 'text-gray-500' : 'text-gray-700'"
                       :placeholder="props.placeholder"
                       class="h-8 w-full p-0 text-left text-xs font-semibold bg-transparent border-0 focus:ring-transparent"
                       type="text"/>
                <span v-else :class="popoverButtonText === props.placeholder ? 'text-gray-500' : 'text-gray-700'"
                      class="flex-1 w-full font-semibold text-left text-xs truncate">{{ popoverButtonText }}</span>
                <button v-show="isClearable"
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
            <div ref="contentRef" class="max-h-96 overflow-y-auto">
                <template v-for="(option, index) in filteredOptions" :key="option.value">
                    <div :class="{ 'bg-indigo-100': activeIndex === index, 'selected': isSelected(option) }"
                         class="flex items-center space-x-2 w-full px-4 py-2 text-xs cursor-pointer"
                         @click="handleSelect(option)" @mouseenter="activeIndex = index">
                        <span class="-ml-2 size-4">
                            <RiCheckLine v-if="isSelected(option)" class="size-4 text-indigo-500"/>
                        </span>
                        <div class="w-full flex items-center justify-between">
                            <span :class="model === option ? 'text-900 font-bold' : 'text-500'">{{ option.name }}</span>
                            <span class="ml-auto text-xs text-gray-500">{{ option.description }}</span>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </Popover>
</template>
