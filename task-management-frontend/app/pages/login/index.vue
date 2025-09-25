<script setup lang='ts'>
  import { Button } from "@/components/ui/button"
  import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
  } from "@/components/ui/card"
  import { useAuthStore } from '~/stores/auth/useAuthStore'

  const auth = useAuthStore()
  const email = ref('')
  const password = ref('')

  async function login() {
    await auth.login(email.value, password.value)
  }

  definePageMeta({
    middleware: ['guest']
  })
</script>

<template>
  <NuxtLayout>
    <FormFieldsForm :handle-submit="login">
      <div>
        <BaseImage src="/assets/images/logo.png" class="h-20"/>
      </div>
      <Card class="w-[450px] h-[450px] px-5">
        <CardHeader class="text-center">
          <CardTitle class="text-3xl font-bold">Sign In</CardTitle>
          <CardDescription>Login to continue using this app.</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="grid items-center w-full gap-4 mb-6">
            <FormFieldsInput
              v-model="email"
              label="Email"
              placeholder="Enter your email"
              :error="auth.errors?.email?.[0]"
            />
          </div>
            <div class="grid items-center w-full gap-4">
              <FormFieldsInput
                v-model="password"
                label="Password"
                placeholder="Enter your password"
                type="password"
                :error="auth.errors?.password?.[0]"
              />
            </div>
            <span v-if="auth.errors?.message" class="italic text-red-500 text-sm">
              {{ auth.errors.message }}
            </span>
        </CardContent>
        <CardFooter class="w-full">
          <Button class="w-full cursor-pointer" :disable="auth.loading">{{ auth.loading ? "Loading..." : "Login" }}</Button>
        </CardFooter>
      </Card>
    </FormFieldsForm>
  </NuxtLayout>
</template>