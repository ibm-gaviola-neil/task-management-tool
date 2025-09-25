<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { useVModel } from "@vueuse/core"
import { cn } from '~/lib/utils';

const props = defineProps<{
    modelValue?: string | number
    defaultValue?: string | number
    label: string,
    placeholder?: string,
    type?: string,
    error?: string,
    class?: HTMLAttributes["class"]
}>()

const emits = defineEmits<{
  (e: "update:modelValue", payload: string | number): void
}>()

const modelValue = useVModel(props, "modelValue", emits, {
  passive: true,
  defaultValue: props.defaultValue,
})
</script>

<template>
    <div class="flex flex-col space-y-1.5">
        <UiLabel>{{ props.label }}</UiLabel>
        <input
            v-model="modelValue"
            :value="modelValue"
            :type="props.type || 'text'"
            :placeholder="props.placeholder || ''"
            data-slot="input"
            :class="cn(
            'file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground dark:bg-input/30 border-input flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
            'focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]',
            'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
            props.class,
            )"
        >
        <span v-if="props.error" class="italic text-red-500 text-sm">
        {{ props.error }}
        </span>
    </div>
</template>