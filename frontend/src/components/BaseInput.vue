<template>
  <ValidationProvider
    v-slot="{ errors }"
    :name="name"
    :rules="rules"
    class="form-group"
    :class="wrapperClass"
  >
    <label :for="inputId">{{ label }}</label>

    <select
      v-if="type === 'select'"
      :id="inputId"
      :value="value"
      :disabled="disabled"
      @change="$emit('input', $event.target.value)"
    >
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>

    <input
      v-else-if="mask"
      :id="inputId"
      :value="value"
      :type="type"
      :placeholder="placeholder"
      :disabled="disabled"
      :inputmode="inputmode"
      v-mask="mask"
      v-bind="$attrs"
      @input="$emit('input', $event.target.value)"
      @blur="$emit('blur', $event)"
    >

    <input
      v-else
      :id="inputId"
      :value="value"
      :type="type"
      :placeholder="placeholder"
      :disabled="disabled"
      :inputmode="inputmode"
      v-bind="$attrs"
      @input="$emit('input', $event.target.value)"
      @blur="$emit('blur', $event)"
    >

    <span class="form-error">{{ errors[0] }}</span>
    <slot name="hint" />
  </ValidationProvider>
</template>

<script>
import { ValidationProvider } from 'vee-validate'

let inputCounter = 0

export default {
  name: 'BaseInput',
  components: {
    ValidationProvider
  },
  inheritAttrs: false,
  props: {
    value: {
      type: [String, Number],
      default: ''
    },
    label: {
      type: String,
      required: true
    },
    name: {
      type: String,
      required: true
    },
    rules: {
      type: [String, Object],
      default: ''
    },
    type: {
      type: String,
      default: 'text'
    },
    placeholder: {
      type: String,
      default: ''
    },
    disabled: {
      type: Boolean,
      default: false
    },
    inputmode: {
      type: String,
      default: undefined
    },
    mask: {
      type: [String, Array],
      default: null
    },
    options: {
      type: Array,
      default: () => []
    },
    wrapperClass: {
      type: [String, Object, Array],
      default: ''
    }
  },
  data () {
    inputCounter += 1

    return {
      inputId: `base-input-${inputCounter}`
    }
  }
}
</script>
