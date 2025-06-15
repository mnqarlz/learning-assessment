<template>
  <div class="grid md:grid-cols-2 h-screen">
    <div class="flex items-center justify-center">
      <div class="md:mx-auto md:container px-12 md:px-14 lg:px-12 xl:lg:24">
        <h1 class="md:text-5xl font-bold text-primary">Log In</h1>
        <div class="grid gap-6 lg:grid-cols-2 mt-5">
          <div class="sm:hidden">
            <select
              id="tabs"
              v-model="activeTab"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary focus:border-primary block w-full p-2.5"
            >
              <option value="student">Students</option>
              <option value="staff">Staff</option>
            </select>
          </div>

          <ul
            class="hidden text-sm font-medium text-center text-gray-500 rounded-lg border border-gray-300 sm:flex"
          >
            <li class="w-full focus-within:z-10">
              <button
                @click="activeTab = 'student'"
                :class="[
                  'inline-block w-full p-2 rounded-s-lg transition-colors duration-200',
                  activeTab === 'student'
                    ? 'text-white bg-primary'
                    : 'bg-white border-gray-200 hover:text-gray-700 hover:bg-gray-50',
                ]"
                type="button"
              >
                Students
              </button>
            </li>
            <li class="w-full focus-within:z-10">
              <button
                @click="activeTab = 'staff'"
                :class="[
                  'inline-block w-full p-2 rounded-e-lg transition-colors duration-200',
                  activeTab === 'staff'
                    ? 'text-white bg-primary'
                    : 'bg-white border-gray-200 hover:text-gray-700 hover:bg-gray-50',
                ]"
                type="button"
              >
                Staff
              </button>
            </li>
          </ul>
        </div>

        <div class="mt-6">
          <Transition name="fade" mode="out-in">
            <StudentInputLogin v-if="activeTab === 'student'" key="student" />
            <StaffInputLogin v-else-if="activeTab === 'staff'" key="staff" />
          </Transition>
        </div>
      </div>
    </div>
    <div class="flex-grow relative h-full hidden md:block">
      <img src="/2103908.svg" class="md:w-3/4 absolute bottom-4 right-4" />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import StaffInputLogin from '@/components/auth/login/StaffInputLogin.vue'
import StudentInputLogin from '@/components/auth/login/StudentInputLogin.vue'

const activeTab = ref('student')
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease-in-out;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
