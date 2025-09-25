<script setup lang="ts">
import type { HtmlHTMLAttributes } from 'vue';
import { cn } from '~/lib/utils';

    type Props = {
        src: string;
        alt?: string;
        class?: HtmlHTMLAttributes["class"];
    };

    const props = defineProps<Props>();
</script>

<template>
    <img :src="src" :alt="props.alt || 'image'" :class="cn('rounded-full cursor-pointer ', props.class)" />
</template>