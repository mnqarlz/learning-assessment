<template>
  <div class="md:grid md:grid-cols-2 h-screen">
    <div class="flex items-center justify-center">
      <div class="md:mx-auto md:container px-14">
        <h1 class="md:text-4xl font-bold text-primary">Log In</h1>
        <h1 class="md:text-2xl font-medium text-gray-500 mt-2">
          Welcome back, let's get you back on track with your courses.
        </h1>
        <div>
          <div class="mt-2">
            <RouterLink to="/staff-login" class="text-gray-500 hover:underline hover:text-gray-600"
              >Staff Login</RouterLink
            >
          </div>

          <div class="grid gap-6 mb-6 md:grid-cols-2">
            <div>
              <InputLogin />
              <div class="mt-2 text-right">
                <RouterLink
                  to="/forgot-password"
                  class="text-gray-500 hover:underline hover:text-gray-600"
                  >Forgot Password</RouterLink
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="flex-grow relative h-full hidden md:block">
      <img src="/2103908.svg" class="md:w-3/4 absolute bottom-4 right-4" />
    </div>
  </div>
</template>

<script setup>
import InputLogin from '@/components/login/InputLogin.vue'
</script>
