<script setup lang="ts">
import draggable from 'vuedraggable'
import { Button } from '~/components/ui/button';
import { Trash2 } from 'lucide-vue-next';
import type { Task } from '~/types/task';
import { ref, watch } from 'vue';

type Props = {
  tasks: Task[];
  updateStatus: (taskId: number) => void;
  deleteTask: (taskId: number) => void;
  updateOrder: (tasks: Task[]) => void;
};
const props = defineProps<Props>();
const modalIsOpen = ref(false);
const taskId = ref<number>(0);
const localTasks = ref<Task[]>([...props.tasks]);

watch(
  () => props.tasks,
  (newTasks) => {
    localTasks.value = [...newTasks];
  },
  { deep: true }
);

const handleStatusChange = (id: number) => {
  props.updateStatus(id);
}

const onDragEnd = () => {
  props.updateOrder(localTasks.value);
}

const handleDeleteModal = (id : number | null, status : boolean = true) => {
  modalIsOpen.value = status;
  if(id) {
    taskId.value = id;
  }
}

const handleDeleteTask = () => {
  props.deleteTask(taskId.value);
  handleDeleteModal(null ,false);
}
</script>

<template>
  <draggable
    class="w-full cursor-move"
    item-key="id"
    v-model="localTasks"
    animation="200"
    @end="onDragEnd"
  >
    <template #item="{ element: task }">
      <div :key="task.id" class="group mb-4 shadow-xs outline-1 p-2 bg-white rounded-lg flex items-center justify-between space-x-4 w-full hover:bg-gray-100">
        <div class="flex items-center cursor-move">
          <UiCheckbox 
            :task="task" 
            :handle-status-change="handleStatusChange"
          />
          <span :class="[task.status === 1 ? 'line-through' : '']">
            {{ task.task_description }}
          </span>
        </div>
        <Button 
          @click="handleDeleteModal(task.id)" 
          class="bg-white hover:bg-gray-200 cursor-pointer text-gray-800 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-opacity duration-200"
        >
          <Trash2/>
        </Button>
      </div>
    </template>
  </draggable>

  <BaseModal 
    title="Are you sure you want to delete this task?"
    :is-open="modalIsOpen" 
    @close="() => handleDeleteModal(null ,false)" 
    @confirm="handleDeleteTask"
  />
</template>
