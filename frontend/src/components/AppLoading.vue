<template>
  <div v-if="loading" class="loading-overlay">
    <div class="loading-spinner" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'AppLoading',
  computed: {
    ...mapGetters('app', ['loading'])
  }
}
</script>
