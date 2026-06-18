import addressService from '@/services/address.service'

const getDefaultFilters = () => ({
  search: '',
  state: '',
  sortBy: 'id',
  sortDir: 'desc',
  page: 1,
  perPage: 10
})

export default {
  namespaced: true,

  state: () => ({
    items: [],
    current: null,
    filters: getDefaultFilters(),
    pagination: {
      current_page: 1,
      last_page: 1
    },
    error: '',
    deleteTarget: null
  }),

  getters: {
    items: (state) => state.items,
    current: (state) => state.current,
    filters: (state) => state.filters,
    pagination: (state) => state.pagination,
    error: (state) => state.error,
    deleteTarget: (state) => state.deleteTarget,
    deleteMessage: (state) => {
      if (!state.deleteTarget) return ''

      return `Deseja excluir o endereço "${state.deleteTarget.street}"?`
    }
  },

  mutations: {
    SET_ITEMS (state, items) {
      state.items = items
    },
    SET_CURRENT (state, item) {
      state.current = item
    },
    SET_FILTERS (state, filters) {
      state.filters = { ...state.filters, ...filters }
    },
    RESET_FILTERS (state) {
      state.filters = getDefaultFilters()
    },
    SET_PAGINATION (state, pagination) {
      state.pagination = pagination
    },
    SET_ERROR (state, error) {
      state.error = error
    },
    SET_DELETE_TARGET (state, item) {
      state.deleteTarget = item
    }
  },

  actions: {
    async fetchList ({ state, commit }) {
      commit('SET_ERROR', '')

      try {
        const { search, state: uf, sortBy, sortDir, page, perPage } = state.filters
        const { data } = await addressService.list({
          page,
          per_page: perPage,
          search: search || undefined,
          state: uf || undefined,
          sort_by: sortBy,
          sort_dir: sortDir
        })

        commit('SET_ITEMS', data.data)
        commit('SET_PAGINATION', {
          current_page: data.current_page,
          last_page: data.last_page
        })
      } catch {
        commit('SET_ERROR', 'Não foi possível carregar os endereços.')
      }
    },

    async fetchOne ({ commit }, id) {
      commit('SET_ERROR', '')

      try {
        const { data } = await addressService.get(id)
        commit('SET_CURRENT', data)

        return data
      } catch {
        commit('SET_ERROR', 'Não foi possível carregar o endereço.')
        throw new Error('fetch_failed')
      }
    },

    async create ({ commit }, payload) {
      commit('SET_ERROR', '')

      try {
        await addressService.create(payload)
      } catch (error) {
        const message = extractApiError(error, 'Não foi possível salvar o endereço.')
        commit('SET_ERROR', message)
        throw error
      }
    },

    async update ({ commit }, { id, payload }) {
      commit('SET_ERROR', '')

      try {
        await addressService.update(id, payload)
      } catch (error) {
        const message = extractApiError(error, 'Não foi possível salvar o endereço.')
        commit('SET_ERROR', message)
        throw error
      }
    },

    async remove ({ commit, dispatch, state }) {
      if (!state.deleteTarget) return

      commit('SET_ERROR', '')

      try {
        await addressService.remove(state.deleteTarget.id)
        commit('SET_DELETE_TARGET', null)
        await dispatch('fetchList')
      } catch (error) {
        const message = error.response?.data?.errors?.address?.[0]
          || error.response?.data?.message
          || 'Não foi possível excluir o endereço.'

        commit('SET_ERROR', message)
        commit('SET_DELETE_TARGET', null)
        throw error
      }
    },

    setFilter ({ commit, dispatch }, filters) {
      commit('SET_FILTERS', filters)
      return dispatch('fetchList')
    },

    setPage ({ commit, dispatch }, page) {
      commit('SET_FILTERS', { page })
      return dispatch('fetchList')
    },

    toggleSort ({ commit, dispatch, state }, column) {
      const { sortBy, sortDir } = state.filters
      const nextDir = sortBy === column && sortDir === 'asc' ? 'desc' : 'asc'

      commit('SET_FILTERS', {
        sortBy: column,
        sortDir: sortBy === column ? nextDir : 'asc',
        page: 1
      })

      return dispatch('fetchList')
    },

    openDelete ({ commit }, item) {
      commit('SET_DELETE_TARGET', item)
    },

    closeDelete ({ commit }) {
      commit('SET_DELETE_TARGET', null)
    },

    clearCurrent ({ commit }) {
      commit('SET_CURRENT', null)
      commit('SET_ERROR', '')
    }
  }
}

function extractApiError (error, fallback) {
  const errors = error.response?.data?.errors

  if (errors) {
    const firstKey = Object.keys(errors)[0]

    return errors[firstKey][0]
  }

  return fallback
}
