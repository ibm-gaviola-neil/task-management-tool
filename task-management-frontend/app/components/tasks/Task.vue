<script setup lang="ts">
import { Button } from '~/components/ui/button';
import { Checkbox } from '~/components/ui/checkbox';
import { Trash2 } from 'lucide-vue-next';
import type { Task } from '~/types/task';
import { ref, watch } from 'vue';

type Props = {
  tasks: Task[];
  updateStatus: (taskId: number) => void;
  deleteTask: (taskId: number) => void;
};
const props = defineProps<Props>();

const statusBools = ref<boolean[]>(props.tasks.map(task => task.status === 1));

watch(
  () => props.tasks,
  (newTasks) => {
    statusBools.value = newTasks.map(task => task.status === 1);
  },
  { deep: true }
);

function handleStatusChange(index: number, id: number) {
  if (props.tasks[index] && typeof statusBools.value[index] !== 'undefined') {
    props.tasks[index]!.status = statusBools.value[index] ? 1 : 0;
    props.updateStatus(id);
  }
}
</script>

<template>
  <div v-for="(task, index) in props.tasks" :key="task.id" class="group mb-4 shadow-xs outline-1 p-2 bg-white rounded-lg flex items-center justify-between space-x-4 w-full hover:bg-gray-100">
    <div class="flex items-center">
      <Checkbox
        v-model="statusBools[index]"
        class="rounded-full h-5 w-5 mr-2 cursor-pointer"
        @update:model-value="() => handleStatusChange(index, task.id)"
      />
      <span :class="[task.status === 1 ? 'line-through' : '']" >
        {{ task.task_description }}
      </span>
    </div>
    <Button @click="props.deleteTask(task.id)" class="bg-white hover:bg-gray-200 cursor-pointer text-gray-800 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-opacity duration-200"><Trash2/></Button>
  </div>
</template>