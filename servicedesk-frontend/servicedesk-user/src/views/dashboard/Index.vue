<template>
  <AppLayout>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800">User Dashboard</h1>
      <p class="text-gray-400 text-sm mt-1">
        Welcome back, {{ userName }}! Here are your submitted and assigned tickets.
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500 font-medium">My Tickets</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ store.myStats.total }}</p>
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
            <p class="text-sm text-gray-500 font-medium">Open</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ store.myStats.open }}</p>
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
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ store.myStats.inProgress }}</p>
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
            <p class="text-3xl font-bold text-green-600 mt-2">{{ store.myStats.resolved }}</p>
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
        <h2 class="text-lg font-bold text-gray-800">My Recent Tickets</h2>
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
                <p class="text-gray-500 font-medium">No tickets found</p>
                <p class="text-sm text-gray-400 mt-1">You haven't created or been assigned any tickets yet.</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="mt-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-3xl p-6 border border-blue-100">
      <h3 class="text-lg font-bold text-gray-800 mb-3">Need Help?</h3>
      <p class="text-sm text-gray-600 mb-4">
        If you have a new issue, you can submit a new service request and our team will assist you.
      </p>
      <router-link 
        to="/tickets/create"
        class="inline-block px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/25"
      >
        Create New Ticket
      </router-link>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import AppLayout from '../../components/layout/AppLayout.vue'
import StatusBadge from '../../components/common/StatusBadge.vue'
import PriorityBadge from '../../components/common/PriorityBadge.vue'
import { useUserStore } from '../../stores/userStore'

const router = useRouter()
const store = useUserStore()

const userName = computed(() => {
  return store.currentUser?.name || localStorage.getItem('userName') || 'User'
})

const recentTickets = computed(() => {
  const tickets = store.myTickets || []
  return [...tickets]
    .sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime())
    .slice(0, 5)
})

function viewTicket(id) {
  router.push(`/tickets/${id}`)
}

onMounted(async () => {
  if (store.tickets.length === 0) {
    await store.fetchData()
  }
})
</script>