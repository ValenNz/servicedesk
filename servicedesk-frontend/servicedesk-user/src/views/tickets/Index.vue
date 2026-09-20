<template>
  <AppLayout>
    <Notification ref="notificationRef" />

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">My Tickets</h1>
        <p class="text-gray-400 text-sm mt-1">
          Track your submitted and assigned service requests
        </p>
      </div>
      
      <router-link 
        to="/tickets/create" 
        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white rounded-2xl font-semibold text-sm hover:bg-red-700 transition-all shadow-lg shadow-red-600/25"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create New Ticket
      </router-link>
    </div>

    <div class="bg-white rounded-3xl p-4 mb-6 border border-gray-100 shadow-sm">
      <div class="grid grid-cols-1 xl:grid-cols-12 gap-3">
        <div class="relative xl:col-span-4">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search your tickets..."
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
                  <button class="p-2 rounded-xl hover:bg-blue-50 text-blue-500 transition-colors" @click="viewTicket(ticket.id)" title="View Ticket">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
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
        <p class="text-sm text-gray-400 mt-1">You haven't created or been assigned any tickets yet.</p>
        <router-link to="/tickets/create" class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors">
          Create Your First Ticket
        </router-link>
      </div>
    </div>

    <div v-if="totalPages > 1" class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
      <div class="flex items-center gap-2">
        <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">Previous</button>
        <button v-for="page in displayPages" :key="page" @click="currentPage = page" class="w-10 h-10 flex items-center justify-center text-sm font-medium rounded-xl transition-colors" :class="currentPage === page ? 'bg-red-600 text-white shadow-md shadow-red-600/25' : 'text-gray-600 hover:bg-gray-100'">{{ page }}</button>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">Next</button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import AppLayout from '../../components/layout/AppLayout.vue'
import StatusBadge from '../../components/common/StatusBadge.vue'
import PriorityBadge from '../../components/common/PriorityBadge.vue'
import Notification from '../../components/common/Notification.vue'
import { useNotification } from '../../composables/useNotification'
import { useUserStore } from '../../stores/userStore' 
import { useTableFilter } from '../../composables/useTableFilter'

const router = useRouter()
const store = useUserStore()
const { notificationRef, success, error } = useNotification()

const userRole = (localStorage.getItem('userRole') || 'user').toLowerCase()
const userName = localStorage.getItem('userName') || ''

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
  return [...store.myTickets].sort((a, b) => {
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
</script>