<script setup lang="ts">
import { ArrowUp } from 'lucide-vue-next'

type Props = {
    tasks: any,
    storeTask: () => void,
    taskStore: any,
    taskDescription: string,
    searchQuery: any
}
const props = defineProps<Props>()
const emit = defineEmits(['update:taskDescription'])
</script>

<template>
    <FormFieldsForm :handle-submit="props.storeTask">
        <BaseTitleHeader v-if="props.tasks?.length < 1" title="What do you have in mind?"/>
        <div class="flex items-end bg-white rounded-xl border border-gray-200 p-4 shadow-sm w-full">
            <FormFieldsTextArea
                :tasks="props.tasks"
                :search-query="props.searchQuery"
                :task-description="props.taskDescription"
                @update:taskDescription="emit('update:taskDescription', $event)"
            />
            <button
                :disabled="!props.taskDescription.trim()"
                class="ml-2 p-2 rounded-lg bg-black transition-colors duration-200 flex items-center cursor-pointer hover:bg-gray-800 disabled:cursor-not-allowed disabled:bg-gray-300 disabled:hover:bg-gray-300"
            >
                <ArrowUp class="w-5 h-5 text-white transition-colors duration-200" />
            </button>
        </div>
        <span
            v-if="props.taskStore.errors?.task_description?.[0]"
            class="italic text-red-500 text-sm"
        >
            {{ props.taskStore.errors.task_description[0] }}
        </span>
    </FormFieldsForm>
</template>