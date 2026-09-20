<template>
  <AppLayout>
    <Notification ref="notificationRef" />

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">All Users</h1>
        <p class="text-gray-400 text-sm mt-1">Manage system users and their roles</p>
      </div>
      
      <router-link 
        v-if="userRole === 'admin'"
        to="/users/create" 
        class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white rounded-2xl font-semibold text-sm hover:bg-red-700 transition-all shadow-lg shadow-red-600/25"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create New User
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
            placeholder="Search by name or email..."
            class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300"
            @input="handleSearch"
          />
        </div>
        
        <div class="xl:col-span-3">
          <select 
            v-model="filterStatus" 
            class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20"
          >
            <option value="">All Roles</option>
            <option value="Admin">Admin</option>
            <option value="employee">Employee</option> 
            <option value="User">User</option>
          </select>
        </div>
        
        <div class="xl:col-span-3">
          <select 
            v-model="filterCategory" 
            class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20"
          >
            <option value="">All Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>
        
        <div class="xl:col-span-2 flex items-center justify-end">
          <button 
            v-if="hasActiveFilters"
            @click="clearAllFilters"
            class="p-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 transition-colors"
            title="Clear Filters"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div class="mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
      <p class="text-sm text-gray-500">
        Showing 
        <span class="font-semibold text-gray-800">{{ startItem }}</span> 
        to 
        <span class="font-semibold text-gray-800">{{ endItem }}</span> 
        of 
        <span class="font-semibold text-gray-800">{{ filteredData.length }}</span> 
        users
      </p>
      
      <div class="flex items-center gap-2">
        <span class="text-sm text-gray-500">Show:</span>
        <select 
          v-model="itemsPerPage" 
          class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20"
        >
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
              <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">User</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th>
              <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
              <th class="text-right px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="user in paginatedData" :key="user.id" class="hover:bg-gray-50/50 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-red-50 text-red-600 rounded-full flex items-center justify-center font-semibold text-sm border border-red-100">
                    {{ user.name.charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">{{ user.name }}</p>
                    <p class="text-xs text-gray-500">{{ user.email }}</p>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">
                <span 
                  class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border capitalize"
                  :class="{
                    'bg-purple-50 text-purple-700 border-purple-100': user.role === 'Admin',
                    'bg-blue-50 text-blue-700 border-blue-100': user.role === 'employee' || user.role === 'Agent',
                    'bg-gray-50 text-gray-700 border-gray-100': user.role === 'User'
                  }"
                >
                  {{ user.role }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span 
                  class="inline-flex items-center gap-1.5 text-sm font-medium px-3 py-1.5 rounded-full border"
                  :class="user.status === 'Active' 
                    ? 'text-green-700 bg-green-50 border-green-100' 
                    : 'text-red-700 bg-red-50 border-red-100'"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="user.status === 'Active' ? 'bg-green-500' : 'bg-red-500'"></span>
                  {{ user.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-1" v-if="userRole === 'admin'">
                  <button 
                    class="p-2 rounded-xl hover:bg-blue-50 text-blue-500 transition-colors" 
                    @click="editUser(user.id)" 
                    title="Edit"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button 
                    class="p-2 rounded-xl hover:bg-red-50 text-red-500 transition-colors" 
                    @click="openDeleteModal(user.id)" 
                    title="Delete"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <p class="text-gray-500 font-medium">No users found</p>
        <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
      </div>
    </div>

    <div v-if="totalPages > 1" class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
      <div class="flex items-center gap-2">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          Previous
        </button>

        <button
          v-for="page in displayPages"
          :key="page"
          @click="currentPage = page"
          class="w-10 h-10 flex items-center justify-center text-sm font-medium rounded-xl transition-colors"
          :class="currentPage === page ? 'bg-red-600 text-white shadow-md shadow-red-600/25' : 'text-gray-600 hover:bg-gray-100'"
        >
          {{ page }}
        </button>

        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          Next
        </button>
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
          <p class="text-sm text-gray-500 mt-2">Are you sure you want to delete this user? This action cannot be undone and will remove all associated data.</p>
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
import Notification from '../../components/common/Notification.vue'
import { useNotification } from '../../composables/useNotification'
import { useAdminStore } from '../../stores/adminStore'
import { useTableFilter } from '../../composables/useTableFilter'

const router = useRouter()
const adminStore = useAdminStore()
const { notificationRef, success, error } = useNotification()

const userRole = localStorage.getItem('userRole') || 'admin'

const showDeleteModal = ref(false)
const userToDelete = ref(null)

function filterUsers(data, filters) {
  return data.filter(user => {
    const matchesSearch = filters.searchQuery === '' ||
      user.name.toLowerCase().includes(filters.searchQuery.toLowerCase()) ||
      user.email.toLowerCase().includes(filters.searchQuery.toLowerCase())

    const matchesRole = !filters.status || user.role === filters.status
    const matchesAccountStatus = !filters.category || user.status === filters.category

    return matchesSearch && matchesRole && matchesAccountStatus
  })
}

const sortedUsers = computed(() => {
  return [...adminStore.users].sort((a, b) => Number(b.id) - Number(a.id))
})

const {
  searchQuery,
  filterStatus,
  filterCategory,
  currentPage,
  itemsPerPage,
  hasActiveFilters,
  filteredData,
  paginatedData,
  totalPages,
  startItem,
  endItem,
  displayPages,
  handleSearch,
  clearAllFilters,
  prevPage,
  nextPage
} = useTableFilter(sortedUsers, filterUsers)

function editUser(id) {
  router.push(`/users/${id}/edit`)
}

function openDeleteModal(id) {
  userToDelete.value = id
  showDeleteModal.value = true
}

function cancelDelete() {
  showDeleteModal.value = false
  userToDelete.value = null
}

async function confirmDelete() {
  if (userToDelete.value) {
    try {
      await adminStore.deleteUser(userToDelete.value)
      
      success('User has been permanently deleted.', 'Deleted Successfully', 4000)
      
      showDeleteModal.value = false
      userToDelete.value = null
      
      if (paginatedData.value.length === 0 && currentPage.value > 1) {
        currentPage.value--
      }
    } catch (err) {
      console.error('Delete error:', err)
      error('Failed to delete user. Please try again.', 'Delete Failed')
    }
  }
}
</script>