<template>
  <div class="page">
    <div class="page__header">
      <div>
        <h1>Pacientes</h1>
        <p>Gerencie os pacientes cadastrados no sistema</p>
      </div>
      <router-link to="/pacientes/novo" class="btn btn--primary">Novo paciente</router-link>
    </div>

    <div class="filters">
      <div class="filters__field">
        <label for="search">Buscar</label>
        <input
          id="search"
          v-model="search"
          type="search"
          placeholder="Nome, CPF ou CNS"
        >
      </div>
      <div class="filters__field filters__field--sm">
        <label for="gender">Sexo</label>
        <select id="gender" v-model="selectedGender">
          <option value="">Todos</option>
          <option value="M">Masculino</option>
          <option value="F">Feminino</option>
          <option value="O">Outro</option>
        </select>
      </div>
    </div>

    <p v-if="error" class="alert alert--error">{{ error }}</p>

    <base-table>
      <template #header>
        <th>
          <button type="button" class="sort-btn" @click="toggleSort('name')">
            Nome {{ sortIcon('name') }}
          </button>
        </th>
        <th>
          <button type="button" class="sort-btn" @click="toggleSort('cpf')">
            CPF {{ sortIcon('cpf') }}
          </button>
        </th>
        <th>CNS</th>
        <th>
          <button type="button" class="sort-btn" @click="toggleSort('birth_date')">
            Nascimento {{ sortIcon('birth_date') }}
          </button>
        </th>
        <th>Sexo</th>
        <th>Endereço</th>
        <th class="data-table__actions">Ações</th>
      </template>

      <tr v-if="!items.length">
        <td colspan="7" class="data-table__empty">Nenhum paciente encontrado.</td>
      </tr>
      <tr v-for="patient in items" :key="patient.id">
        <td>{{ patient.name }}</td>
        <td>{{ formatCpf(patient.cpf) }}</td>
        <td>{{ formatCns(patient.cns) }}</td>
        <td>{{ formatDate(patient.birth_date) }}</td>
        <td>{{ genderLabel(patient.gender) }}</td>
        <td>{{ addressLabel(patient.address) }}</td>
        <td class="data-table__actions">
          <router-link :to="`/pacientes/${patient.id}`" class="btn btn--ghost btn--sm">
            Editar
          </router-link>
          <button
            type="button"
            class="btn btn--danger btn--sm"
            @click="openDelete(patient)"
          >
            Excluir
          </button>
        </td>
      </tr>
    </base-table>

    <pagination
      :current-page="pagination.current_page"
      :last-page="pagination.last_page"
      @change="setPage"
    />

    <confirm-modal
      :visible="Boolean(deleteTarget)"
      title="Excluir paciente"
      :message="deleteMessage"
      @cancel="closeDelete"
      @confirm="confirmDelete"
    />
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import BaseTable from '@/components/BaseTable.vue'
import Pagination from '@/components/Pagination.vue'
import ConfirmModal from '@/components/ConfirmModal.vue'
import { formatCpf, formatCns, formatDate, genderLabel } from '@/utils/format'

export default {
  name: 'PacientesIndex',
  components: {
    BaseTable,
    Pagination,
    ConfirmModal
  },
  data () {
    return {
      search: '',
      selectedGender: '',
      searchTimer: null
    }
  },
  computed: {
    ...mapGetters('patients', [
      'items',
      'filters',
      'pagination',
      'error',
      'deleteTarget',
      'deleteMessage'
    ])
  },
  watch: {
    search () {
      clearTimeout(this.searchTimer)
      this.searchTimer = setTimeout(() => {
        this.setFilter({ search: this.search, page: 1 })
      }, 400)
    },
    selectedGender (value) {
      this.setFilter({ gender: value, page: 1 })
    }
  },
  created () {
    this.fetchList()
  },
  beforeDestroy () {
    clearTimeout(this.searchTimer)
  },
  methods: {
    formatCpf,
    formatCns,
    formatDate,
    genderLabel,
    addressLabel (address) {
      if (!address) return '-'

      return `${address.street}, ${address.city}/${address.state}`
    },
    ...mapActions('patients', [
      'fetchList',
      'setFilter',
      'setPage',
      'toggleSort',
      'openDelete',
      'closeDelete',
      'remove'
    ]),
    sortIcon (column) {
      if (this.filters.sortBy !== column) return ''

      return this.filters.sortDir === 'asc' ? '↑' : '↓'
    },
    async confirmDelete () {
      await this.remove()
    }
  }
}
</script>
