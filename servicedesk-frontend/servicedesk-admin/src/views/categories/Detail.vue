<template>
  <AppLayout>
    <Notification ref="notificationRef" />

    <div v-if="category" class="max-w-3xl mx-auto space-y-6">
      <div class="flex items-center gap-4">
        <button @click="router.back()" class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Edit Category</h1>
          <p class="text-sm text-gray-500 mt-1">Update category information and details</p>
        </div>
      </div>

      <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
            </svg>
          </div>
          <h2 class="text-lg font-bold text-gray-800">Category Details</h2>
        </div>
        
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Category Name <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="localCategory.name" 
              type="text" 
              class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
              :class="{ 'border-red-300 bg-red-50': errors.name }"
              placeholder="e.g. Network Issues"
              @input="clearError('name')"
            />
            <p v-if="errors.name" class="text-xs text-red-500 mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              {{ errors.name }}
            </p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Description <span class="text-red-500">*</span>
            </label>
            <textarea 
              v-model="localCategory.description" 
              rows="4"
              class="w-full px-4 py-3 bg-gray-50 border rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all resize-none"
              :class="{ 'border-red-300 bg-red-50': errors.description }"
              placeholder="Briefly describe what this category is used for..."
              @input="clearError('description')"
            ></textarea>
            <p v-if="errors.description" class="text-xs text-red-500 mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              {{ errors.description }}
            </p>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Total Tickets</label>
            <input 
              :value="localCategory.ticketCount" 
              type="text" 
              disabled
              class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-2xl text-sm text-gray-500 cursor-not-allowed"
            />
            <p class="text-xs text-gray-400 mt-1">This number updates automatically based on assigned tickets.</p>
          </div>
        </div>

        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
          <button 
            @click="router.back()"
            class="px-6 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors"
          >
            Cancel
          </button>
          <button 
            @click="saveCategory"
            :disabled="isSaving"
            class="px-6 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-all shadow-lg shadow-red-600/25 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg v-if="isSaving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ isSaving ? 'Saving...' : 'Save Changes' }}
          </button>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-12">
      <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm inline-block">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
        </svg>
        <p class="text-gray-500 font-medium">Category not found</p>
        <button @click="router.push('/categories')" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors">
          Back to Categories
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/layout/AppLayout.vue'
import Notification from '../../components/common/Notification.vue'
import { useNotification } from '../../composables/useNotification'
import { useAdminStore } from '../../stores/adminStore'

const route = useRoute()
const router = useRouter()
const adminStore = useAdminStore()
const { notificationRef, success, error } = useNotification()

const categoryId = route.params.id
const category = computed(() => 
  adminStore.categories.find(c => String(c.id) === String(categoryId))
)

const localCategory = ref({
  id: 0,
  name: '',
  description: '',
  ticketCount: 0
})

watch(category, (newCategory) => {
  if (newCategory) {
    localCategory.value = { ...newCategory }
  }
}, { immediate: true })

const errors = ref({})
const isSaving = ref(false)

function clearError(field) {
  if (errors.value[field]) errors.value[field] = ''
}

function validateForm() {
  errors.value = {}
  let isValid = true

  if (!localCategory.value.name.trim()) {
    errors.value.name = 'Category name is required'
    isValid = false
  }

  if (!localCategory.value.description.trim()) {
    errors.value.description = 'Description is required'
    isValid = false
  }

  return isValid
}

async function saveCategory() {
  if (!validateForm()) {
    error('Please fix the errors in the form.', 'Validation Failed')
    return
  }

  isSaving.value = true
  
  try {
    await adminStore.updateCategory(localCategory.value.id, {
      name: localCategory.value.name,
      description: localCategory.value.description
    })
    
    success('Category has been updated successfully!', 'Update Successful', 4000)
    
    await new Promise(resolve => setTimeout(resolve, 1000))
    router.push('/categories') 
  } catch (err) {
    console.error('Update error:', err)
    error('Failed to update category. Please try again.', 'Update Failed')
  } finally {
    isSaving.value = false
  }
}
</script>