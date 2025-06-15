<template>
  <label :for="name" class="block mt-5 mb-2 font-medium text-gray-500">{{ label }}</label>

  <div class="relative">
    <div
      class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"
    >
      <slot name="iconStart"></slot>
    </div>

    <input
      :name="name"
      :id="name"
      :type="type"
      :value="inputValue"
      :placeholder="placeholder"
      @input="handleChange"
      @blur="handleBlur"
      class="border bg-gray-50 border-gray-400 pr-3 text-sm rounded-md block w-full p-2.5 focus:outline-none focus:ring-1 transition-colors"
      :class="[
        hasStartIcon ? 'pl-10' : '',
        hasTrailingIcon ? 'pr-1' : '',
        meta.valid && meta.touched ? 'success-input' : 'bg-gray-50',
        !!errorMessage ? 'error-input' : ' ',
      ]"
    />

    <div
      class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"
    >
      <slot name="iconTrailing"></slot>
    </div>
  </div>

  <p v-if="errorMessage" class="text-red-500 text-sm mt-1 ml-1 block">
    {{
      errorMessage.includes('Invalid input: expected string, received undefined')
        ? 'Please enter required fields'
        : errorMessage
    }}
  </p>
</template>

<script setup>
import { toRef, useSlots } from 'vue'
import { useField } from 'vee-validate'

const slots = useSlots()
const hasStartIcon = !!slots.iconStart
const hasTrailingIcon = !!slots.iconTrailing

const props = defineProps({
  type: {
    type: String,
    default: 'text',
  },
  value: {
    type: String,
    default: undefined,
  },
  name: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    required: true,
  },
  placeholder: {
    type: String,
    default: '',
  },
})

const name = toRef(props, 'name')

const {
  value: inputValue,
  errorMessage,
  handleBlur,
  handleChange,
  meta, // This meta is field-specific, not form-level
} = useField(name, null, {
  initialValue: props.value,
})
</script>
