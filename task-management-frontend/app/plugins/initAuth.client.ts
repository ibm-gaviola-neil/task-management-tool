import { useAuthStore } from '~/stores/auth/useAuthStore'

export default defineNuxtPlugin(async () => {
  const auth = useAuthStore();
  
  if(!auth.loggedIn && !auth.userData){
    try {
      await auth.fetchUser()
    } catch (error) {
      auth.logout()
    }
  }
});