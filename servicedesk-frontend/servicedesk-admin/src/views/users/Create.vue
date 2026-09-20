<template>
  <AppLayout>
    <Notification ref="notificationRef" />
    
    <div class="max-w-4xl mx-auto space-y-6">
      <div class="flex items-center gap-4">
        <button @click="router.back()" class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Create New User</h1>
          <p class="text-sm text-gray-500 mt-1">Add a new user to the system</p>
        </div>
      </div>

      <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
        <form @submit.prevent="submitUser" class="space-y-6">
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Full Name <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="formData.name" 
              type="text" 
              placeholder="e.g., John Doe"
              class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
              :class="{ 'border-red-300 bg-red-50': errors.name }"
              @input="clearError('name')"
            />
            <p v-if="errors.name" class="text-xs text-red-500 mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
              {{ errors.name }}
            </p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Email Address <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="formData.email" 
              type="email" 
              placeholder="e.g., employee@servicedesk.com"
              class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
              :class="{ 'border-red-300 bg-red-50': errors.email }"
              @input="clearError('email')"
            />
            <p v-if="errors.email" class="text-xs text-red-500 mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
              {{ errors.email }}
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Role <span class="text-red-500">*</span>
              </label>
              <select 
                v-model="formData.role" 
                class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all capitalize"
                :class="{ 'border-red-300 bg-red-50': errors.role }"
                @change="clearError('role')"
              >
                <option value="">Select a role...</option>
                <option value="Admin">Admin</option>
                <option value="employee">Employee</option> 
                <option value="User">User</option>
              </select>
              <p v-if="errors.role" class="text-xs text-red-500 mt-1 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                {{ errors.role }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Status <span class="text-red-500">*</span>
              </label>
              <div class="grid grid-cols-2 gap-2">
                <button 
                  v-for="stat in ['Active', 'Inactive']" 
                  :key="stat" 
                  type="button"
                  @click="formData.status = stat; clearError('status')"
                  class="px-3 py-2.5 rounded-xl text-xs font-semibold border-2 transition-all"
                  :class="[
                    formData.status === stat 
                      ? (stat === 'Active' ? 'bg-green-50 border-green-500 text-green-700' : 'bg-gray-50 border-gray-500 text-gray-700')
                      : 'bg-white border-gray-200 text-gray-600 hover:border-gray-300',
                    errors.status ? 'border-red-300' : ''
                  ]"
                >
                  {{ stat }}
                </button>
              </div>
              <p v-if="errors.status" class="text-xs text-red-500 mt-1 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                {{ errors.status }}
              </p>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
            <button 
              type="button" 
              @click="router.back()"
              class="px-6 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              :disabled="isSubmitting"
              class="px-6 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-all shadow-lg shadow-red-600/25 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg v-if="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              {{ isSubmitting ? 'Creating User...' : 'Create User' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import AppLayout from '../../components/layout/AppLayout.vue'
import Notification from '../../components/common/Notification.vue'
import { useNotification } from '../../composables/useNotification'
import { useAdminStore } from '../../stores/adminStore'

const router = useRouter()
const adminStore = useAdminStore()
const { notificationRef, success, error } = useNotification()

const formData = ref({
  name: '',
  email: '',
  role: '',
  status: 'Active'
})

const errors = ref({})
const isSubmitting = ref(false)

function clearError(field) {
  if (errors.value[field]) {
    errors.value[field] = ''
  }
}

function validateForm() {
  errors.value = {}
  let isValid = true

  if (!formData.value.name.trim()) {
    errors.value.name = 'Full name is required'
    isValid = false
  }

  if (!formData.value.email.trim()) {
    errors.value.email = 'Email is required'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.value.email)) {
    errors.value.email = 'Please enter a valid email address'
    isValid = false
  }

  if (!formData.value.role) {
    errors.value.role = 'Please select a role'
    isValid = false
  }

  if (!formData.value.status) {
    errors.value.status = 'Please select a status'
    isValid = false
  }

  return isValid
}

async function submitUser() {
  if (!validateForm()) {
    error('Please fix the errors in the form.', 'Validation Failed')
    return
  }

  isSubmitting.value = true

  try {
    const newUser = {
      name: formData.value.name,
      email: formData.value.email,
      role: formData.value.role,
      status: formData.value.status,
      password: 'password', 
      phone: '', 
      avatar: '' 
    }

    await adminStore.addUser(newUser)
    
    success('User has been created successfully!', 'Success')
    
    setTimeout(() => {
      router.push('/users')
    }, 1000)
  } catch (error) {
    console.error('Create user error:', error)
    error('Failed to create user. Please try again.', 'Error')
  } finally {
    isSubmitting.value = false
  }
}
</script>