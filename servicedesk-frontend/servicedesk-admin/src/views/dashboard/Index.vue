<template>
  <AppLayout>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800">
        {{ isAdmin ? 'Admin Dashboard' : 'Employee Dashboard' }}
      </h1>
      <p class="text-gray-400 text-sm mt-1">
        {{ isAdmin 
          ? 'Overview of all service desk activities' 
          : `Welcome back, ${userName}! Here are your assigned tickets.` 
        }}
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 font-medium">
              {{ isAdmin ? 'Total Tickets' : 'My Tickets' }}
            </p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ stats.totalTickets }}</p>
          </div>
          <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 font-medium">Open Tickets</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ stats.openTickets }}</p>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 font-medium">In Progress</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ stats.inProgressTickets }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 font-medium">Resolved</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ stats.resolvedTickets }}</p>
          </div>
          <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-800">
          {{ isAdmin ? 'Recent Tickets' : 'My Recent Tickets' }}
        </h2>
        <router-link to="/tickets" class="text-sm text-red-600 hover:text-red-700 font-semibold">
          View All
        </router-link>
      </div>
      
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50">
              <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Ticket ID</th>
              <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Title</th>
              <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
              <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Priority</th>
              <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Assigned To</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr 
              v-for="ticket in recentTickets" 
              :key="ticket.id" 
              class="hover:bg-gray-50 cursor-pointer transition-colors"
              @click="viewTicket(ticket.id)"
            >
              <td class="px-6 py-4 text-sm font-mono font-semibold text-gray-600">{{ ticket.id }}</td>
              <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ ticket.title }}</td>
              <td class="px-6 py-4"><StatusBadge :status="ticket.status" /></td>
              <td class="px-6 py-4"><PriorityBadge :priority="ticket.priority" /></td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ ticket.assignedTo || 'Unassigned' }}</td>
            </tr>
            
            <tr v-if="recentTickets.length === 0">
              <td colspan="5" class="px-6 py-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 font-medium">
                  {{ isAdmin ? 'No tickets yet' : 'No tickets assigned to you' }}
                </p>
                <p class="text-sm text-gray-400 mt-1">
                  {{ isAdmin ? 'Tickets will appear here once created' : 'Contact your admin to get assigned' }}
                </p>
              </td>
            </tr>
          </tbody>
        </table>
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
import { useAdminStore } from '../../stores/adminStore'

const router = useRouter()
const store = useAdminStore()

const userRole = localStorage.getItem('userRole') || 'admin'
const userName = localStorage.getItem('userName') || 'user'
const isAdmin = userRole.toLowerCase() === 'admin'

const visibleTickets = computed(() => {
  if (isAdmin) {
    return store.tickets 
  } else {
    return store.tickets.filter(t => t.assignedTo === userName)
  }
})

const stats = computed(() => {
  const tickets = visibleTickets.value
  
  return {
    totalTickets: tickets.length,
    openTickets: tickets.filter(t => t.status === 'Open').length,
    inProgressTickets: tickets.filter(t => t.status === 'In Progress').length,
    resolvedTickets: tickets.filter(t => t.status === 'Resolved').length
  }
})

const recentTickets = computed(() => {
  return [...visibleTickets.value]
    .sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime())
    .slice(0, 5)
})

function viewTicket(id) {
  router.push(`/tickets/${id}`)
}
</script>