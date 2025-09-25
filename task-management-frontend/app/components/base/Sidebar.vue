<script setup lang="ts">
  import { onMounted } from 'vue'
  import { useTastStore } from '~/stores/tasks/useTaskStore'
  import { storeToRefs } from 'pinia'

  const taskStore = useTastStore()
  const { dates } = storeToRefs(taskStore)
  const activeDate = ref('today')

  onMounted(async () => {
      await taskStore.fetchHistoryDates()
  })

  const getTaskBydate = async (date?: string) => {
      activeDate.value = date ?? 'today'
      await taskStore.fetchTasks(date)
  }
</script>

<template>
    <div class="w-[300px] h-full">
      <div
        @click="getTaskBydate()"
        :class="[
          'px-3 py-2 rounded-lg cursor-pointer mb-1',
          activeDate === 'today' ? 'bg-black text-white' : 'bg-white text-black hover:bg-black hover:text-white transition-colors duration-200'
        ]"
      >
        Today
      </div>
      <div v-for="date in dates" :key="date?.label">
        <h1 class="px-3 py-2 text-xs text-gray-400 font-bold">{{ date?.label }}</h1>
        <ul class="mb-5">
          <li
            v-for="exactDate in date?.exact_dates"
            :key="exactDate.date"
            @click="getTaskBydate(exactDate.date)"
            :data-date="exactDate.date"
            :class="[
              'cursor-pointer px-3 py-2 rounded-lg transition-colors duration-200 mb-2',
              activeDate === exactDate.date
                ? 'bg-black text-white'
                : 'bg-white text-black hover:bg-black hover:text-white'
            ]"
          >
            {{ exactDate.date_desc }}
          </li>
        </ul>
      </div>
    </div>
  </template>
  