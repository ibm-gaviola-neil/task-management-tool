<script setup lang="ts">
import type { Task } from '~/types/task';
import { Check } from "lucide-vue-next"

  type props = {
    task : Task,
    handleStatusChange: (taskId: number) => void,
  } 

  const props = defineProps<props>();
</script>

<template>
  <div>
    <label class="inline-flex items-center cursor-pointer">
      <input
        type="checkbox" 
        v-model="task.status"
        :true-value="1"
        :false-value="0"
        @update:model-value="() => handleStatusChange(task.id)"
        class="sr-only peer" 
      />
      <div class="w-6 h-6 mr-2 rounded-full border-1 border-gray-400 flex items-center justify-center 
        peer-checked:border-black-500 peer-checked:bg-black transition-colors duration-200">
        <Check class="size-3.5 text-white" />
      </div>
    </label>
  </div>
</template>