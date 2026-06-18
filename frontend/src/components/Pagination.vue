<template>
  <div v-if="lastPage > 1" class="pagination">
    <button
      type="button"
      class="btn btn--ghost btn--sm"
      :disabled="currentPage <= 1"
      @click="$emit('change', currentPage - 1)"
    >
      Anterior
    </button>
    <span>Página {{ currentPage }} de {{ lastPage }}</span>
    <button
      type="button"
      class="btn btn--ghost btn--sm"
      :disabled="currentPage >= lastPage"
      @click="$emit('change', currentPage + 1)"
    >
      Próxima
    </button>
  </div>
</template>

<script>
export default {
  name: 'AppPagination',
  props: {
    currentPage: {
      type: Number,
      required: true
    },
    lastPage: {
      type: Number,
      required: true
    }
  }
}
</script>
