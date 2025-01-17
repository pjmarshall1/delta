<script setup>

const props = defineProps({
    items: {
        type: Array,
        required: true,
    }
});
</script>

<template>
    <div class="flex flex-col">
        <template v-for="(item, index) in items" :key="index">
            <div class="px-3 py-2 flex items-center justify-between">
                <div class="text-smaller font-medium tracking-wide text-gray-600">{{ item.label }}</div>
                <div :class="[item?.color ?? 'text-gray-800' ,'text-smaller font-semibold tracking-wide']">
                    {{ item.value }}
                </div>
            </div>
        </template>
    </div>
</template>
