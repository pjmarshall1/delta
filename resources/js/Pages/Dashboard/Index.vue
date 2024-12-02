<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from "@/Components/Card.vue";
</script>

<template>
    <AuthenticatedLayout title="Dashboard">
        <Card class="p-5">You are logged in!</Card>
    </AuthenticatedLayout>
</template>
