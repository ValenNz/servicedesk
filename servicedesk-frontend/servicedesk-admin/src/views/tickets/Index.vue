<template>
  <AppLayout>
    <Notification ref="notificationRef" />

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">{{ userRole === 'admin' ? 'All Tickets' : 'My Tickets' }}</h1>
        <p class="text-gray-400 text-sm mt-1">
          {{ userRole === 'admin' ? 'Manage and track all service requests' : 'Track your assigned service requests' }}
        </p>
      </div>
    </div>

    <div class="bg-white rounded-3xl p-4 mb-6 border border-gray-100 shadow-sm">
      <div class="grid grid-cols-1 xl:grid-cols-12 gap-3">
        <div class="relative xl:col-span-3">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search tickets..."
            class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300"
            @input="handleSearch"
          />
        </div>
        
        <div class="xl:col-span-3 flex items-center gap-1">
          <input v-model="filterStartDate" type="date" class="flex-1 px-2 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-red-500/20 text-gray-600" />
          <span class="text-gray-400 text-xs">-</span>
          <input v-model="filterEndDate" type="date" class="flex-1 px-2 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-red-500/20 text-gray-600" />
        </div>
        
        <div class="xl:col-span-2">
          <select v-model="filterStatus" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
            <option value="">All Status</option>
            <option value="Open">Open</option>
            <option value="In Progress">In Progress</option>
            <option value="Resolved">Resolved</option>
            <option value="Closed">Closed</option>
          </select>
        </div>
        
        <div class="xl:col-span-2">
          <select v-model="filterCategory" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
            <option value="">All Categories</option>
            <option v-for="cat in store.categories" :key="cat.id" :value="cat.name">{{ cat.name }}</option>
          </select>
        </div>
        
        <div class="xl:col-span-1">
          <select v-model="filterPriority" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
            <option value="">All</option>
            <option value="High">High</option>
            <option value="Medium">Medium</option>
            <option value="Low">Low</option>
          </select>
        </div>
        
        <div class="xl:col-span-1 flex items-center justify-end">
          <button v-if="hasActiveFilters" @click="clearAllFilters" class="p-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-colors" title="Clear Filters">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
      <p class="text-sm text-gray-500">
        Showing <span class="font-semibold text-gray-800">{{ startItem }}</span> to <span class="font-semibold text-gray-800">{{ endItem }}</span> of <span class="font-semibold text-gray-800">{{ filteredData.length }}</span> tickets
      </p>
      <div class="flex items-center gap-2">
        <span class="text-sm text-gray-500">Show:</span>
        <select v-model="itemsPerPage" class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20">
          <option :value="5">5</option>
          <option :value="10">10</option>
          <option :value="20">20</option>
          <option :value="50">50</option>
        </select>
        <span class="text-sm text-gray-500">per page</span>
      </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-50">
              <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Ticket ID</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Title</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Category</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Priority</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Assigned To</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
              <th class="text-right px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="ticket in paginatedData" :key="ticket.id" class="hover:bg-gray-50/50 transition-colors cursor-pointer" @click="viewTicket(ticket.id)">
              <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-400">{{ ticket.id }}</td>
              <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ ticket.title }}</td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center text-xs font-medium text-gray-600 bg-gray-50 px-2.5 py-1 rounded-full">
                  <span class="w-1.5 h-1.5 rounded-full bg-red-400 mr-1.5"></span>
                  {{ ticket.category }}
                </span>
              </td>
              <td class="px-6 py-4"><PriorityBadge :priority="ticket.priority" /></td>
              <td class="px-6 py-4"><StatusBadge :status="ticket.status" /></td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ ticket.assignedTo || 'Unassigned' }}</td>
              <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">{{ ticket.timeAgo }}</td>
              <td class="px-6 py-4 text-right" @click.stop>
                <div class="flex items-center justify-end gap-1">
                  <button class="p-2 rounded-xl hover:bg-blue-50 text-blue-500 transition-colors" @click="editTicket(ticket.id)" title="Edit Ticket">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                  </button>
                  <button v-if="userRole === 'admin'" class="p-2 rounded-xl hover:bg-red-50 text-red-500 transition-colors" @click="openDeleteModal(ticket.id)" title="Delete Ticket">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div v-if="filteredData.length === 0" class="text-center py-12">
      <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm inline-block">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <p class="text-gray-500 font-medium">No tickets found</p>
        <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
      </div>
    </div>

    <div v-if="totalPages > 1" class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
      <div class="flex items-center gap-2">
        <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">Previous</button>
        <button v-for="page in displayPages" :key="page" @click="currentPage = page" class="w-10 h-10 flex items-center justify-center text-sm font-medium rounded-xl transition-colors" :class="currentPage === page ? 'bg-red-600 text-white shadow-md shadow-red-600/25' : 'text-gray-600 hover:bg-gray-100'">{{ page }}</button>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">Next</button>
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
          <h3 class="text-xl font-bold text-gray-800">Delete Ticket?</h3>
          <p class="text-sm text-gray-500 mt-2">Are you sure you want to delete ticket <span class="font-semibold text-gray-800">#{{ ticketToDelete }}</span>? This action cannot be undone.</p>
        </div>
        
        <div class="flex gap-3">
          <button 
            @click="cancelDelete"
            class="flex-1 px-4 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-colors"
          >
            Cancel
          </button>
          <button 
            @click="confirmDelete"
            class="flex-1 px-4 py-3 bg-red-600 text-white rounded-2xl text-sm font-semibold hover:bg-red-700 transition-colors shadow-lg shadow-red-600/25"
          >
            Yes, Delete
          </button>
        </div>
      </div>
    </div>

  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import AppLayout from '../../components/layout/AppLayout.vue'
