<template>
  <AppLayout>
    <Notification ref="notificationRef" />
    
    <div v-if="ticket" class="space-y-6">
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="flex items-center gap-4">
          <button @click="router.back()" class="p-2.5 rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <div>
            <div class="flex items-center gap-3">
              <h1 class="text-2xl font-bold text-gray-800">{{ ticket.id }}</h1>
              <StatusBadge :status="ticket.status" />
              <PriorityBadge :priority="ticket.priority" />
            </div>
            <p class="text-sm text-gray-500 mt-1">{{ ticket.title }}</p>
          </div>
        </div>
        
        <div class="flex items-center gap-2">
          <button 
            v-if="ticket.status !== 'Resolved' && ticket.status !== 'Closed'"
            @click="quickAction('resolve')"
            class="px-4 py-2.5 bg-green-600 text-white rounded-xl text-sm font-semibold hover:bg-green-700 transition-all shadow-lg shadow-green-600/25 flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Resolve
          </button>
          <button 
            v-if="ticket.status === 'Resolved' && !isValidated"
            @click="showValidationModal = true"
            class="px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/25 flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Validate
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2 space-y-6">
          <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
              <h2 class="text-lg font-bold text-gray-800">Ticket Information</h2>
              <span class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold">
                Created {{ ticket.timeAgo }}
              </span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
              <div class="bg-gray-50 rounded-2xl p-4">
                <div class="flex items-center gap-2 mb-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                  </svg>
                  <p class="text-xs font-semibold text-gray-400 uppercase">Category</p>
                </div>
                <p class="text-sm font-semibold text-gray-800">{{ ticket.category }}</p>
              </div>
              
              <div class="bg-gray-50 rounded-2xl p-4">
                <div class="flex items-center gap-2 mb-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  <p class="text-xs font-semibold text-gray-400 uppercase">Created By</p>
                </div>
                <p class="text-sm font-semibold text-gray-800">{{ ticket.createdBy }}</p>
              </div>
              
              <div class="bg-gray-50 rounded-2xl p-4">
                <div class="flex items-center gap-2 mb-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <p class="text-xs font-semibold text-gray-400 uppercase">Created At</p>
                </div>
                <p class="text-sm font-semibold text-gray-800">{{ formatDate(ticket.createdAt) }}</p>
              </div>
              
              <div class="bg-gray-50 rounded-2xl p-4">
                <div class="flex items-center gap-2 mb-2">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <p class="text-xs font-semibold text-gray-400 uppercase">Last Updated</p>
                </div>
                <p class="text-sm font-semibold text-gray-800">{{ formatDate(ticket.updatedAt) }}</p>
              </div>
            </div>

            <div class="mb-6">
              <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                <p class="text-xs font-semibold text-gray-400 uppercase">Description</p>
              </div>
              <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-2xl border border-gray-100">{{ ticket.description }}</p>
            </div>

            <div v-if="ticket.file || (ticket.attachments && ticket.attachments.length > 0)">
              <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
                <p class="text-xs font-semibold text-gray-400 uppercase">File / Attachments</p>
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
            <h2 class="text-lg font-bold text-gray-800 mb-6">Activity Timeline</h2>
            
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
                  <p class="text-sm font-semibold text-gray-800">Ticket Created</p>
                  <p class="text-xs text-gray-500 mt-1">By {{ ticket.createdBy }} • {{ formatDate(ticket.createdAt) }}</p>
                </div>
              </div>

              <div v-for="(comment, index) in (ticket.comments || [])" :key="index" class="flex gap-4">
                <div class="flex flex-col items-center">
                  <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="text-xs font-bold text-green-600">{{ comment.author.charAt(0) }}</span>
                  </div>
                  <div v-if="index < (ticket.comments || []).length - 1" class="w-0.5 h-full bg-gray-200 mt-2"></div>
                </div>
                <div class="flex-1 pb-6">
                  <div class="bg-gray-50 rounded-2xl p-4">
                    <div class="flex items-center justify-between mb-2">
                      <span class="text-sm font-semibold text-gray-800">{{ comment.author }}</span>
                      <span class="text-xs text-gray-400">{{ comment.time }}</span>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ comment.text }}</p>
                    
                    <div v-if="comment.attachment" @click="downloadFile(comment.attachment.url, comment.attachment.name)" class="mt-3 flex items-center gap-3 p-3 bg-white rounded-xl border border-gray-100 w-max hover:border-red-200 transition-colors cursor-pointer">
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
              <label class="block text-sm font-semibold text-gray-700 mb-3">Add Comment or Internal Note</label>
              <div class="flex gap-3">
                <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                  {{ adminStore.currentUser?.avatar || 'U' }}
                </div>
                <div class="flex-1">
                  <textarea
                    v-model="newComment"
                    rows="3"
                    placeholder="Type your comment here..."
                    class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 resize-none"
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
                      Attach Proof / File
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
                    <button @click="addComment" :disabled="isSubmitting" class="px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 shadow-lg shadow-red-600/20">
                      <svg v-if="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                      </svg>
                      {{ isSubmitting ? 'Uploading...' : 'Send Comment' }}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="space-y-6">
          <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm sticky top-24">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Ticket Management</h2>
            <div class="space-y-5">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Current Status <span class="text-xs font-normal text-gray-400 ml-2">(Required)</span></label>
                <select v-model="localTicket.status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all" :class="{ 'border-red-300': errors.status }">
                  <option value="Open">Open</option>
                  <option value="In Progress">In Progress</option>
                  <option value="Resolved">Resolved</option>
                  <option value="Closed">Closed</option>
                </select>
                <p v-if="errors.status" class="text-xs text-red-500 mt-1">{{ errors.status }}</p>
              </div>

              <div v-if="userRole === 'admin'">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Assign To <span class="text-xs font-normal text-gray-400 ml-2">(Optional)</span></label>
                <select v-model="localTicket.assignedTo" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all">
                  <option value="">-- Unassigned --</option>
                  <option v-for="emp in availableEmployees" :key="emp.id" :value="emp.name">
                    {{ emp.name }} ({{ getEmployeeWorkload(emp.name) }} active)
                  </option>
                </select>
              </div>

              <div v-else>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Assigned To</label>
                <div class="px-4 py-3 bg-gray-100 border border-gray-200 rounded-2xl text-sm text-gray-600">
                  {{ localTicket.assignedTo || 'Unassigned' }}
                </div>
                <p class="text-xs text-gray-400 mt-1">Only admins can reassign tickets</p>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Priority Level</label>
                <div class="grid grid-cols-3 gap-2">
                  <button v-for="priority in ['Low', 'Medium', 'High']" :key="priority" @click="localTicket.priority = priority" class="px-3 py-2 rounded-xl text-xs font-semibold border-2 transition-all" :class="localTicket.priority === priority ? (priority === 'High' ? 'bg-red-50 border-red-500 text-red-700' : priority === 'Medium' ? 'bg-yellow-50 border-yellow-500 text-yellow-700' : 'bg-green-50 border-green-500 text-green-700') : 'bg-white border-gray-200 text-gray-600 hover:border-gray-300'">
                    {{ priority }}
                  </button>
                </div>
              </div>

              <div v-if="localTicket.status === 'Resolved'" class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                <label class="flex items-start gap-3 cursor-pointer">
                  <input v-model="isValidated" type="checkbox" class="w-5 h-5 mt-0.5 rounded border-blue-300 text-blue-600 focus:ring-blue-500" />
                  <div>
                    <span class="text-sm font-semibold text-blue-800">Validate Resolution</span>
                    <p class="text-xs text-blue-600 mt-1">Confirm that the issue has been properly resolved before closing</p>
                  </div>
                </label>
              </div>

              <div class="pt-4 space-y-3">
                <button @click="updateTicket" class="w-full py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-all shadow-lg shadow-red-600/25 flex items-center justify-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                  Save Changes
                </button>
                
                <button v-if="userRole === 'admin'" @click="showDeleteConfirm = true" class="w-full py-3 bg-white border-2 border-red-200 text-red-600 rounded-2xl text-sm font-semibold hover:bg-red-50 transition-all flex items-center justify-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                  Delete Ticket
                </button>
              </div>
            </div>
          </div>

          <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-3xl p-6 shadow-lg text-white">
            <h3 class="text-sm font-semibold text-red-100 mb-4">Quick Stats</h3>
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <span class="text-sm text-red-100">Assignee Workload</span>
                <span class="text-lg font-bold">{{ localTicket.assignedTo ? getEmployeeWorkload(localTicket.assignedTo) + ' tickets' : 'Unassigned' }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-red-100">Comments</span>
                <span class="text-lg font-bold">{{ (ticket.comments || []).length }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="showValidationModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl">
          <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Validate Ticket</h3>
            <p class="text-sm text-gray-500 mt-2">Confirm that this ticket has been properly resolved</p>
          </div>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">Validation Notes (Optional)</label>
              <textarea v-model="validationNotes" rows="3" placeholder="Add any notes about the validation..." class="w-full px-4 py-3 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-300 resize-none"></textarea>
            </div>
            <div class="flex gap-3">
              <button @click="showValidationModal = false" class="flex-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
              <button @click="validateTicket" class="flex-1 px-4 py-3 bg-green-600 text-white rounded-2xl text-sm font-semibold hover:bg-green-700 transition-colors">Confirm</button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="showDeleteConfirm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl">
          <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Delete Ticket?</h3>
            <p class="text-sm text-gray-500 mt-2">This action cannot be undone. This will permanently delete the ticket and all associated comments.</p>
          </div>
          <div class="flex gap-3">
            <button @click="showDeleteConfirm = false" class="flex-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
            <button @click="confirmDelete" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-colors">Delete</button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import AppLayout from '../../components/layout/AppLayout.vue'
import StatusBadge from '../../components/common/StatusBadge.vue'
import PriorityBadge from '../../components/common/PriorityBadge.vue'
import Notification from '../../components/common/Notification.vue'
import { useNotification } from '../../composables/useNotification'
import { useAdminStore } from '../../stores/adminStore'

const route = useRoute()
const router = useRouter()
const adminStore = useAdminStore()
const { notificationRef, success, error, warning } = useNotification()

const ticketId = route.params.id
const userRole = localStorage.getItem('userRole') || 'admin'

const ticket = computed(() => adminStore.getTicketById(ticketId))
const localTicket = ref({})

watch(ticket, (newVal) => {
  if (newVal) {
    localTicket.value = { ...newVal }
  }
}, { immediate: true, deep: true })

const newComment = ref('')
const selectedFile = ref(null)
const isValidated = ref(false)
const showValidationModal = ref(false)
const showDeleteConfirm = ref(false)
const validationNotes = ref('')
const errors = ref({})
const isSubmitting = ref(false)

const availableEmployees = computed(() => {
  return adminStore.users.filter(u => 
    u.role === 'employee' || u.role === 'Agent'
  )
})

function getEmployeeWorkload(employeeName) {
  return adminStore.tickets.filter(t => 
    t.assignedTo === employeeName && 
    t.status !== 'Closed' && 
    t.status !== 'Resolved'
  ).length
}

function formatDate(dateString) {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })
}

