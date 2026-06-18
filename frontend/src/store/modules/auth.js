import api from '@/services/api'

const TOKEN_KEY = 'conectasus_token'

export default {
  namespaced: true,

  state: {
    token: localStorage.getItem(TOKEN_KEY) || '',
    user: null
  },

  getters: {
    token: (state) => state.token,
    user: (state) => state.user,
    isAuthenticated: (state) => Boolean(state.token)
  },

  mutations: {
    SET_TOKEN (state, token) {
      state.token = token
      localStorage.setItem(TOKEN_KEY, token)
    },
    SET_USER (state, user) {
      state.user = user
    },
    CLEAR_AUTH (state) {
      state.token = ''
      state.user = null
      localStorage.removeItem(TOKEN_KEY)
    }
  },

  actions: {
    clearSession ({ commit }) {
      commit('CLEAR_AUTH')
    },

    async login ({ commit }, credentials) {
      const { data } = await api.post('/login', credentials)

      commit('SET_TOKEN', data.token)
      commit('SET_USER', data.user)

      return data
    },

    async fetchMe ({ commit }) {
      const { data } = await api.get('/me')
      commit('SET_USER', data)
      return data
    },

    async logout ({ commit, state }) {
      const hadToken = Boolean(state.token)

      commit('CLEAR_AUTH')

      if (!hadToken) {
        return
      }

      try {
        await api.post('/logout')
      } catch {
        // Token já inválido ou sessão expirada.
      }
    },

    init ({ dispatch, getters }) {
      if (getters.isAuthenticated) {
        return dispatch('fetchMe').catch(() => dispatch('clearSession'))
      }

      return Promise.resolve()
    }
  }
}
