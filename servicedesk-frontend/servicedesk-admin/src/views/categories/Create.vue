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
          <h1 class="text-2xl font-bold text-gray-800">Create New Category</h1>
          <p class="text-sm text-gray-500 mt-1">Add a new category to organize your tickets</p>
        </div>
      </div>

      <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
        <form @submit.prevent="submitCategory" class="space-y-6">
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Category Name <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="formData.name" 
              type="text" 
              placeholder="e.g., Network, Software, Hardware"
              class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
              :class="{ 'border-red-300 bg-red-50': errors.name }"
              @input="clearError('name')"
            />
            <p v-if="errors.name" class="text-xs text-red-500 mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
              {{ errors.name }}
            </p>
            <p class="text-xs text-gray-400 mt-1">This name will appear in ticket filters and dropdowns</p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Description <span class="text-red-500">*</span>
            </label>
            <textarea 
              v-model="formData.description" 
              rows="4" 
              placeholder="Briefly describe what types of issues belong to this category..."
              class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all resize-none"
              :class="{ 'border-red-300 bg-red-50': errors.description }"
              @input="clearError('description')"
            ></textarea>
            <p v-if="errors.description" class="text-xs text-red-500 mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
              {{ errors.description }}
            </p>
          </div>

          <div v-if="formData.name || formData.description" class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              Live Preview
            </h3>
            
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
              <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center">
                  <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                  </svg>
                </div>
              </div>
              <h4 class="text-lg font-bold text-gray-800 mb-1">{{ formData.name || 'Category Name' }}</h4>
              <p class="text-sm text-gray-500 mb-4">{{ formData.description || 'Category description will appear here...' }}</p>
              <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Tickets</span>
                <span class="text-sm font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full">0</span>
              </div>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              {{ isSubmitting ? 'Creating Category...' : 'Create Category' }}
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
  description: ''
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
    errors.value.name = 'Category name is required'
    isValid = false
  } else if (formData.value.name.trim().length < 3) {
    errors.value.name = 'Category name must be at least 3 characters'
    isValid = false
  } else {
    const exists = adminStore.categories.some(c => 
      c.name.toLowerCase() === formData.value.name.trim().toLowerCase()
    )
    if (exists) {
      errors.value.name = 'Category name already exists'
      isValid = false
    }
  }

  if (!formData.value.description.trim()) {
    errors.value.description = 'Description is required'
    isValid = false
  } else if (formData.value.description.trim().length < 10) {
    errors.value.description = 'Description must be at least 10 characters'
    isValid = false
  }

  return isValid
}

async function submitCategory() {
  if (!validateForm()) {
    error('Please fix the errors in the form.', 'Validation Failed')
    return
  }

  isSubmitting.value = true

  try {
    await adminStore.addCategory({
      name: formData.value.name.trim(),
      description: formData.value.description.trim()
    })
    
    success('Category has been created successfully!', 'Success')
    
    router.push('/categories')
  } catch (err) {
    console.error('Create category error:', err)
    error('Failed to create category. Please try again.', 'Error')
  } finally {
    isSubmitting.value = false
  }
}
</script>