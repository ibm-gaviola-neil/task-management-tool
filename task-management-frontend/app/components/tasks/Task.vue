
<script setup lang="ts">
import { Button } from '~/components/ui/button';
import { Checkbox } from "~/components/ui/checkbox"
import { Trash2 } from 'lucide-vue-next'
import type { Task } from '~/types/task';

type Props = {
    tasks : Task[],
    updateStatus : (taskId : number) => void,
    deleteTask : (taskId : number) => void,
}
const props = defineProps<Props>()
</script>

<template>
    <div v-for="task in props.tasks" :key="task.id" class="group mb-4 shadow-xs outline-1 p-2 bg-white rounded-lg flex items-center justify-between space-x-4 w-full hover:bg-gray-100">
        <div class="flex items-center">
            <Checkbox
                :v-model="task?.status"
                true-value="1"
                false-value="0"
                class="rounded-full h-5 w-5 mr-2 cursor-pointer"
                @update:model-value="props.updateStatus(task.id)"
            /> 
            <span :class="[task.status === 1 ? 'line-through' : '']" >
                {{ task.task_description }}
            </span>
        </div>
        <Button @click="props.deleteTask(task.id)" class="bg-white hover:bg-gray-200 cursor-pointer text-gray-800 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-opacity duration-200"><Trash2/></Button>
    </div>
</template>
