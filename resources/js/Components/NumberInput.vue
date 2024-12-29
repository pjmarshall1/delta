<script setup>
import {onMounted, ref, watch} from 'vue';

const model = defineModel(0);

const props = defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
    max: {
        type: [String, Number],
        default: null,
    },
    min: {
        type: [String, Number],
        default: null,
    },
    precision: {
        type: [String, Number],
        default: null,
    },
})

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});

const sanitizeInput = (value) => {
    return parseInt(value.toString().replace(/[^0-9.,]/g, ''));
};

const handleKeyDown = (e) => {
    const acceptedKeys = ['Backspace', 'Delete', 'Enter', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Tab', '.', '-'];

    // If precision is 0, prevent decimal input
    // if (e.key === '.' && props.precision === 0) {
    //     e.preventDefault();
    //     return;
    // }

    // Prevent additional decimals if one already exists
    if (e.key === '.' && !model.value?.toString().includes('.')) {
        e.preventDefault();
        return;
    }

    // Prevent entering anything other than valid keys
    if (!/^[\d-]+$/.test(e.key) && !acceptedKeys.includes(e.key)) {
        e.preventDefault();
        return;
    }

    // Handle maximum precision after a decimal point
    if (
        model.value?.toString().includes('.') &&
        model.value?.toString().split('.')[1].length >= props.precision &&
        !acceptedKeys.includes(e.key)
    ) {
        e.preventDefault();
        return;
    }

    // Prevent entering leading zero unless followed by a decimal point
    if (e.key === '0' && !model.value && !e.target.value.includes('.')) {
        e.preventDefault();
        return;
    }

    // Handle specific keys
    switch (e.key) {
        case 'ArrowUp':
            model.value++;
            break;
        case 'ArrowDown':
            model.value--;
            break;
        case 'Enter':
        case 'Tab':
            if (model.value === '0') {
                model.value = null;
            } else {
                model.value = Number(model.value);
            }
            break;
    }
};


watch(model, (newValue, oldValue) => {
    if (props.max && newValue >= props.max) {
        model.value = props.max;
    } else if (props.min && newValue <= props.min) {
        model.value = props.min;
    }

    if (newValue !== oldValue && newValue) {
        model.value = sanitizeInput(newValue);
    }
});
</script>

<template>
    <div
        :class="{'bg-gray-200 text-gray-500' : disabled}"
        class="p-1 flex items-center rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-1 focus:ring-inset focus:ring-indigo-600">
        <input
            ref="input" v-model="model"
            :disabled="disabled"
            aria-describedby="number"
            class="px-1 block w-full border-0 text-xs text-gray-900 text-left leading-6 placeholder:text-gray-400 bg-transparent outline-0 focus:ring-0 disabled:bg-gray-200 disabled:text-gray-500"
            placeholder="0"
            style="height: 24px;"
            @keydown="handleKeyDown"/>
        <div class="w-5 pr-0.5 flex flex-col">
            <button :disabled="(props.max && model >= props.max) || disabled"
                    class="h-1/2 flex items-center justify-center text-gray-700 hover:text-gray-900"
                    @click="model++">
                <svg class="size-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path
                        d="M11.9999 10.8284L7.0502 15.7782L5.63599 14.364L11.9999 8L18.3639 14.364L16.9497 15.7782L11.9999 10.8284Z"></path>
                </svg>
            </button>
            <button :disabled="(props.min && model <= props.min) || disabled"
                    class="h-1/2 flex items-center justify-center text-gray-700 hover:text-gray-900"
                    @click="model--">
                <svg class="size-3" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 0h24v24H0z" fill="none"></path>
                    <path
                        d="M11.9999 13.1714L16.9497 8.22168L18.3639 9.63589L11.9999 15.9999L5.63599 9.63589L7.0502 8.22168L11.9999 13.1714Z"></path>
                </svg>
            </button>
        </div>
    </div>
</template>
