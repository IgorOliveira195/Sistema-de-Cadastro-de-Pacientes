<template>
  <div v-if="visible" class="modal" @click.self="onCancel">
    <div class="modal__dialog" role="dialog" aria-modal="true">
      <h2>{{ title }}</h2>
      <p>{{ message }}</p>
      <div class="modal__actions">
        <button type="button" class="btn btn--ghost" @click="onCancel">
          Cancelar
        </button>
        <button type="button" class="btn btn--danger" @click="onConfirm">
          Excluir
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ConfirmModal',
  props: {
    visible: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Confirmar exclusão'
    },
    message: {
      type: String,
      default: 'Tem certeza que deseja excluir este registro?'
    }
  },
  methods: {
    onCancel () {
      this.$emit('cancel')
    },
    onConfirm () {
      this.$emit('confirm')
    }
  }
}
</script>