function validateForm() {
  errors.value = {}
  let isValid = true
  if (!localTicket.value.status) {
    errors.value.status = 'Status is required'
    isValid = false
  }
  return isValid
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
    if (!localTicket.value.comments) localTicket.value.comments = []

    const fileUrl = selectedFile.value ? URL.createObjectURL(selectedFile.value) : null

    const newCommentObj = {
      author: adminStore.currentUser?.name || 'User',
      text: newComment.value,
      time: 'Just now',
      attachment: selectedFile.value ? {
        name: selectedFile.value.name,
        size: (selectedFile.value.size / 1024).toFixed(1) + ' KB',
        type: selectedFile.value.type,
        url: fileUrl
      } : null
    }

    localTicket.value.comments.push(newCommentObj)
    
    if (selectedFile.value) {
      if (!localTicket.value.attachments) localTicket.value.attachments = []
      localTicket.value.attachments.push({
        name: selectedFile.value.name,
        size: (selectedFile.value.size / 1024).toFixed(1) + ' KB',
        url: fileUrl
      })
    }

    await adminStore.updateTicket(ticketId, {
      comments: localTicket.value.comments,
      attachments: localTicket.value.attachments
    })

    newComment.value = ''
    removeSelectedFile()
    success('Comment and proof attached successfully!', 'Success')
  } catch (err) {
    error('Failed to add comment.', 'Error')
  } finally {
    isSubmitting.value = false
  }
}

