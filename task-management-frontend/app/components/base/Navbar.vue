<script setup lang="ts">
import { Search } from "lucide-vue-next"
import { useTastStore } from "~/stores/tasks/useTaskStore"

const searchQuery = ref('')
const taskStore = useTastStore()

const handleSearch = (event : Event) => {
    const target = event.target as HTMLInputElement
    taskStore.setSeearchQuery(target.value)
}
</script>

<template>
    <div class="w-full h-[60px] shadow-md flex mb-2">
        <div class="w-full h-full flex items-center justify-start px-3">
            <BaseImage src="/assets/images/logo.png" class="w-[50px] h-[40px]"/>
        </div>
        <div class="w-full h-full flex items-center justify-center relative">
            <UiInput
                placeholder="Search tasks..."
                class="rounded-full pl-10"
                v-model="searchQuery"
                @input="handleSearch"
            />
            <span class="absolute start-0 inset-y-0 flex items-center justify-center px-3">
                <Search class="size-6 text-muted-foreground" />
            </span>
        </div>
        <div class="w-full h-full flex items-center justify-end px-4 space-x-4">
            <BaseDropDown />
        </div>
    </div>
</template>