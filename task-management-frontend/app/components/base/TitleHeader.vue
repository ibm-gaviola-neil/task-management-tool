<script setup lang="ts">
type Props = {
    title : string
}

const props = defineProps<Props>()
</script>

<template>
    <h1 class="text-center mb-3 text-3xl font-bold">
        {{ props.title }}
    </h1>
</template>