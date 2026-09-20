<template>
  <AppLayout>
    <Notification ref="notificationRef" />
    
    <div v-if="ticket" class="space-y-6">
      <div class="flex items-start gap-4">
        <button @click="router.back()" class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200 mt-1">
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <div class="flex-1">
          <div class="flex flex-wrap items-center gap-3 mb-2">
            <h1 class="text-2xl font-bold text-gray-800">{{ ticket.id }}</h1>
            <StatusBadge :status="ticket.status" />
            <PriorityBadge :priority="ticket.priority" />
          </div>
          <p class="text-sm text-gray-500">{{ ticket.title }}</p>
        </div>
      </div>

      <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-gray-800 mb-6">Ticket Progress</h3>
        <div class="relative mb-8">
          <div class="absolute top-1/2 left-0 w-full h-1.5 bg-gray-100 -translate-y-1/2 rounded-full"></div>
          <div 
            class="absolute top-1/2 left-0 h-1.5 bg-blue-500 -translate-y-1/2 rounded-full transition-all duration-500"
            :style="{ width: progressPercentage + '%' }"
          ></div>
          
          <div class="relative flex justify-between">
            <div v-for="(step, index) in steps" :key="step.id" class="flex flex-col items-center">
              <div 
                class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-4 transition-all duration-300 z-10"
                :class="currentStep >= index + 1 ? 'bg-blue-500 border-blue-100 text-white shadow-lg shadow-blue-500/30' : 'bg-white border-gray-200 text-gray-400'"
              >
                <svg v-if="currentStep > index + 1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
                <span v-else>{{ index + 1 }}</span>
              </div>
              <span class="mt-3 text-xs font-semibold" :class="currentStep >= index + 1 ? 'text-blue-600' : 'text-gray-400'">
                {{ step.label }}
              </span>
            </div>
          </div>
        </div>
        
        <div class="p-4 rounded-2xl border" :class="statusMessage.bgClass">
          <div class="flex items-start gap-3">
            <div class="mt-0.5 flex-shrink-0" :class="statusMessage.iconClass">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="statusMessage.icon" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-bold" :class="statusMessage.textClass">{{ statusMessage.title }}</p>
              <p class="text-xs mt-1 leading-relaxed" :class="statusMessage.textClass">{{ statusMessage.description }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
          <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-lg font-bold text-gray-800">Issue Details</h2>
            </div>
            
            <div class="mb-6">
              <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                <p class="text-xs font-semibold text-gray-400 uppercase">Description</p>
              </div>
              <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-2xl border border-gray-100 whitespace-pre-wrap">{{ ticket.description }}</p>
            </div>

            <div v-if="ticket.file || (ticket.attachments && ticket.attachments.length > 0)">
              <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                <p class="text-xs font-semibold text-gray-400 uppercase">Attachments</p>
              </div>
              
              <div v-if="ticket.file" class="bg-gray-50 rounded-2xl p-4 border border-gray-100 mb-3">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ ticket.file.name }}</p>
                    <p class="text-xs text-gray-500">{{ ticket.file.size }}</p>
                  </div>
                  <button @click="downloadFile(ticket.file.url, ticket.file.name)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Download">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                  </button>
                </div>
              </div>
              
              <div v-if="ticket.attachments && ticket.attachments.length > 0" class="space-y-3">
                <div v-for="(file, index) in ticket.attachments" :key="index" class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                      <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-medium text-gray-800 truncate">{{ file.name }}</p>
                      <p class="text-xs text-gray-500">{{ file.size }}</p>
                    </div>
                    <button @click="downloadFile(file.url, file.name)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Download">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Conversation History</h2>
            
            <div class="space-y-6">
              <div class="flex gap-4">
                <div class="flex flex-col items-center">
                  <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                  </div>
                  <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
                </div>
                <div class="flex-1 pb-6">
                  <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
                    <p class="text-sm font-semibold text-blue-900">Ticket Submitted</p>
                    <p class="text-xs text-blue-700 mt-1">By {{ ticket.createdBy }} • {{ formatDate(ticket.createdAt) }}</p>
                  </div>
                </div>
              </div>

              <div v-for="(comment, index) in (ticket.comments || [])" :key="index" class="flex gap-4">
                <div class="flex flex-col items-center">
                  <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                    :class="comment.author === userName ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'">
                    <span class="text-xs font-bold">{{ comment.author.charAt(0) }}</span>
                  </div>
                  <div v-if="index < (ticket.comments || []).length - 1" class="w-0.5 h-full bg-gray-200 mt-2"></div>
                </div>
                <div class="flex-1 pb-6">
                  <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                    <div class="flex items-center justify-between mb-2">
                      <div class="flex items-center gap-2">
                        <span class="text-sm font-semibold text-gray-800">{{ comment.author }}</span>
                        <span v-if="comment.author === userName" class="text-[10px] px-1.5 py-0.5 bg-red-100 text-red-600 rounded-md font-medium">You</span>
                      </div>
                      <span class="text-xs text-gray-400">{{ comment.time }}</span>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ comment.text }}</p>
                    
                    <div v-if="comment.attachment" @click="downloadFile(comment.attachment.url, comment.attachment.name)" class="mt-3 flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-200 w-max hover:border-red-200 hover:bg-red-50 transition-colors cursor-pointer">
                      <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                      </div>
                      <div>
                        <p class="text-xs font-medium text-gray-800">{{ comment.attachment.name }}</p>
                        <p class="text-[10px] text-gray-500">{{ comment.attachment.size }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100">
              <label class="block text-sm font-semibold text-gray-700 mb-3">Add a Comment</label>
              <div class="flex gap-3">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                  {{ userAvatar }}
                </div>
                <div class="flex-1">
                  <textarea
                    v-model="newComment"
                    rows="3"
                    placeholder="Type your reply or add more details here..."
                    class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 resize-none transition-all"
                    :class="{ 'border-red-300 bg-red-50': errors.comment }"
                    @keydown.meta.enter="addComment"
                    @keydown.ctrl.enter="addComment"
                  ></textarea>
                  <p v-if="errors.comment" class="text-xs text-red-500 mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    {{ errors.comment }}
                  </p>
                  
                  <div class="mt-3 flex items-center gap-3 flex-wrap">
                    <label class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-100 hover:border-gray-300 cursor-pointer transition-all">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                      </svg>
                      Attach File
                      <input type="file" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.docx" @change="handleFileUpload" />
                    </label>
                    
                    <div v-if="selectedFile" class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium border border-blue-100 animate-fade-in">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                      </svg>
                      {{ selectedFile.name }}
                      <button @click="removeSelectedFile" class="ml-1 hover:text-blue-900 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                      </button>
                    </div>
                  </div>
                  <p v-if="errors.file" class="text-xs text-red-500 mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    {{ errors.file }}
                  </p>

                  <div class="flex justify-between items-center mt-4">
                    <p class="text-xs text-gray-400">Max 5MB • PDF, JPG, PNG, DOCX</p>
                    <button @click="addComment" :disabled="isSubmitting" class="px-5 py-2.5 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 shadow-lg shadow-red-600/20">
                      <svg v-if="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                      </svg>
                      {{ isSubmitting ? 'Sending...' : 'Send Reply' }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm sticky top-24">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Ticket Details</h2>
            <div class="space-y-5">
              <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Ticket ID</label>
                <p class="text-sm font-mono font-semibold text-gray-800">{{ ticket.id }}</p>
              </div>

              <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Category</label>
                <p class="text-sm font-medium text-gray-800">{{ ticket.category }}</p>
              </div>

              <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Priority</label>
                <PriorityBadge :priority="ticket.priority" />
              </div>

              <div>
                <label class="block text-xs font-semibold text-gray-400 uppercase mb-1.5">Created On</label>
                <p class="text-sm font-medium text-gray-800">{{ formatDate(ticket.createdAt) }}</p>
              </div>

              <div class="pt-5 border-t border-gray-100">
                 <p class="text-xs text-gray-500 text-center leading-relaxed">
                   Need to add more information?<br>
                   Use the comment section below.
                 </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-12">
      <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm inline-block">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <p class="text-gray-500 font-medium">Ticket not found</p>
        <button @click="router.push('/tickets')" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors">
          Back to Tickets
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/layout/AppLayout.vue'
import StatusBadge from '../../components/common/StatusBadge.vue'
import PriorityBadge from '../../components/common/PriorityBadge.vue'
import Notification from '../../components/common/Notification.vue'
import { useNotification } from '../../composables/useNotification'
import { useUserStore } from '../../stores/userStore'

const route = useRoute()
const router = useRouter()
const userStore = useUserStore()
const { notificationRef, success, error, warning } = useNotification()

const ticketId = route.params.id
const userName = localStorage.getItem('userName') || 'User'
const userAvatar = userName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()

const ticket = computed(() => userStore.getTicketById(ticketId))

const newComment = ref('')
const selectedFile = ref(null)
const errors = ref({})
const isSubmitting = ref(false)

const steps = [
  { id: 'open', label: 'Submitted' },
  { id: 'in_progress', label: 'In Progress' },
  { id: 'resolved', label: 'Resolved' },
  { id: 'closed', label: 'Closed' }
]

const currentStep = computed(() => {
  if (!ticket.value) return 1
  const status = ticket.value.status
  if (status === 'Open') return 1
  if (status === 'In Progress') return 2
  if (status === 'Resolved') return 3
  if (status === 'Closed') return 4
  return 1
})

const progressPercentage = computed(() => {
  return ((currentStep.value - 1) / (steps.length - 1)) * 100
})

const statusMessage = computed(() => {
  if (!ticket.value) return {}
  const status = ticket.value.status
  switch (status) {
    case 'Open':
      return {
        title: 'Ticket Received',
        description: 'Your ticket has been successfully submitted. Our support team will review it and assign an agent shortly.',
        bgClass: 'bg-blue-50 border-blue-100',
        textClass: 'text-blue-800',
        iconClass: 'text-blue-600',
        icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
      }
    case 'In Progress':
      return {
        title: 'Agent is Working on It',
        description: `Assigned to: ${ticket.value.assignedTo || 'Support Team'}. They are currently investigating your issue.`,
        bgClass: 'bg-yellow-50 border-yellow-100',
        textClass: 'text-yellow-800',
        iconClass: 'text-yellow-600',
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
      }
    case 'Resolved':
      return {
        title: 'Issue Resolved',
        description: 'The agent has marked this ticket as resolved. Please check if your issue is fixed. If not, add a comment below.',
        bgClass: 'bg-green-50 border-green-100',
        textClass: 'text-green-800',
        iconClass: 'text-green-600',
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
      }
    case 'Closed':
      return {
        title: 'Ticket Closed',
        description: 'This ticket has been closed. Thank you for using our support service!',
        bgClass: 'bg-gray-50 border-gray-100',
        textClass: 'text-gray-800',
        iconClass: 'text-gray-600',
        icon: 'M5 13l4 4L19 7'
      }
    default:
      return {}
  }
})

function formatDate(dateString) {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
}

function downloadFile(url, filename) {
  if (!url) {
    warning('File URL not available', 'Download Failed')
    return
  }
  const link = document.createElement('a')
  link.href = url
  link.download = filename
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  success(`Downloading ${filename}...`, 'Download Started')
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
  const fileInput = document.querySelector('input[type="file"]')
  if (fileInput) fileInput.value = ''
}

async function addComment() {
  errors.value.comment = ''
  errors.value.file = ''

  if (!newComment.value.trim() && !selectedFile.value) {
    errors.value.comment = 'Please enter a comment or attach a file as proof.'
    return
  }

  isSubmitting.value = true

  try {
    const commentData = {
      text: newComment.value,
      attachment: selectedFile.value ? {
        name: selectedFile.value.name,
        size: (selectedFile.value.size / 1024).toFixed(1) + ' KB',
        type: selectedFile.value.type,
        url: URL.createObjectURL(selectedFile.value)
      } : null
    }

    await userStore.addComment(ticketId, commentData)

    newComment.value = ''
    removeSelectedFile()
    success('Comment added successfully!', 'Success')
  } catch (err) {
    console.error('Add comment error:', err)
    error('Failed to add comment.', 'Error')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
  animation: fade-in 0.2s ease-out forwards;
}
</style>  