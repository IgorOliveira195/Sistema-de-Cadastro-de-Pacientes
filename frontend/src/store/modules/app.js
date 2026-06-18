export default {
  namespaced: true,

  state: {
    loading: false
  },

  getters: {
    loading: (state) => state.loading
  },

  mutations: {
    SET_LOADING (state, loading) {
      state.loading = loading
    }
  }
}
