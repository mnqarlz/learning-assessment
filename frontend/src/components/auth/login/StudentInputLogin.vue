<template>
  <div>
    <h1 class="md:text-2xl font-medium text-gray-500 mt-2">
      Welcome back, let's get you back on track with your courses.
    </h1>

    <div class="grid gap-6 mb-6 lg:grid-cols-2">
      <Form
        :validation-schema="validationSchema"
        @submit="onSubmit"
        v-slot="{ meta: formMeta, isSubmitting }"
      >
        <InputForm
          name="matric_number"
          label="Matric Number"
          placeholder="Enter your matric number (A200000)"
        >
          <template #iconStart>
            <UserIcon width="20" height="20" />
          </template>
        </InputForm>

        <div class="mt-4">
          <button
            type="submit"
            :disabled="isSubmitting || !formMeta.valid"
            :class="[
              'text-white text-sm font-medium rounded-md w-full py-2.5 focus:outline-none focus:ring-2 focus:ring-opacity-50 flex items-center justify-center space-x-2 login-button transition-all duration-200',
              formMeta.valid && !isSubmitting
                ? 'bg-primary hover:bg-primary-darker hover:cursor-pointer focus:ring-primary cursor-pointer'
                : 'bg-primary opacity-75 cursor-not-allowed',
            ]"
          >
            <div
              v-if="isSubmitting"
              class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"
            ></div>

            <div
              v-if="!isSubmitting"
              class="arrow-container inline-flex text-white items-center space-x-2"
            >
              <span>Log In</span>
              <ArrowRightIcon :width="20" :height="20" />
            </div>
          </button>
        </div>
      </Form>
    </div>
  </div>
</template>

<script setup>
import { z } from 'zod'
import { Form, useForm } from 'vee-validate'
import { UserIcon, ArrowRightIcon } from 'lucide-vue-next'
import InputForm from '@/components/InputForm.vue'
import { toTypedSchema } from '@vee-validate/zod'

const validationSchema = toTypedSchema(
  z.object({
    matric_number: z
      .string()
      .min(1, 'Matric number is required')
      .min(5, 'Matric number must be at least 5 characters')
      .transform((val) => val.toUpperCase()),
  }),
)

const { isSubmitting } = useForm({
  validationSchema,
  initialValues: {
    matric_number: '',
  },
})

const onSubmit = async (values) => {
  try {
    // Simulate API call
    await new Promise((resolve) => setTimeout(resolve, 1000))
    console.log('Logging in with:', values)

    // call API
    // const response = await loginApi(values.matric_number)

    // On success, redirect to dashboard
  } catch (error) {
    console.error('Login failed:', error)
    // Handle login error (show toast, etc.)
  }
}
</script>

<style scoped>
.login-button:hover:not(:disabled) .arrow-container {
  transform: translateX(5px);
}

.arrow-container {
  transition: transform 0.2s ease-out;
}
</style>
