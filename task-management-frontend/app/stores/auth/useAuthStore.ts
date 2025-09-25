import { defineStore } from 'pinia'
import type { User } from '~/types/user'
import { useNuxtApp } from '#imports'
import type { AxiosInstance } from 'axios'
import type { LoginValidationError } from '~/types/validationErrors'

interface AuthState {
  userData: User | null,
  errors: LoginValidationError | null,
  loggedIn: boolean
  loading: boolean,
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    userData: null,
    errors: null,
    loggedIn: false,
    loading: false,
  }),
  actions: {
    async fetchUser() {
      this.loading = true
      const { $axios } = useNuxtApp()
      const axios = $axios as AxiosInstance

      try {
        const { data } = await axios.get('/api/auth/user')
        this.userData = data.user
        this.loggedIn = true
      } catch(error : any) {
        this.userData = null
        this.loggedIn = false
      } finally {
        this.loading = false
      }
    },
    async login(email: string, password: string) {
      const { $axios, $fetchCsrfCookie } = useNuxtApp()
      const axios = $axios as AxiosInstance
      const fetchCsrfCookie = $fetchCsrfCookie as () => Promise<void>

      this.loading = true
      this.errors = null
      try {
        await fetchCsrfCookie()
        await axios.post('/api/login', { email, password })
        await this.fetchUser()
        return navigateTo('/dashboard')
      } catch (error : any) {
        this.userData = null
        this.loggedIn = false
        
        if(error.response && error.response.status === 422){
          this.errors = error.response.data.errors
        }

        if(error.response && error.response.status === 401){
          this.errors = error.response.data
        }

      } finally {
        this.loading = false
      }
    },
    async logout() {
      const { $axios, $fetchCsrfCookie  } = useNuxtApp()
      const fetchCsrfCookie = $fetchCsrfCookie as () => Promise<void>
      const axios = $axios as AxiosInstance

      this.loading = true 
      try {
        await fetchCsrfCookie()
        await axios.post('/api/auth/logout')
        return navigateTo('/login')
      } catch (error) {
      } finally {
        this.userData = null
        this.loggedIn = false
        this.loading = false
      }
    }
  }
})