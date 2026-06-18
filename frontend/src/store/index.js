import Vue from 'vue'
import Vuex from 'vuex'
import auth from './modules/auth'
import app from './modules/app'
import addresses from './modules/addresses'
import patients from './modules/patients'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    auth,
    app,
    addresses,
    patients
  }
})
