<template>
  <AppLayout>
    <Notification ref="notificationRef" />

    <div v-if="user" class="max-w-5xl mx-auto space-y-6">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
          <button @click="router.back()" class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center text-2xl font-bold border border-red-200">
              {{ localUser.name ? localUser.name.charAt(0).toUpperCase() : '?' }}
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-800">{{ localUser.name }}</h1>
              <p class="text-sm text-gray-500">{{ localUser.email }}</p>
            </div>
          </div>
        </div>
        
        <div class="flex items-center gap-2">
          <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border capitalize"
            :class="{
              'bg-purple-50 text-purple-700 border-purple-100': localUser.role === 'Admin',
              'bg-blue-50 text-blue-700 border-blue-100': localUser.role === 'employee' || localUser.role === 'Agent',
              'bg-gray-50 text-gray-700 border-gray-100': localUser.role === 'User'
            }">
            {{ localUser.role }}
          </span>
          <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-full border"
            :class="localUser.status === 'Active' ? 'text-green-700 bg-green-50 border-green-100' : 'text-red-700 bg-red-50 border-red-100'">
            <span class="w-1.5 h-1.5 rounded-full" :class="localUser.status === 'Active' ? 'bg-green-500' : 'bg-red-500'"></span>
            {{ localUser.status }}
          </span>
        </div>
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
          <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Personal Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                <input v-model="localUser.name" type="text" class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all" :class="{ 'border-red-300 bg-red-50': errors.name }" placeholder="e.g. John Doe" @input="clearError('name')" />
                <p v-if="errors.name" class="text-xs text-red-500 mt-1">{{ errors.name }}</p>
              </div>

              <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                <input v-model="localUser.email" type="email" class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all" :class="{ 'border-red-300 bg-red-50': errors.email }" placeholder="john@company.com" @input="clearError('email')" />
                <p v-if="errors.email" class="text-xs text-red-500 mt-1">{{ errors.email }}</p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                <input v-model="localUser.phone" type="tel" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all" placeholder="+62 812-3456-7890" />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">User ID</label>
                <input :value="localUser.id" type="text" disabled class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-2xl text-sm text-gray-500 cursor-not-allowed" />
              </div>
            </div>
          </div>

          <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Account Settings</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                <select v-model="localUser.role" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all capitalize">
                  <option value="">Select a role...</option>
                  <option value="Admin">Admin</option>
                  <option value="employee">Employee</option>
                  <option value="User">User</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Account Status <span class="text-red-500">*</span></label>
                <select v-model="localUser.status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all">
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm sticky top-24">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Actions</h2>
            <div class="space-y-3">
              <button @click="saveUser" :disabled="isSaving" class="w-full py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-all shadow-lg shadow-red-600/25 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg v-if="isSaving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                {{ isSaving ? 'Saving...' : 'Save Changes' }}
              </button>
              
              <button @click="showDeleteModal = true" class="w-full py-3 bg-white border-2 border-red-200 text-red-600 rounded-2xl text-sm font-semibold hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                Delete User
              </button>
            </div>
          </div>

          <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl p-6 shadow-lg text-white">
            <h3 class="text-sm font-semibold text-gray-300 mb-4">User Statistics</h3>
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-400">Tickets Created</span>
                <span class="text-lg font-bold">{{ userStats.ticketsCreated }}</span>
              </div>
              <div class="flex items-center justify-between" v-if="localUser.role === 'employee' || localUser.role === 'Agent' || localUser.role === 'Admin'">
                <span class="text-sm text-gray-400">Tickets Assigned</span>
                <span class="text-lg font-bold">{{ userStats.ticketsAssigned }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-400">Last Login</span>
                <span class="text-sm font-semibold text-gray-200">{{ userStats.lastLogin }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl transform transition-all scale-100">
          <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Delete User?</h3>
            <p class="text-sm text-gray-500 mt-2">Are you sure you want to delete <span class="font-semibold text-gray-800">{{ localUser.name }}</span>? This action cannot be undone.</p>
          </div>
          <div class="flex gap-3">
            <button @click="showDeleteModal = false" class="flex-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
            <button @click="confirmDelete" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-colors shadow-lg shadow-red-600/25">Yes, Delete</button>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-12">
      <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm inline-block">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <p class="text-gray-500 font-medium">User not found</p>
        <button @click="router.push('/users')" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors">Back to Users</button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/layout/AppLayout.vue'
import Notification from '../../components/common/Notification.vue'
import { useNotification } from '../../composables/useNotification'
import { useAdminStore } from '../../stores/adminStore'

const route = useRoute()
const router = useRouter()
const adminStore = useAdminStore()
const { notificationRef, success, error } = useNotification()

const userId = route.params.id

const user = computed(() => 
  adminStore.users.find(u => String(u.id) === String(userId))
)

const localUser = ref({
  id: 0,
  name: '',
  email: '',
  phone: '',
  role: 'User',
  status: 'Active'
})

watch(user, (newUser) => {
  if (newUser) {
    localUser.value = { ...newUser }
  }
}, { immediate: true })

onMounted(async () => {
  if (adminStore.users.length === 0) {
    await adminStore.fetchData()
  }
})

const showDeleteModal = ref(false)
const errors = ref({})
const isSaving = ref(false)

const userStats = computed(() => {
  if (!localUser.value.name) return { ticketsCreated: 0, ticketsAssigned: 0, lastLogin: 'N/A' }
  
  const ticketsCreated = adminStore.tickets.filter(t => t.createdBy === localUser.value.name).length
  const ticketsAssigned = adminStore.tickets.filter(t => t.assignedTo === localUser.value.name).length
  
  return {
    ticketsCreated,
    ticketsAssigned,
    lastLogin: '2 hours ago'
  }
})

function clearError(field) {
  if (errors.value[field]) errors.value[field] = ''
}

function validateForm() {
  errors.value = {}
  let isValid = true

  if (!localUser.value.name.trim()) {
    errors.value.name = 'Full name is required'
    isValid = false
  }

  if (!localUser.value.email.trim()) {
    errors.value.email = 'Email address is required'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(localUser.value.email)) {
    errors.value.email = 'Please enter a valid email address'
    isValid = false
  }

  return isValid
}

async function saveUser() {
  if (!validateForm()) {
    error('Please fix the errors in the form.', 'Validation Failed')
    return
  }

  isSaving.value = true
  
  try {
    await adminStore.updateUser(localUser.value.id, {
      name: localUser.value.name,
      email: localUser.value.email,
      phone: localUser.value.phone,
      role: localUser.value.role,
      status: localUser.value.status
    })
    
    success('User profile has been updated successfully!', 'Update Successful', 4000)
    
    await new Promise(resolve => setTimeout(resolve, 1000))
    router.push('/users') 
  } catch (err) {
    console.error('Update error:', err)
    error('Failed to update user. Please try again.', 'Update Failed')
  } finally {
    isSaving.value = false
  }
}

async function confirmDelete() {
  try {
    await adminStore.deleteUser(userId)
    
    success('User has been permanently deleted.', 'Deleted Successfully', 4000)
    showDeleteModal.value = false
    
    await new Promise(resolve => setTimeout(resolve, 1000))
    router.push('/users') 
  } catch (err) {
    console.error('Delete error:', err)
    error('Failed to delete user. Please try again.', 'Delete Failed')
  }
}
</script>