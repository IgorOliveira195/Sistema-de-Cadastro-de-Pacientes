import Vue from 'vue'
import { extend, localize } from 'vee-validate'
import { required, email } from 'vee-validate/dist/rules'
import ptBR from 'vee-validate/dist/locale/pt_BR.json'
import VueMask from 'v-mask'
import App from './App.vue'
import router from './router'
import store from './store'
import api, { setupApiInterceptors } from './services/api'
import { isValidCpf } from './utils/cpf'
import { UFS } from './utils/ufs'
import './assets/main.css'

extend('required', required)
extend('email', email)

extend('cep', {
  validate: (value) => /^\d{8}$/.test(String(value || '').replace(/\D/g, '')),
  message: 'O CEP deve conter 8 dígitos.'
})

extend('uf', {
  validate: (value) => UFS.includes(String(value || '').toUpperCase()),
  message: 'Selecione uma UF válida.'
})

extend('cpf', {
  validate: (value) => isValidCpf(value),
  message: 'Informe um CPF válido.'
})

extend('cns', {
  validate: (value) => /^\d{15}$/.test(String(value || '').replace(/\D/g, '')),
  message: 'O CNS deve conter 15 dígitos.'
})

extend('phone', {
  validate: (value) => {
    if (!value) return true

    const digits = String(value).replace(/\D/g, '')

    return digits.length === 10 || digits.length === 11
  },
  message: 'Informe um telefone válido com DDD.'
})

localize('pt_BR', ptBR)

Vue.use(VueMask)
Vue.config.productionTip = false

setupApiInterceptors(store, router)

store.dispatch('auth/init').finally(() => {
  new Vue({
    router,
    store,
    render: h => h(App)
  }).$mount('#app')
})

export { api }
