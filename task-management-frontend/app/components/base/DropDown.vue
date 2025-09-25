<script setup lang="ts">
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import { useAuthStore } from '~/stores/auth/useAuthStore';

const auth = useAuthStore()

const handleLogout = () => {
  auth.logout();
}
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger>
        <BaseImage src="/assets/images/user.png" class="w-[70px] h-[70px]"/>
    </DropdownMenuTrigger>
    <DropdownMenuContent class="mr-4">
      <DropdownMenuItem class="cursor-pointer" @click="handleLogout">Logout</DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu>
</template>