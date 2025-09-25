<script setup lang="ts">
type Props = {
    handleSubmit : (event: Event) => void
}

const props = defineProps<Props>()
</script>

<template>
    <form @submit.prevent="props.handleSubmit" class="w-full flex items-center justify-center flex-col space-y-6">
        <slot />
    </form>
</template>