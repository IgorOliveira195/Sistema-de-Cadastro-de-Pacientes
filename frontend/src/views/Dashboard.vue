<template>
  <div class="page">
    <div class="page__header">
      <div>
        <h1>Dashboard</h1>
        <p>Visão geral do sistema</p>
      </div>
    </div>

    <div v-if="error" class="alert alert--error">{{ error }}</div>

    <div class="cards">
      <div class="card">
        <span class="card__label">Pacientes cadastrados</span>
        <strong class="card__value">{{ totals.patients_total }}</strong>
      </div>
      <div class="card">
        <span class="card__label">Endereços cadastrados</span>
        <strong class="card__value">{{ totals.addresses_total }}</strong>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/services/api'

export default {
  name: 'DashboardPage',
  data () {
    return {
      totals: {
        patients_total: 0,
        addresses_total: 0
      },
      error: ''
    }
  },
  created () {
    this.loadDashboard()
  },
  methods: {
    async loadDashboard () {
      this.error = ''

      try {
        const { data } = await api.get('/dashboard')
        this.totals = data
      } catch {
        this.error = 'Não foi possível carregar os totais do dashboard.'
      }
    }
  }
}
</script>
