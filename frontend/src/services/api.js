import axios from 'axios'

const api = axios.create({
  baseURL: process.env.VUE_APP_API_URL,
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json'
  }
})

export function setupApiInterceptors (store, router) {
  let isHandlingUnauthorized = false

  api.interceptors.request.use((config) => {
    const token = store.getters['auth/token']

    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }

    store.commit('app/SET_LOADING', true)

    return config
  })

  api.interceptors.response.use(
    (response) => {
      store.commit('app/SET_LOADING', false)
      return response
    },
    (error) => {
      store.commit('app/SET_LOADING', false)

      const url = error.config?.url || ''
      const isAuthRequest = url.includes('/login') || url.includes('/logout')
      const isUnauthorized = error.response?.status === 401

      if (isUnauthorized && !isAuthRequest && !isHandlingUnauthorized) {
        isHandlingUnauthorized = true

        store.dispatch('auth/clearSession').finally(() => {
          isHandlingUnauthorized = false
        })

        if (router.currentRoute.name !== 'login') {
          router.push({ name: 'login' })
        }
      }

      return Promise.reject(error)
    }
  )
}

export default api
