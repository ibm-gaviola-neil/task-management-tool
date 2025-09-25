
<template>
  <Login/>
</template>

<script setup lang='ts'>
  import Login from './login/index.vue'
  definePageMeta({
    middleware: ['guest']
  })
</script>