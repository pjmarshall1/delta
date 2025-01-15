<script setup>
import {onMounted, onUnmounted, ref, watch} from 'vue';

const emit = defineEmits(['onClose', 'onOpen']);

const props = defineProps({
    autoClose: {
        type: Boolean,
        default: true,
    },
    contain: {
        type: Boolean,
        default: true,
    },
    popoverClasses: {
        type: String,
        default: 'mt-1 bg-white border border-gray-200 rounded-md shadow',
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

watch(() => open.value, (value) => {
    if (value) {
        emit('onOpen');
    } else {
        emit('onClose');
    }
});
</script>

<template>
    <div :class="props.contain && 'relative'">
        <div v-on:click="open = !open">
            <slot :active="open" name="trigger"/>
        </div>

        <!-- Full Screen Dropdown Overlay -->
        <div v-show="open" class="fixed inset-0 z-10" v-on:click="open = false"
        ></div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div v-show="open" :class="popoverClasses"
                 class="absolute z-20 rounded-md shadow-lg"
                 v-on:click="open = !autoClose"
            >
                <slot :active="open" name="content"/>
            </div>
        </Transition>
    </div>
</template>
