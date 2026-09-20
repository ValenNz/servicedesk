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
          <h1 class="text-2xl font-bold text-gray-800">Create New Ticket</h1>
          <p class="text-sm text-gray-500 mt-1">Tell us about your issue and we'll help you resolve it</p>
        </div>
      </div>

      <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="text-sm text-blue-800">
          <p class="font-semibold mb-1">How it works:</p>
          <ul class="list-disc list-inside text-xs text-blue-700 space-y-0.5">
            <li>Fill in the details about your issue below</li>
            <li>Our team will review and assign it to the right agent</li>
            <li>You'll receive updates via comments on this ticket</li>
          </ul>
        </div>
      </div>

      <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
        <form @submit.prevent="submitTicket" class="space-y-6">
          
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Ticket Title <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="formData.title" 
              type="text" 
              placeholder="e.g., Cannot login to my email account"
              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
              :class="{ 'border-red-300 bg-red-50': errors.title }"
              @input="clearError('title')"
            />
            <p v-if="errors.title" class="text-xs text-red-500 mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
              {{ errors.title }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Brief summary of your issue (5-100 characters)</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Category <span class="text-red-500">*</span>
              </label>
              <select 
                v-model="formData.category" 
                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
                :class="{ 'border-red-300 bg-red-50': errors.category }"
                @change="clearError('category')"
              >
                <option value="">-- Select a category --</option>
                <option v-for="cat in store.categories" :key="cat.id" :value="cat.name">
                  {{ cat.name }}
                </option>
              </select>
              <p v-if="errors.category" class="text-xs text-red-500 mt-1 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                {{ errors.category }}
              </p>
              <p class="text-xs text-gray-400 mt-1">Choose the category that best fits your issue</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Priority Level <span class="text-red-500">*</span>
              </label>
              <div class="grid grid-cols-3 gap-2">
                <button 
                  v-for="priority in priorities" 
                  :key="priority.value"
                  type="button"
                  @click="formData.priority = priority.value; clearError('priority')"
                  class="px-3 py-2.5 rounded-xl text-xs font-semibold border-2 transition-all flex flex-col items-center gap-1"
                  :class="formData.priority === priority.value 
                    ? priority.activeClass 
                    : 'bg-white border-gray-200 text-gray-600 hover:border-gray-300'"
                >
                  <span>{{ priority.label }}</span>
                </button>
              </div>
              <p v-if="errors.priority" class="text-xs text-red-500 mt-1 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                {{ errors.priority }}
              </p>
              <div class="mt-2 p-3 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-xs font-medium text-gray-700 mb-1">Priority Guide:</p>
                <ul class="text-xs text-gray-600 space-y-0.5">
                  <li><span class="font-semibold text-green-600">Low</span> - Minor issue, not urgent</li>
                  <li><span class="font-semibold text-yellow-600">Medium</span> - Affects work but not critical</li>
                  <li><span class="font-semibold text-red-600">High</span> - Cannot work, needs immediate attention</li>
                </ul>
              </div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Description <span class="text-red-500">*</span>
            </label>
            <textarea 
              v-model="formData.description" 
              rows="5" 
              placeholder="Please describe your issue in detail. Include any steps you've already tried, error messages you received, or other relevant information..."
              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all resize-none"
              :class="{ 'border-red-300 bg-red-50': errors.description }"
              @input="clearError('description')"
            ></textarea>
            <div class="flex items-center justify-between mt-1">
              <p v-if="errors.description" class="text-xs text-red-500 flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                {{ errors.description }}
              </p>
              <p class="text-xs text-gray-400 ml-auto">{{ formData.description.length }} characters</p>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Attachment <span class="text-xs font-normal text-gray-400">(Optional)</span>
            </label>
            <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center hover:border-red-300 transition-colors">
              <input 
                type="file" 
                ref="fileInput"
                class="hidden" 
                accept=".pdf,.jpg,.jpeg,.png,.docx" 
                @change="handleFileUpload" 
              />
              
              <div v-if="!selectedFile" @click="$refs.fileInput.click()" class="cursor-pointer">
                <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <p class="text-sm font-medium text-gray-700">Click to upload or drag and drop</p>
                <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG, DOCX (Max 5MB)</p>
              </div>
              
              <div v-else class="flex items-center gap-3 p-3 bg-blue-50 rounded-xl border border-blue-100">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0 text-left">
                  <p class="text-sm font-medium text-gray-800 truncate">{{ selectedFile.name }}</p>
                  <p class="text-xs text-gray-500">{{ (selectedFile.size / 1024).toFixed(1) }} KB</p>
                </div>
                <button 
                  type="button"
                  @click="removeSelectedFile" 
                  class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                  title="Remove file"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>
            <p v-if="errors.file" class="text-xs text-red-500 mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
              {{ errors.file }}
            </p>
          </div>

          <div v-if="formData.title || formData.description" class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              Preview
            </h3>
            
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
              <div class="flex items-start justify-between mb-3">
                <div>
                  <h4 class="text-lg font-bold text-gray-800 mb-1">{{ formData.title || 'Your ticket title' }}</h4>
                  <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-500">{{ formData.category || 'No category' }}</span>
                    <span v-if="formData.priority" class="text-xs px-2 py-0.5 rounded-full font-medium"
                      :class="{
                        'bg-green-100 text-green-700': formData.priority === 'Low',
                        'bg-yellow-100 text-yellow-700': formData.priority === 'Medium',
                        'bg-red-100 text-red-700': formData.priority === 'High'
                      }">
                      {{ formData.priority }}
                    </span>
                  </div>
                </div>
              </div>
              <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ formData.description || 'Your description will appear here...' }}</p>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
              </svg>
              {{ isSubmitting ? 'Submitting...' : 'Submit Ticket' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AppLayout from '../../components/layout/AppLayout.vue'
import Notification from '../../components/common/Notification.vue'
import { useNotification } from '../../composables/useNotification'
import { useUserStore } from '../../stores/userStore'

const router = useRouter()
const store = useUserStore()
const { notificationRef, success, error } = useNotification()

const formData = ref({
  title: '',
  description: '',
  category: '',
  priority: ''
})

const selectedFile = ref(null)
const fileInput = ref(null)
const errors = ref({})
const isSubmitting = ref(false)

const priorities = [
  { 
    value: 'Low', 
    label: 'Low', 
    activeClass: 'bg-green-50 border-green-500 text-green-700' 
  },
  { 
    value: 'Medium', 
    label: 'Medium', 
    activeClass: 'bg-yellow-50 border-yellow-500 text-yellow-700' 
  },
  { 
    value: 'High', 
    label: 'High', 
    activeClass: 'bg-red-50 border-red-500 text-red-700' 
  }
]

onMounted(async () => {
  if (store.categories.length === 0) {
    await store.fetchData()
  }
})

function clearError(field) {
  if (errors.value[field]) {
    errors.value[field] = ''
  }
}

function validateForm() {
  errors.value = {}
  let isValid = true

  if (!formData.value.title.trim()) {
    errors.value.title = 'Please enter a ticket title'
    isValid = false
  } else if (formData.value.title.trim().length < 5) {
    errors.value.title = 'Title must be at least 5 characters'
    isValid = false
  } else if (formData.value.title.trim().length > 100) {
    errors.value.title = 'Title must not exceed 100 characters'
    isValid = false
  }

  if (!formData.value.category) {
    errors.value.category = 'Please select a category'
    isValid = false
  }

  if (!formData.value.priority) {
    errors.value.priority = 'Please select a priority level'
    isValid = false
  }

  if (!formData.value.description.trim()) {
    errors.value.description = 'Please describe your issue'
    isValid = false
  } else if (formData.value.description.trim().length < 10) {
    errors.value.description = 'Description must be at least 10 characters'
    isValid = false
  }

  return isValid
}

function handleFileUpload(event) {
  const file = event.target.files[0]
  if (!file) return

  const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']
  const maxSize = 5 * 1024 * 1024

  if (!allowedTypes.includes(file.type)) {
    errors.value.file = 'Invalid file type. Allowed: PDF, JPG, PNG, DOCX'
    selectedFile.value = null
    event.target.value = ''
    return
  }

  if (file.size > maxSize) {
    errors.value.file = 'File size exceeds 5MB limit'
    selectedFile.value = null
    event.target.value = ''
    return
  }

  errors.value.file = ''
  selectedFile.value = file
}

function removeSelectedFile() {
  selectedFile.value = null
  errors.value.file = ''
  if (fileInput.value) fileInput.value.value = ''
}

async function submitTicket() {
  if (!validateForm()) {
    error('Please fix the errors in the form.', 'Validation Failed')
    return
  }

  isSubmitting.value = true

  try {
    const ticketData = {
      title: formData.value.title.trim(),
      description: formData.value.description.trim(),
      category: formData.value.category,
      priority: formData.value.priority,
      file: selectedFile.value ? {
        name: selectedFile.value.name,
        size: (selectedFile.value.size / 1024).toFixed(1) + ' KB',
        type: selectedFile.value.type,
        url: URL.createObjectURL(selectedFile.value)
      } : null
    }

    const created = await store.addTicket(ticketData)
    
    success('Your ticket has been submitted successfully! An agent will review it soon.', 'Ticket Created', 5000)
    
    setTimeout(() => {
      router.push(`/tickets/${created.id}`)
    }, 1500)
  } catch (err) {
    console.error('Create ticket error:', err)
    error('Failed to create ticket. Please try again.', 'Error')
  } finally {
    isSubmitting.value = false
  }
}
</script>