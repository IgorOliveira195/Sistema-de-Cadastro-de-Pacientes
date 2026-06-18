<template>
  <div class="login-page">
    <div class="login-card">
      <div class="login-card__header">
        <span class="login-card__logo">+</span>
        <h1>ConectaSUS</h1>
        <p>Sistema de Cadastro de Pacientes</p>
      </div>

      <ValidationObserver v-slot="{ handleSubmit }" ref="form">
        <form @submit.prevent="handleSubmit(onSubmit)">
          <ValidationProvider
            v-slot="{ errors }"
            name="e-mail"
            rules="required|email"
          >
            <div class="form-group">
              <label for="email">E-mail</label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                autocomplete="username"
              >
              <span class="form-error">{{ errors[0] }}</span>
            </div>
          </ValidationProvider>

          <ValidationProvider
            v-slot="{ errors }"
            name="senha"
            rules="required"
          >
            <div class="form-group">
              <label for="password">Senha</label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                autocomplete="current-password"
              >
              <span class="form-error">{{ errors[0] }}</span>
            </div>
          </ValidationProvider>

          <p v-if="apiError" class="form-error form-error--general">
            {{ apiError }}
          </p>

          <button
            type="submit"
            class="btn btn--primary btn--block"
            :class="{ 'btn--loading': submitting }"
            :disabled="submitting"
          >
            <span v-if="submitting" class="btn__spinner" aria-hidden="true" />
            {{ submitting ? 'Entrando...' : 'Entrar' }}
          </button>
        </form>
      </ValidationObserver>
    </div>
  </div>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate'

export default {
  name: 'LoginView',
  components: {
    ValidationObserver,
    ValidationProvider
  },
  data () {
    return {
      form: {
        email: 'admin@conectasus.com',
        password: 'password'
      },
      apiError: '',
      submitting: false
    }
  },
  methods: {
    async onSubmit () {
      this.apiError = ''
      this.submitting = true

      try {
        await this.$store.dispatch('auth/login', this.form)
        this.$router.push({ name: 'dashboard' })
      } catch (error) {
        const errors = error.response?.data?.errors

        if (errors?.email) {
          this.apiError = errors.email[0]
        } else if (!error.response) {
          this.apiError = 'Não foi possível conectar à API. Verifique se o backend está rodando em http://localhost:8000.'
        } else {
          this.apiError = error.response?.data?.message || 'Não foi possível realizar o login.'
        }
      } finally {
        this.submitting = false
      }
    }
  }
}
</script>
