<script setup lang="ts">
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'

type Props = {
  isOpen: boolean,
  title?: string,
  confirmButtonText?: string,
}

const props = defineProps<Props>()
const emit = defineEmits(['close', 'confirm'])

const handleCloseModal = () => {
  emit('close')
}

const handleConfirmModal = () => {
  emit('confirm')
}
</script>

<template>
  <AlertDialog :open="props.isOpen">
    <AlertDialogContent>
      <AlertDialogHeader>
        <AlertDialogTitle>{{ props.title }}</AlertDialogTitle>
      </AlertDialogHeader>
      <AlertDialogFooter class="flex items-center justify-between">
        <AlertDialogCancel @click="handleCloseModal">Cancel</AlertDialogCancel>
        <AlertDialogAction @click="handleConfirmModal" class="bg-red-500 text-white">{{ props.confirmButtonText || 'Delete' }}</AlertDialogAction>
      </AlertDialogFooter>
    </AlertDialogContent>
  </AlertDialog>
</template>
