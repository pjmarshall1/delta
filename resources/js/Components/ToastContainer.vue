<script setup>

import {useToast} from '@/Composables/useToast.js';
import Toast from "@/Components/Toast.vue";
import {watch} from "vue";

const {toasts, removeToast} = useToast();

const props = defineProps({
    position: {
        type: String,
        default: 'top-right',
    },
    timeout: {
        type: Number,
        default: 5000,
    },
    dismissible: {
        type: Boolean,
        default: true,
    },
});
// Toast positions for filtering
const positions = ['top-right', 'top', 'top-left', 'bottom-right', 'bottom', 'bottom-left'];

// Compute the position class based on the position prop
const positionClass = (position) => ({
    top: 'top-4 left-1/2 transform -translate-x-1/2',
    'top-left': 'top-4 left-4',
    'top-right': 'top-4 right-4',
    bottom: 'bottom-4 left-1/2 transform -translate-x-1/2',
    'bottom-left': 'bottom-4 left-4',
    'bottom-right': 'bottom-4 right-4',
}[position]);

// Compute the transition name based on the position
const transitionName = (position) => ({
    top: 'toast-top',
    'top-left': 'toast-top-left',
    'top-right': 'toast-top-right',
    bottom: 'toast-bottom',
    'bottom-left': 'toast-bottom-left',
    'bottom-right': 'toast-bottom-right',
}[position]);

watch(toasts, (newToasts) => {
    newToasts.forEach((toast) => {
        toast.options.timeout ??= props.timeout;
        toast.options.dismissible ??= props.dismissible;
        toast.options.position ??= props.position;
    });
}, {deep: true});

</script>

<template>
    <template v-for="position in positions" :key="position">
        <div :class="positionClass(position || props.position)" class="fixed z-50 space-y-4 flex flex-col">
            <TransitionGroup :name="transitionName(position)" tag="div"
                             :class="position.startsWith('bottom') ? 'flex flex-col-reverse space-y-reverse' : ''">
                <template v-for="toast in toasts" :key="toast.id">
                    <Toast v-show="toast.options.position === position" :id="toast.id" :type="toast.type"
                           :message="toast.message" :options="toast.options"
                           v-on:onRemoveToast="removeToast(id)"/>
                </template>
            </TransitionGroup>
        </div>
    </template>
</template>

<style scoped>
.toast-top-left-enter-active,
.toast-top-left-leave-active {
    transition: transform 0.3s, opacity 0.3s;
}

.toast-top-left-enter-from {
    transform: translateX(-100%);
    opacity: 0;
}

.toast-top-left-leave-to {
    transform: translateX(-100%);
    opacity: 0;
}

.toast-top-left-move {
    transition: transform 0.3s;
}

.toast-top-enter-active,
.toast-top-leave-active {
    transition: transform 0.3s, opacity 0.3s;
}

.toast-top-enter-from {
    transform: translateY(-100%);
    opacity: 0;
}

.toast-top-leave-to {
    transform: translateY(-200%);
    opacity: 0;
}

.toast-top-move {
    transition: transform 0.3s;
}

.toast-top-right-enter-active,
.toast-top-right-leave-active {
    transition: transform 0.3s, opacity 0.3s;
}

.toast-top-right-enter-from {
    transform: translateX(200%);
    opacity: 0;
}

.toast-top-right-leave-to {
    transform: translateX(100%);
    opacity: 0;
}

.toast-top-right-move {
    transition: transform 0.3s;
}

.toast-bottom-left-enter-active,
.toast-bottom-left-leave-active {
    transition: transform 0.3s, opacity 0.3s;
}

.toast-bottom-left-enter-from {
    transform: translateX(-100%);
    opacity: 0;
}

.toast-bottom-left-leave-to {
    transform: translateX(-100%);
    opacity: 0;
}

.toast-bottom-left-move {
    transition: transform 0.3s;
}

.toast-bottom-enter-active,
.toast-bottom-leave-active {
    transition: transform 0.3s, opacity 0.3s;
}

.toast-bottom-enter-from {
    transform: translateY(100%);
    opacity: 0;
}

.toast-bottom-leave-to {
    transform: translateY(200%);
    opacity: 0;
}

.toast-bottom-move {
    transition: transform 0.3s;
}

.toast-bottom-right-enter-active,
.toast-bottom-right-leave-active {
    transition: transform 0.3s, opacity 0.3s;
}

.toast-bottom-right-enter-from {
    transform: translateX(100%);
    opacity: 0;
}

.toast-bottom-right-leave-to {
    transform: translateX(100%);
    opacity: 0;
}

.toast-bottom-right-move {
    transition: transform 0.3s;
}
</style>

