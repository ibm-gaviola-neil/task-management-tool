<script setup lang="ts">
import { useTastStore } from '~/stores/tasks/useTaskStore';
import type { Task } from '~/types/task';

const taskStore = useTastStore();
const { tasks, searchQuery } = storeToRefs(taskStore);
const taskDescription = ref('');

const filteredTasks = computed(() => {
  if (!tasks.value) return [];
  if (!searchQuery.value) return tasks.value;
  return tasks.value.filter(task =>
    task.task_description.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const storeTask = async () => {
  await taskStore.storeTask(taskDescription.value);
  taskDescription.value = '';
};

const updateStatus = async (id: number) => {
  await taskStore.update(id);
};

const deleteTask = async (id: number) => {
  await taskStore.deleteTask(id);
};

// ADD THIS: Update the order in the store and backend
const updateOrder = async (reorderedTasks: Task[]) => {
  await taskStore.updateOrder(reorderedTasks);
};

onMounted(async () => {
  await taskStore.fetchTasks();
});

definePageMeta({
  layout: 'dashboard-layout',
  middleware: ['auth'],
});
</script>

<template>
  <NuxtLayout>
    <div
      :class="[
        'w-full h-[calc(100vh-100px)] px-40',
        filteredTasks?.length > 0
          ? 'flex flex-col justify-between'
          : 'flex items-center'
      ]"
    >
      <div v-if="filteredTasks?.length > 0 " class="w-full flex items-center justify-center flex-col">
        <TasksTask
          :tasks="filteredTasks"
          :update-status="updateStatus"
          :delete-task="deleteTask"
          :update-order="updateOrder"
        />
      </div>
      <TasksAddTask
        :tasks="filteredTasks"
        :store-task="storeTask"
        :task-store="taskStore"
        v-model:taskDescription="taskDescription"
        :search-query="searchQuery"
      />
    </div>
  </NuxtLayout>
</template>