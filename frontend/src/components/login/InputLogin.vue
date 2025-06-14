<template lang="">
  <form @submit="onSubmit">
    <label for="matric_number" class="block mt-5 mb-2 font-medium text-gray-500">
      Matric Number
    </label>
    <div class="relative">
      <Field name="matric_number" v-slot="{ field, meta }">
        <input
          v-bind="field"
          type="text"
          id="matric_number"
          :class="[
            'bg-gray-50 border text-sm rounded-md block w-full pl-10 p-2.5 focus:outline-none focus:ring-1 transition-colors',
            meta.valid && meta.touched
              ? 'border-green-500 text-gray-700 focus:border-green-500 focus:ring-green-500'
              : errors.matric_number
                ? 'border-red-500 text-red-700 focus:border-red-500 focus:ring-red-500'
                : 'border-gray-500 text-gray-700 focus:border-primary focus:ring-primary focus:text-gray-700',
          ]"
          placeholder="e.g., A12BC3456"
        />
      </Field>

      <UserIcon
        :class="[
          'absolute left-3 top-1/2 transform -translate-y-1/2 transition-colors',
          errors.matric_number ? 'text-red-500' : 'text-gray-500',
        ]"
        width="20"
        height="20"
      />
    </div>

    <ErrorMessage name="matric_number" class="text-red-500 text-sm mt-1 ml-1 block" />

    <div class="mt-4">
      <button
        type="submit"
        :disabled="isSubmitting || !meta.valid"
        :class="[
          'text-white text-sm font-medium rounded-md block w-full py-2.5 focus:outline-none focus:ring-2 focus:ring-opacity-50 flex items-center justify-center space-x-2 login-button transition-all duration-200 cursor-not-allowed',
          meta.valid && !isSubmitting
            ? 'bg-primary hover:bg-[#77263F] hover:cursor-pointer focus:ring-primary'
            : 'bg-primary opacity-75',
        ]"
      >
        <!-- Loading state -->
        <div
          v-if="isSubmitting"
          class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"
        ></div>

        <!-- Button Text -->
        <span v-else>Log In</span>

        <div v-if="!isSubmitting" class="arrow-container text-white">
          <ArrowRight :width="20" :height="20" />
        </div>
      </button>
    </div>
  </form>
</template>

<script setup>
import { User as UserIcon, ArrowRight } from 'lucide-vue-next'
import { useForm, Field, ErrorMessage } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import { z } from 'zod'

const validationSchema = toTypedSchema(
  z.object({
    matric_number: z
      .string()
      .min(1, 'Matric number is required')
      .transform((val) => val.toUpperCase()),
  }),
)

const { handleSubmit, errors, meta, isSubmitting } = useForm({
  validationSchema,
  initialValues: {
    matric_number: '',
  },
})

const onSubmit = handleSubmit(async (values) => {
  try {
    console.log('Logging in with:', values)

    // Simulate API call
    await new Promise((resolve) => setTimeout(resolve, 1000))

    // call API
    // const response = await loginApi(values.matric_number)

    // On success, redirect to dashboard
  } catch (error) {
    console.error('Login failed:', error)
    // Handle login error (show toast, etc.)
  }
})
</script>

<style scoped>
.login-button:hover:not(:disabled) .arrow-container {
  transform: translateX(5px);
}

.arrow-container {
  transition: transform 0.2s ease-out;
}
</style>
