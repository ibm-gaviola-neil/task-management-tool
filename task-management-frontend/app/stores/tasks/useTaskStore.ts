import type { AxiosInstance } from "axios"
import type { Task, TaskState } from "~/types/task"

export const useTastStore = defineStore('task', {
    state : () : TaskState => ({
        tasks: null,
        task : null,
        dates: null,
        isLoading : false,
        errors: null,
        date: null,
        searchQuery : ''
    }),

    actions : {
        setSeearchQuery(query: string | '') {
            this.searchQuery = query
        },

        async fetchTasks (date?: string) {
            this.isLoading = true
            this.date = date || null
            const { $axios } = useNuxtApp()
            const axios = $axios as AxiosInstance

            try {
                const { data } = await axios.get(`/api/tasks${this.date ? `?date=${this.date}`: ''}`)
                this.tasks = data.tasks
            } catch(error : any) {
                this.errors = null
            } finally {
                this.isLoading = false
            }
        },

        async fetchHistoryDates () {
            this.isLoading = true
            const { $axios } = useNuxtApp()
            const axios = $axios as AxiosInstance

            try {
                const { data } = await axios.get('/api/tasks/sidebar-dates')
                this.dates = data.dates
            } catch(error : any) {
                this.errors = null
            } finally {
                this.isLoading = false
            }
        },

        async storeTask(task_description: string) {
            const { $axios, $fetchCsrfCookie } = useNuxtApp()
            const axios = $axios as AxiosInstance
            const fetchCsrfCookie = $fetchCsrfCookie as () => Promise<void>
    
            this.isLoading = true
            this.errors = null
            try {
                await fetchCsrfCookie()
                await axios.post('/api/tasks', { task_description }, {withCredentials: true})
                await this.fetchTasks()
            } catch (error : any) {
                if(error.response && error.response.status === 422){
                    this.errors = error.response.data.errors
                }
            } finally {
                this.isLoading = false
            }
        },

        async update(id : number) {
            const { $axios, $fetchCsrfCookie } = useNuxtApp()
            const axios = $axios as AxiosInstance
            const fetchCsrfCookie = $fetchCsrfCookie as () => Promise<void>
    
            this.isLoading = true
            this.errors = null
            try {
                await fetchCsrfCookie()
                await axios.put(`/api/tasks/${id}`)
                await this.fetchTasks(this.date || undefined)
            } catch (error : any) {
            if(error.response && error.response.status === 500){
                this.errors = error.response.data.errors
            }
    
            } finally {
                this.isLoading = false
            }
        },

        async updateOrder(tasks : Task[]) {
            const { $axios, $fetchCsrfCookie } = useNuxtApp()
            const axios = $axios as AxiosInstance
            const fetchCsrfCookie = $fetchCsrfCookie as () => Promise<void>
    
            this.isLoading = true
            this.errors = null
            try {
                await fetchCsrfCookie()
                await axios.post(`/api/tasks/order/`, { tasks })
                await this.fetchTasks(this.date || undefined)
            } catch (error : any) {
            if(error.response && error.response.status === 500){
                this.errors = error.response.data.errors
            }
    
            } finally {
                this.isLoading = false
            }
        },

        async deleteTask(id: number) {
            const { $axios, $fetchCsrfCookie } = useNuxtApp()
            const axios = $axios as AxiosInstance
            const fetchCsrfCookie = $fetchCsrfCookie as () => Promise<void>
    
            this.isLoading = true
            this.errors = null
            try {
                await fetchCsrfCookie()
                await axios.delete(`/api/tasks/${id}`)
                await this.fetchTasks(this.date || undefined)
                await this.fetchHistoryDates()
            } catch (error : any) {
            if(error.response && error.response.status === 500){
                this.errors = error.response.data.errors
            }
    
            } finally {
                this.isLoading = false
            }
        },
    }
})