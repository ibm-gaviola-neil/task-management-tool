// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: false },
  modules: ['@nuxtjs/tailwindcss', 'shadcn-nuxt', '@pinia/nuxt'],
  shadcn : {
    prefix: '',
    /**
     * Directory that the component lives in.
     * @default "./components/ui",
     */
    componentDir: './components/ui'
  },
  components: [
    { path: '~/components', extensions: ['vue'] }
  ],
})