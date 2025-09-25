import axios from 'axios'

export default defineNuxtPlugin((nuxtApp) => {
  const config = useRuntimeConfig();
  const api = axios.create({
    baseURL: config.public.apiBase, 
    withCredentials: true,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    }
  });

  async function fetchCsrfCookie() {
    await api.get('/sanctum/csrf-cookie', {withCredentials: true});
  }

  api.interceptors.request.use(config => {
    if (['post', 'put', 'delete'].includes((config.method || '').toLowerCase())) {
      const csrfToken = document.cookie
        .split('; ')
        .find(row => row.startsWith('XSRF-TOKEN='))
        ?.split('=')[1];
      if (csrfToken) {
        config.headers['X-XSRF-TOKEN'] = decodeURIComponent(csrfToken);
      }
    }
    return config;
  });

  nuxtApp.provide('axios', api);
  nuxtApp.provide('fetchCsrfCookie', fetchCsrfCookie);
});