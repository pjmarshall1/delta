<script setup>
import {computed} from 'vue'

const props = defineProps({
    orientation: {
        type: String,
        default: 'vertical',
    },
})

const classes = computed(() => {
    return {
        'h-full w-px bg-gradient-to-b': props.orientation === 'vertical',
        'h-px w-full bg-gradient-to-r': props.orientation === 'horizontal',
    }
})

</script>

<template>
    <div :class="classes" class="from-transparent via-gray-400 to-transparent"/>
</template>
