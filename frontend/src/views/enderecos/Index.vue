<template>
  <div class="page">
    <div class="page__header">
      <div>
        <h1>Endereços</h1>
        <p>Gerencie os endereços cadastrados no sistema</p>
      </div>
      <router-link to="/enderecos/novo" class="btn btn--primary">Novo endereço</router-link>
    </div>

    <div class="filters">
      <div class="filters__field">
        <label for="search">Buscar</label>
        <input
          id="search"
          v-model="search"
          type="search"
          placeholder="Logradouro, bairro, cidade ou CEP"
        >
      </div>
      <div class="filters__field filters__field--sm">
        <label for="state">UF</label>
        <select id="state" v-model="selectedState">
          <option value="">Todas</option>
          <option v-for="uf in ufs" :key="uf" :value="uf">{{ uf }}</option>
        </select>
      </div>
    </div>

    <p v-if="error" class="alert alert--error">{{ error }}</p>

    <base-table>
      <template #header>
        <th>
          <button type="button" class="sort-btn" @click="toggleSort('street')">
            Logradouro {{ sortIcon('street') }}
          </button>
        </th>
        <th>
          <button type="button" class="sort-btn" @click="toggleSort('zip_code')">
            CEP {{ sortIcon('zip_code') }}
          </button>
        </th>
        <th>Bairro</th>
        <th>
          <button type="button" class="sort-btn" @click="toggleSort('city')">
            Cidade {{ sortIcon('city') }}
          </button>
        </th>
        <th>
          <button type="button" class="sort-btn" @click="toggleSort('state')">
            UF {{ sortIcon('state') }}
          </button>
        </th>
        <th>Pacientes</th>
        <th class="data-table__actions">Ações</th>
      </template>

      <tr v-if="!items.length">
        <td colspan="7" class="data-table__empty">Nenhum endereço encontrado.</td>
      </tr>
      <tr v-for="address in items" :key="address.id">
        <td>{{ address.street }}</td>
        <td>{{ formatCep(address.zip_code) }}</td>
        <td>{{ address.neighborhood }}</td>
        <td>{{ address.city }}</td>
        <td>{{ address.state }}</td>
        <td>{{ address.patients_count }}</td>
        <td class="data-table__actions">
          <router-link :to="`/enderecos/${address.id}`" class="btn btn--ghost btn--sm">
            Editar
          </router-link>
          <button
            type="button"
            class="btn btn--danger btn--sm"
            @click="openDelete(address)"
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
      title="Excluir endereço"
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
import { UFS } from '@/utils/ufs'
import { formatCep } from '@/utils/format'

export default {
  name: 'EnderecosIndex',
  components: {
    BaseTable,
    Pagination,
    ConfirmModal
  },
  data () {
    return {
      search: '',
      selectedState: '',
      searchTimer: null,
      ufs: UFS
    }
  },
  computed: {
    ...mapGetters('addresses', [
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
    selectedState (value) {
      this.setFilter({ state: value, page: 1 })
    }
  },
  created () {
    this.fetchList()
  },
  beforeDestroy () {
    clearTimeout(this.searchTimer)
  },
  methods: {
    formatCep,
    ...mapActions('addresses', [
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
