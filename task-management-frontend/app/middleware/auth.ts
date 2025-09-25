import { useAuthStore } from "~/stores/auth/useAuthStore"

export default defineNuxtRouteMiddleware (async (from, to) => {
    const auth = useAuthStore();

    if (import.meta.server) return
  
    if(!auth.loggedIn && !auth.userData){
        try {
            await auth.fetchUser()
        } catch (error) {
            auth.logout()
        }
    }

    if(!auth.loggedIn){
        return navigateTo('/')
    }
})