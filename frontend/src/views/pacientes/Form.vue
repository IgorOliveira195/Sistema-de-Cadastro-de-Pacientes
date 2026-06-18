<template>
  <div class="page">
    <div class="page__header">
      <div>
        <h1>{{ isEditing ? 'Editar paciente' : 'Novo paciente' }}</h1>
        <p>Preencha os dados do paciente</p>
      </div>
      <router-link to="/pacientes" class="btn btn--ghost">Voltar</router-link>
    </div>

    <div class="form-card">
      <ValidationObserver v-slot="{ handleSubmit }" ref="form">
        <form @submit.prevent="handleSubmit(onSubmit)">
          <div class="form-grid">
            <base-input
              v-model="form.name"
              label="Nome completo"
              name="nome"
              rules="required"
              wrapper-class="form-group--wide"
            />

            <base-input
              v-model="form.cpf"
              label="CPF"
              name="CPF"
              rules="required|cpf"
              mask="###.###.###-##"
              inputmode="numeric"
            />

            <base-input
              v-model="form.cns"
              label="CNS"
              name="CNS"
              rules="required|cns"
              mask="### #### #### ####"
              inputmode="numeric"
            />

            <base-input
              v-model="form.birth_date"
              label="Data de nascimento"
              name="data de nascimento"
              rules="required"
              type="date"
            />

            <base-input
              v-model="form.gender"
              label="Sexo"
              name="sexo"
              rules="required"
              type="select"
              :options="genderOptions"
            />

            <base-input
              v-model="form.phone"
              label="Telefone"
              name="telefone"
              rules="phone"
              :mask="['(##) ####-####', '(##) #####-####']"
              inputmode="numeric"
            />

            <base-input
              v-model="form.address_id"
              label="Endereço"
              name="endereço"
              rules="required"
              type="select"
              wrapper-class="form-group--wide"
              :options="addressSelectOptions"
            />
          </div>

          <p v-if="error" class="alert alert--error">{{ error }}</p>

          <div class="form-actions">
            <router-link to="/pacientes" class="btn btn--ghost">Cancelar</router-link>
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
import { formatCpf, formatCns, formatPhone } from '@/utils/format'

export default {
  name: 'PacientesForm',
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
        address_id: '',
        name: '',
        cpf: '',
        cns: '',
        birth_date: '',
        gender: '',
        phone: ''
      },
      submitting: false
    }
  },
  computed: {
    ...mapGetters('patients', ['error', 'addressOptions']),
    isEditing () {
      return Boolean(this.id)
    },
    genderOptions () {
      return [
        { value: '', label: 'Selecione' },
        { value: 'M', label: 'Masculino' },
        { value: 'F', label: 'Feminino' },
        { value: 'O', label: 'Outro' }
      ]
    },
    addressSelectOptions () {
      return [
        { value: '', label: 'Selecione um endereço' },
        ...this.addressOptions.map((address) => ({
          value: address.id,
          label: `${address.street}, ${address.neighborhood} - ${address.city}/${address.state}`
        }))
      ]
    }
  },
  created () {
    this.fetchAddressOptions()

    if (this.isEditing) {
      this.loadPatient()
    } else {
      this.clearCurrent()
    }
  },
  methods: {
    ...mapActions('patients', [
      'fetchOne',
      'fetchAddressOptions',
      'create',
      'update',
      'clearCurrent'
    ]),
    async loadPatient () {
      try {
        const data = await this.fetchOne(this.id)

        this.form = {
          address_id: data.address_id,
          name: data.name,
          cpf: formatCpf(data.cpf),
          cns: formatCns(data.cns),
          birth_date: String(data.birth_date).split('T')[0],
          gender: data.gender,
          phone: data.phone ? formatPhone(data.phone) : ''
        }
      } catch {
        // erro tratado no store
      }
    },
    async onSubmit () {
      this.submitting = true

      const phoneDigits = this.form.phone.replace(/\D/g, '')

      const payload = {
        address_id: Number(this.form.address_id),
        name: this.form.name,
        cpf: this.form.cpf.replace(/\D/g, ''),
        cns: this.form.cns.replace(/\D/g, ''),
        birth_date: this.form.birth_date,
        gender: this.form.gender,
        phone: phoneDigits || null
      }

      try {
        if (this.isEditing) {
          await this.update({ id: this.id, payload })
        } else {
          await this.create(payload)
        }

        this.$router.push({ name: 'pacientes.index' })
      } catch {
        // erro tratado no store
      } finally {
        this.submitting = false
      }
    }
  }
}
</script>
