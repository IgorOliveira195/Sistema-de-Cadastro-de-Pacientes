<template>
  <div class="table-card">
    <table class="data-table">
      <thead>
        <tr>
          <slot name="header" />
        </tr>
      </thead>
      <tbody>
        <slot />
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: 'BaseTable'
}
</script>
