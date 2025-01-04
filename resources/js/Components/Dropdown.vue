<script setup>
import {onMounted, onUnmounted, ref} from 'vue';

const props = defineProps({
    autoClose: {
        type: Boolean,
        default: true,
    },
    dropdownClasses: {
        type: String,
        default: 'mt-2 w-48',
    },
    contentClasses: {
        type: String,
        default: 'bg-white',
    },
});

const closeOnEscape = (e) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const open = ref(false);
</script>

<template>
    <div class="relative">
        <div v-on:click="open = !open">
            <slot :active="open" name="trigger"/>
        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div v-show="open" class="fixed inset-0 z-40" v-on:click="open = false"
        ></div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div v-show="open" :class="dropdownClasses"
                 class="absolute z-50 rounded-md shadow-lg"
                 style="display: none" v-on:click="open = !autoClose"
            >
                <div :class="contentClasses" class="rounded-md ring-1 ring-black ring-opacity-5 overflow-hidden">
                    <slot name="content"/>
                </div>
            </div>
        </Transition>
    </div>
</template>
