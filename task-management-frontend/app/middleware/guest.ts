import { useAuthStore } from "~/stores/auth/useAuthStore"

export default defineNuxtRouteMiddleware(async (to, from) => {
    const auth = useAuthStore();
  
    if(!auth.loggedIn && !auth.userData){
      try {
        await auth.fetchUser()
      } catch (error) {
        auth.logout()
      }
    }

    if(auth.loggedIn){
      return navigateTo('/dashboard')
    }
})