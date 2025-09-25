<script setup lang="ts">
const props = defineProps<{
    taskDescription: string,
    tasks: any[],
    searchQuery: any,
    placeholder?: string
}>()

const emit = defineEmits(['update:taskDescription'])

const onInput = (event: Event) => {
    emit('update:taskDescription', (event.target as HTMLTextAreaElement).value)
}
</script>

<template>
    <textarea
        :value="props.taskDescription"
        @input="onInput"
        :rows="props.tasks?.length > 0 ? 1 : 3"
        class="flex-1 resize-none border-none focus:ring-0 outline-none text-base bg-transparent w-full"
        :placeholder="props.placeholder || 'Type your message...'"
    ></textarea>
</template>