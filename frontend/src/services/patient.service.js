import api from './api'

export default {
  list (params = {}) {
    return api.get('/patients', { params })
  },

  get (id) {
    return api.get(`/patients/${id}`)
  },

  create (data) {
    return api.post('/patients', data)
  },

  update (id, data) {
    return api.put(`/patients/${id}`, data)
  },

  remove (id) {
    return api.delete(`/patients/${id}`)
  }
}
