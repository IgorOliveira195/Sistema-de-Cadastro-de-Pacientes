<template>
  <div class="layout">
    <app-navbar />
    <main class="layout__content">
      <router-view />
    </main>
    <app-loading />
  </div>
</template>

<script>
import AppNavbar from '@/components/AppNavbar.vue'
import AppLoading from '@/components/AppLoading.vue'

export default {
  name: 'AppLayout',
  components: {
    AppNavbar,
    AppLoading
  }
}
</script>
