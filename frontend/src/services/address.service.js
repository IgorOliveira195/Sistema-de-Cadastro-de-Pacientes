import api from './api'

export default {
  list (params = {}) {
    return api.get('/addresses', { params })
  },

  get (id) {
    return api.get(`/addresses/${id}`)
  },

  create (data) {
    return api.post('/addresses', data)
  },

  update (id, data) {
    return api.put(`/addresses/${id}`, data)
  },

  remove (id) {
    return api.delete(`/addresses/${id}`)
  }
}
