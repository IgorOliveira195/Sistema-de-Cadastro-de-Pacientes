<template>
  <header class="navbar">
    <div class="navbar__brand">
      <span class="navbar__logo">+</span>
      <div>
        <strong>ConectaSUS</strong>
        <small>Cadastro de Pacientes</small>
      </div>
    </div>

    <nav class="navbar__links">
      <router-link to="/dashboard">Dashboard</router-link>
      <router-link to="/enderecos">Endereços</router-link>
      <router-link to="/pacientes">Pacientes</router-link>
    </nav>

    <div class="navbar__user">
      <span>{{ userName }}</span>
      <button type="button" class="btn btn--ghost" @click="handleLogout">
        Sair
      </button>
    </div>
  </header>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'AppNavbar',
  computed: {
    ...mapGetters('auth', ['user']),
    userName () {
      return this.user?.name || 'Usuário'
    }
  },
  methods: {
    async handleLogout () {
      await this.$store.dispatch('auth/logout')
      this.$router.push({ name: 'login' })
    }
  }
}
</script>