import StatusBadge from '../../components/common/StatusBadge.vue'
import PriorityBadge from '../../components/common/PriorityBadge.vue'
import Notification from '../../components/common/Notification.vue'
import { useNotification } from '../../composables/useNotification'
import { useAdminStore } from '../../stores/adminStore'
import { useTableFilter } from '../../composables/useTableFilter'

const router = useRouter()
const store = useAdminStore()
const { notificationRef, success, error } = useNotification()

const userRole = localStorage.getItem('userRole') || 'admin'
const userName = localStorage.getItem('userName') || ''

const showDeleteModal = ref(false)
const ticketToDelete = ref(null)

function filterTickets(data, filters) {
  return data.filter(ticket => {
    const matchesSearch = filters.searchQuery === '' || 
      ticket.id.toLowerCase().includes(filters.searchQuery.toLowerCase()) ||
      ticket.title.toLowerCase().includes(filters.searchQuery.toLowerCase()) ||
      ticket.description.toLowerCase().includes(filters.searchQuery.toLowerCase())
    
    const matchesStatus = !filters.status || ticket.status === filters.status
    const matchesCategory = !filters.category || ticket.category === filters.category
    const matchesPriority = !filters.priority || ticket.priority === filters.priority

    let matchesDate = true
    if (filters.startDate) {
      const ticketDate = new Date(ticket.createdAt).setHours(0, 0, 0, 0)
      const filterDate = new Date(filters.startDate).setHours(0, 0, 0, 0)
      if (ticketDate < filterDate) matchesDate = false
    }
    if (filters.endDate) {
      const ticketDate = new Date(ticket.createdAt).setHours(0, 0, 0, 0)
      const filterDate = new Date(filters.endDate).setHours(23, 59, 59, 999)
      if (ticketDate > filterDate) matchesDate = false
    }

    return matchesSearch && matchesStatus && matchesCategory && matchesPriority && matchesDate
  })
}

const ticketsForDisplay = computed(() => {
  let data = store.tickets
  
  if (userRole === 'employee') {
    data = data.filter(ticket => ticket.assignedTo === userName)
  }
  
  return [...data].sort((a, b) => {
    return new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
  })
})

const {
  searchQuery, filterStatus, filterCategory, filterPriority, filterStartDate, filterEndDate,
  currentPage, itemsPerPage, hasActiveFilters, filteredData, paginatedData, totalPages,
  startItem, endItem, displayPages, handleSearch, clearAllFilters, prevPage, nextPage
} = useTableFilter(ticketsForDisplay, filterTickets)

function viewTicket(id) { 
  router.push(`/tickets/${id}`) 
}

function editTicket(id) { 
  router.push(`/tickets/${id}?edit=true`) 
}

function openDeleteModal(id) {
  ticketToDelete.value = id
  showDeleteModal.value = true
}

function cancelDelete() {
  showDeleteModal.value = false
  ticketToDelete.value = null
}

async function confirmDelete() {
  if (ticketToDelete.value) {
    try {
      await store.deleteTicket(ticketToDelete.value)
      
      success(`Ticket #${ticketToDelete.value} has been permanently deleted.`, 'Deleted Successfully', 4000)
      
      showDeleteModal.value = false
      ticketToDelete.value = null
      
      if (paginatedData.value.length === 0 && currentPage.value > 1) {
        currentPage.value--
      }
    } catch (err) {
      console.error('Delete error:', err)
      error('Failed to delete ticket. Please try again.', 'Delete Failed')
    }
  }
}
</script>