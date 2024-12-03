<script setup>
import {computed, inject} from 'vue';

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
});

const registerTab = inject('registerTab');
const getActiveTabIndex = inject('getActiveTabIndex');

const tabIndex = registerTab(props.name);
const isActive = computed(() => getActiveTabIndex() === tabIndex);
</script>

<template>
    <div v-show="isActive">
        <slot></slot>
    </div>
</template>
