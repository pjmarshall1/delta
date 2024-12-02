<script setup>

const props = defineProps({
    title: String,
});

</script>

<template>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <template v-if="title">
            <div class="h-12 w-full px-3 flex flex-none items-center bg-white border-b border-gray-200">
                <span class="text-sm text-gray-900 font-semibold">{{ title }}</span>
            </div>
        </template>

        <slot/>
    </div>
</template>
