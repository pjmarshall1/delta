<script setup>
import {onMounted, onUnmounted, ref} from 'vue';

const props = defineProps({
    dropdownClasses: {
        type: String,
        default: 'mt-2 w-48',
    },
    contentClasses: {
        type: String,
        default: 'py-1 bg-white',
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
    <div>
        <div v-on:click="open = !open">
            <slot name="trigger" :active="open"/>
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
            <div v-show="open" v-on:click="open = false"
                 class="absolute z-50 rounded-md shadow-lg"
                :class="dropdownClasses" style="display: none"
            >
                <div class="rounded-md ring-1 ring-black ring-opacity-5" :class="contentClasses">
                    <slot name="content"/>
                </div>
            </div>
        </Transition>
    </div>
</template>
