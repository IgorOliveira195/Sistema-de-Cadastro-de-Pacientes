<template>
  <div class="page">
    <div class="page__header">
      <div>
        <h1>{{ isEditing ? 'Editar endereço' : 'Novo endereço' }}</h1>
        <p>Preencha os dados do endereço</p>
      </div>
      <router-link to="/enderecos" class="btn btn--ghost">Voltar</router-link>
    </div>

    <div class="form-card">
      <ValidationObserver v-slot="{ handleSubmit }" ref="form">
        <form @submit.prevent="handleSubmit(onSubmit)">
          <div class="form-grid">
            <base-input
              v-model="form.zip_code"
              label="CEP"
              name="CEP"
              rules="required|cep"
              mask="#####-###"
              inputmode="numeric"
              @blur="onCepBlur"
            >
              <template #hint>
                <small v-if="cepLoading" class="form-hint">Buscando CEP...</small>
                <small v-else-if="cepError" class="form-error">{{ cepError }}</small>
              </template>
            </base-input>

            <base-input
              v-model="form.street"
              label="Logradouro"
              name="logradouro"
              rules="required"
              wrapper-class="form-group--wide"
            />

            <base-input
              v-model="form.neighborhood"
              label="Bairro"
              name="bairro"
              rules="required"
            />

            <base-input
              v-model="form.city"
              label="Cidade"
              name="cidade"
              rules="required"
            />

            <base-input
              v-model="form.state"
              label="UF"
              name="estado"
              rules="required|uf"
              type="select"
              :options="ufOptions"
            />
          </div>

          <p v-if="error" class="alert alert--error">{{ error }}</p>

          <div class="form-actions">
            <router-link to="/enderecos" class="btn btn--ghost">Cancelar</router-link>
            <button type="submit" class="btn btn--primary" :disabled="submitting">
              Salvar
            </button>
          </div>
        </form>
      </ValidationObserver>
    </div>
  </div>
</template>

<script>
import { ValidationObserver } from 'vee-validate'
import { mapGetters, mapActions } from 'vuex'
import BaseInput from '@/components/BaseInput.vue'
import { fetchAddressByCep } from '@/services/viacep'
import { UFS } from '@/utils/ufs'
import { formatCep } from '@/utils/format'

export default {
  name: 'EnderecosForm',
  components: {
    ValidationObserver,
    BaseInput
  },
  props: {
    id: {
      type: [String, Number],
      default: null
    }
  },
  data () {
    return {
      form: {
        street: '',
        zip_code: '',
        neighborhood: '',
        city: '',
        state: ''
      },
      cepError: '',
      cepLoading: false,
      submitting: false
    }
  },
  computed: {
    ...mapGetters('addresses', ['error']),
    isEditing () {
      return Boolean(this.id)
    },
    ufOptions () {
      return [
        { value: '', label: 'Selecione' },
        ...UFS.map((uf) => ({ value: uf, label: uf }))
      ]
    }
  },
  created () {
    if (this.isEditing) {
      this.loadAddress()
    } else {
      this.clearCurrent()
    }
  },
  methods: {
    ...mapActions('addresses', ['fetchOne', 'create', 'update', 'clearCurrent']),
    async loadAddress () {
      try {
        const data = await this.fetchOne(this.id)

        this.form = {
          street: data.street,
          zip_code: formatCep(data.zip_code),
          neighborhood: data.neighborhood,
          city: data.city,
          state: data.state
        }
      } catch {
        // erro tratado no store
      }
    },
    async onCepBlur () {
      const digits = this.form.zip_code.replace(/\D/g, '')

      if (digits.length !== 8) return

      this.cepError = ''
      this.cepLoading = true

      try {
        const address = await fetchAddressByCep(digits)

        this.form.street = address.street || this.form.street
        this.form.neighborhood = address.neighborhood || this.form.neighborhood
        this.form.city = address.city || this.form.city
        this.form.state = address.state || this.form.state
      } catch (error) {
        this.cepError = error.message
      } finally {
        this.cepLoading = false
      }
    },
    async onSubmit () {
      this.submitting = true

      const payload = {
        ...this.form,
        zip_code: this.form.zip_code.replace(/\D/g, ''),
        state: this.form.state.toUpperCase()
      }

      try {
        if (this.isEditing) {
          await this.update({ id: this.id, payload })
        } else {
          await this.create(payload)
        }

        this.$router.push({ name: 'enderecos.index' })
      } catch {
        // erro tratado no store
      } finally {
        this.submitting = false
      }
    }
  }
}
</script>
