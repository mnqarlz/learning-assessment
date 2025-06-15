<template>
  <div>
    <h1 class="md:text-2xl font-medium text-gray-500 mt-2">
      Welcome back, let’s get you back on managing your student
    </h1>

    <div class="grid gap-6 mb-6 lg:grid-cols-2">
      <Form
        :validation-schema="validationSchema"
        @submit="onSubmit"
        v-slot="{ meta: formMeta, isSubmitting }"
      >
        <InputForm name="email" label="Email Address" placeholder="Enter your email address">
          <template #iconStart>
            <MailIcon width="20" height="20" />
          </template>
        </InputForm>

        <InputForm
          name="password"
          label="Password"
          placeholder="Enter your password"
          :type="showPassword ? 'text' : 'password'"
        >
          <template #iconStart>
            <LockIcon width="20" height="20" />
          </template>
          <template #iconTrailing>
            <button
              type="button"
              @click="togglePassword"
              class="text-gray-500 focus:outline-none cursor-pointer pt-2 hover:text-gray-700"
            >
              <EyeOffIcon v-if="showPassword" width="20" height="20" />
              <EyeIcon v-else width="20" height="20" />
            </button>
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
import { z } from 'zod/v4'
import { ref } from 'vue'
import { Form, useForm } from 'vee-validate'
import InputForm from '@/components/InputForm.vue'
import { toTypedSchema } from '@vee-validate/zod'
import { EyeIcon, EyeOffIcon, LockIcon, ArrowRightIcon, MailIcon } from 'lucide-vue-next'

const validationSchema = toTypedSchema(
  z.object({
    email: z.email(),
    password: z
      .string()
      .min(1, 'Matric number is required')
      .min(5, 'Matric number must be at least 5 characters'),
  }),
)

const showPassword = ref(false)

function togglePassword() {
  showPassword.value = !showPassword.value
}

const { isSubmitting } = useForm({
  validationSchema,
  initialValues: {
    email: '',
    password: '',
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