function quickAction(action) {
  if (action === 'resolve') {
    localTicket.value.status = 'Resolved'
    isValidated.value = false
    updateTicket()
  }
}

async function updateTicket() {
  if (!validateForm()) {
    error('Please fill in all required fields', 'Validation Error')
    return
  }
  
  try {
    await adminStore.updateTicket(ticketId, {
      assignedTo: localTicket.value.assignedTo,
      status: localTicket.value.status,
      priority: localTicket.value.priority,
      comments: localTicket.value.comments,
      attachments: localTicket.value.attachments,
      validated: isValidated.value
    })
    
    success('Ticket has been updated successfully!', 'Update Successful', 4000)
    setTimeout(() => router.back(), 1000)
  } catch (err) {
    error('Failed to update ticket.', 'Error')
  }
}

async function validateTicket() {
  isValidated.value = true
  showValidationModal.value = false
  
  if (validationNotes.value.trim()) {
    if (!localTicket.value.comments) localTicket.value.comments = []
    localTicket.value.comments.push({
      author: `${adminStore.currentUser?.name || 'Admin'} (Validation)`,
      text: ` Validated: ${validationNotes.value}`,
      time: 'Just now'
    })
  }
  
  success('Ticket validated and resolved!', 'Validation Complete', 4000)
  await updateTicket()
}

async function confirmDelete() {
  try {
    await adminStore.deleteTicket(ticketId)
    showDeleteConfirm.value = false
    success('Ticket has been deleted permanently.', 'Deleted', 4000)
    setTimeout(() => router.push('/tickets'), 1000)
  } catch (err) {
    error('Failed to delete ticket.', 'Error')
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