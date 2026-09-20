<template>
  <AppLayout>
    <Notification ref="notificationRef" />

    <div class="max-w-5xl mx-auto space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">
            {{ isAdmin ? 'Administrator' : 'Employee' }} Profile
          </h1>
          <p class="text-gray-400 text-sm mt-1">
            {{ isAdmin ? 'Manage your administrator account settings' : 'Manage your employee account settings' }}
          </p>
        </div>
        <div class="flex items-center gap-2">
          <span 
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold border capitalize"
            :class="isAdmin 
              ? 'bg-purple-50 text-purple-700 border-purple-100' 
              : 'bg-blue-50 text-blue-700 border-blue-100'"
          >
            <span class="w-1.5 h-1.5 rounded-full" :class="isAdmin ? 'bg-purple-500' : 'bg-blue-500'"></span>
            {{ loggedInUser?.role || 'User' }}
          </span>
        </div>
      </div>

      <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 via-red-500 to-orange-500 h-48 relative">
          <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                  <circle cx="2" cy="2" r="1" fill="white" />
                </pattern>
              </defs>
              <rect width="100%" height="100%" fill="url(#dots)" />
            </svg>
          </div>
          
          <div class="absolute -bottom-16 left-8">
            <div class="relative">
              <div class="w-32 h-32 bg-white rounded-3xl flex items-center justify-center text-red-600 text-4xl font-bold shadow-2xl border-4 border-white">
                {{ avatarInitials }}
              </div>
              <div class="absolute -top-2 -right-2 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg border-2 border-white">
                Online
              </div>
            </div>
          </div>
        </div>

        <div class="pt-24 px-8 pb-8">
          <form @submit.prevent="saveProfile" class="space-y-8">
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div v-for="stat in quickStats" :key="stat.label" 
                class="bg-gradient-to-br rounded-2xl p-5 border shadow-sm hover:shadow-md transition-shadow"
                :class="stat.bgClass"
              >
                <div class="flex items-center justify-between mb-3">
                  <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm" :class="stat.iconBg">
                    <svg class="w-5 h-5" :class="stat.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon" />
                    </svg>
                  </div>
                </div>
                <p class="text-3xl font-bold mb-1" :class="stat.valueColor">{{ stat.value }}</p>
                <p class="text-xs font-semibold" :class="stat.labelColor">{{ stat.label }}</p>
              </div>
            </div>

            <div>
              <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
                Personal Information
              </h2>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Full Name <span class="text-red-500">*</span>
                  </label>
                  <input 
                    v-model="profile.name" 
                    type="text" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
                    :class="{ 'border-red-300 bg-red-50': errors.name }"
                    placeholder="e.g. John Doe"
                    @input="clearError('name')"
                  />
                  <p v-if="errors.name" class="text-xs text-red-500 mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    {{ errors.name }}
                  </p>
                </div>

                <div class="md:col-span-2">
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                  </label>
                  <input 
                    v-model="profile.email" 
                    type="email" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
                    :class="{ 'border-red-300 bg-red-50': errors.email }"
                    placeholder="user@servicedesk.com"
                    @input="clearError('email')"
                  />
                  <p v-if="errors.email" class="text-xs text-red-500 mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    {{ errors.email }}
                  </p>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                  <input 
                    v-model="profile.phone" 
                    type="tel" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
                    placeholder="+62 812-3456-7890"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                  <div class="relative">
                    <input 
                      :value="profile.role" 
                      type="text" 
                      disabled
                      class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-2xl text-sm text-gray-500 cursor-not-allowed capitalize"
                    />
                    <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                  </div>
                  <p class="text-xs text-gray-400 mt-1">Contact system administrator to change your role.</p>
                </div>
              </div>
            </div>

            <div class="pt-8 border-t border-gray-100">
              <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                Change Password
              </h2>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                  <input 
                    v-model="passwords.current" 
                    type="password" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
                    :class="{ 'border-red-300 bg-red-50': errors.currentPassword }"
                    placeholder="••••••••"
                    @input="clearError('currentPassword')"
                  />
                  <p v-if="errors.currentPassword" class="text-xs text-red-500 mt-1">{{ errors.currentPassword }}</p>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                  <input 
                    v-model="passwords.new" 
                    type="password" 
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
                    :class="{ 'border-red-300 bg-red-50': errors.newPassword }"
                    placeholder="••••••••"
                    @input="clearError('newPassword')"
                  />
                  
                  <div v-if="passwords.new" class="mt-2 space-y-2">
                    <div class="flex gap-1">
                      <div v-for="i in 4" :key="i" class="h-1.5 flex-1 rounded-full transition-all"
                        :class="i <= passwordStrength.level 
                          ? passwordStrength.color 
                          : 'bg-gray-200'">
                      </div>
                    </div>
                    <p class="text-xs font-medium" :class="passwordStrength.textColor">
                      {{ passwordStrength.label }}
                    </p>
                  </div>
                  
                  <p v-if="errors.newPassword" class="text-xs text-red-500 mt-1">{{ errors.newPassword }}</p>
                  <p class="text-xs text-gray-400 mt-1">Leave blank to keep your current password.</p>
                </div>
              </div>
            </div>

            <div class="pt-8 border-t border-gray-100">
              <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                  <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                Recent Activity
              </h2>
              
              <div v-if="recentActivity.length > 0" class="space-y-3">
                <div v-for="(activity, index) in recentActivity" :key="index" 
                  class="flex items-start gap-3 p-4 bg-gray-50 rounded-2xl hover:bg-gray-100 transition-colors border border-gray-100"
                >
                  <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                    :class="activity.iconBg">
                    <svg class="w-5 h-5" :class="activity.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="activity.icon" />
                    </svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800">{{ activity.title }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ activity.subtitle }}</p>
                  </div>
                  <span class="text-xs font-medium text-gray-400 whitespace-nowrap">{{ activity.time }}</span>
                </div>
              </div>
              <div v-else class="text-center py-8 bg-gray-50 rounded-2xl border border-gray-100">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-gray-500">No recent activity</p>
                <p class="text-xs text-gray-400 mt-1">Your ticket activities will appear here</p>
              </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100">
              <button 
                type="button" 
                @click="resetForm"
                class="px-6 py-3 border border-gray-200 rounded-2xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all"
              >
                Reset
              </button>
              <button 
                type="submit" 
                :disabled="isSaving"
                class="px-6 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white rounded-2xl text-sm font-semibold hover:from-red-700 hover:to-orange-700 transition-all shadow-lg shadow-red-600/25 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
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
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import AppLayout from '../../components/layout/AppLayout.vue'
import Notification from '../../components/common/Notification.vue'
import { useNotification } from '../../composables/useNotification'
import { useAdminStore } from '../../stores/adminStore'

const adminStore = useAdminStore()
const { notificationRef, success, error } = useNotification()

const userRole = localStorage.getItem('userRole') || 'admin'
const userId = localStorage.getItem('userId')
const isAdmin = computed(() => userRole.toLowerCase() === 'admin')

const loggedInUser = computed(() => {
  if (userId) {
    const found = adminStore.users.find(u => String(u.id) === String(userId))
    if (found) return found
  }
  return adminStore.currentUser
})

const avatarInitials = computed(() => {
  const name = profile.value.name || loggedInUser.value?.name || 'User'
  const parts = name.trim().split(' ').filter(Boolean)
  if (parts.length >= 2) {
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  }
  return parts[0]?.[0]?.toUpperCase() || 'U'
})

const quickStats = computed(() => {
  const tickets = adminStore.tickets
  
  if (isAdmin.value) {
    return [
      {
        label: 'Total Tickets',
        value: tickets.length,
        icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        bgClass: 'from-blue-50 to-blue-100/50 border-blue-100',
        iconBg: 'bg-blue-100',
        iconColor: 'text-blue-600',
        valueColor: 'text-blue-700',
        labelColor: 'text-blue-600'
      },
      {
        label: 'Active Users',
        value: adminStore.users.filter(u => u.status === 'Active').length,
        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
        bgClass: 'from-purple-50 to-purple-100/50 border-purple-100',
        iconBg: 'bg-purple-100',
        iconColor: 'text-purple-600',
        valueColor: 'text-purple-700',
        labelColor: 'text-purple-600'
      },
      {
        label: 'Categories',
        value: adminStore.categories.length,
        icon: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
        bgClass: 'from-orange-50 to-orange-100/50 border-orange-100',
        iconBg: 'bg-orange-100',
        iconColor: 'text-orange-600',
        valueColor: 'text-orange-700',
        labelColor: 'text-orange-600'
      },
      {
        label: 'Resolved',
        value: tickets.filter(t => t.status === 'Resolved').length,
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        bgClass: 'from-green-50 to-green-100/50 border-green-100',
        iconBg: 'bg-green-100',
        iconColor: 'text-green-600',
        valueColor: 'text-green-700',
        labelColor: 'text-green-600'
      }
    ]
  } else {
    const userName = loggedInUser.value?.name
    const myTickets = tickets.filter(t => t.assignedTo === userName || t.createdBy === userName)
    return [
      {
        label: 'My Tickets',
        value: myTickets.length,
        icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        bgClass: 'from-blue-50 to-blue-100/50 border-blue-100',
        iconBg: 'bg-blue-100',
        iconColor: 'text-blue-600',
        valueColor: 'text-blue-700',
        labelColor: 'text-blue-600'
      },
      {
        label: 'In Progress',
        value: myTickets.filter(t => t.status === 'In Progress').length,
        icon: 'M13 10V3L4 14h7v7l9-11h-7z',
        bgClass: 'from-yellow-50 to-yellow-100/50 border-yellow-100',
        iconBg: 'bg-yellow-100',
        iconColor: 'text-yellow-600',
        valueColor: 'text-yellow-700',
        labelColor: 'text-yellow-600'
      },
      {
        label: 'Open',
        value: myTickets.filter(t => t.status === 'Open').length,
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        bgClass: 'from-red-50 to-red-100/50 border-red-100',
        iconBg: 'bg-red-100',
        iconColor: 'text-red-600',
        valueColor: 'text-red-700',
        labelColor: 'text-red-600'
      },
      {
        label: 'Resolved',
        value: myTickets.filter(t => t.status === 'Resolved').length,
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        bgClass: 'from-green-50 to-green-100/50 border-green-100',
        iconBg: 'bg-green-100',
        iconColor: 'text-green-600',
        valueColor: 'text-green-700',
        labelColor: 'text-green-600'
      }
    ]
  }
})

const recentActivity = computed(() => {
  const activities = adminStore.activities || []
  const userName = loggedInUser.value?.name
  if (!userName) return []
  
  const userActivities = activities
    .filter(a => a.user === userName)
    .slice(0, 5)
  
  return userActivities.map(a => {
    let title, subtitle, iconBg, iconColor, icon
    
    switch (a.type) {
      case 'ticket_created':
        title = 'Created a new ticket'
        subtitle = a.details?.ticket || 'Unknown ticket'
        iconBg = 'bg-blue-100'
        iconColor = 'text-blue-600'
        icon = 'M12 4v16m8-8H4'
        break
      case 'ticket_assigned':
        title = 'Assigned ticket'
        subtitle = a.details?.ticket || 'Unknown ticket'
        iconBg = 'bg-purple-100'
        iconColor = 'text-purple-600'
        icon = 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
        break
      case 'comment_added':
        title = 'Added a comment'
        subtitle = a.details?.ticket || 'Unknown ticket'
        iconBg = 'bg-yellow-100'
        iconColor = 'text-yellow-600'
        icon = 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'
        break
      case 'status_changed':
        title = 'Changed ticket status'
        subtitle = `${a.details?.ticket || 'Unknown ticket'}: ${a.details?.from || '?'} → ${a.details?.to || '?'}`
        iconBg = 'bg-orange-100'
        iconColor = 'text-orange-600'
        icon = 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
        break
      case 'user_updated':
        title = 'Updated profile information'
        subtitle = 'Personal details changed'
        iconBg = 'bg-green-100'
        iconColor = 'text-green-600'
        icon = 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
        break
      case 'user_login':
        title = 'Logged in to the system'
        subtitle = 'Session started'
        iconBg = 'bg-teal-100'
        iconColor = 'text-teal-600'
        icon = 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1'
        break
      case 'user_logout':
        title = 'Logged out of the system'
        subtitle = 'Session ended'
        iconBg = 'bg-gray-100'
        iconColor = 'text-gray-600'
        icon = 'M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1'
        break
      default:
        title = a.description
        subtitle = 'Activity recorded'
        iconBg = 'bg-gray-100'
        iconColor = 'text-gray-600'
        icon = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
    }
    
    return {
      title,
      subtitle,
      iconBg,
      iconColor,
      icon,
      time: formatTimeAgo(a.timestamp)
    }
  })
})

function formatTimeAgo(timestamp) {
  if (!timestamp) return 'Recently'
  const date = new Date(timestamp)
  const now = new Date()
  const seconds = Math.floor((now - date) / 1000)
  
  if (seconds < 60) return 'Just now'
  const minutes = Math.floor(seconds / 60)
  if (minutes < 60) return `${minutes} minute${minutes > 1 ? 's' : ''} ago`
  const hours = Math.floor(minutes / 60)
  if (hours < 24) return `${hours} hour${hours > 1 ? 's' : ''} ago`
  const days = Math.floor(hours / 24)
  if (days < 7) return `${days} day${days > 1 ? 's' : ''} ago`
  return date.toLocaleDateString()
}

const passwordStrength = computed(() => {
  const pwd = passwords.value.new
  if (!pwd) return { level: 0, label: '', color: '', textColor: '' }
  
  let score = 0
  if (pwd.length >= 6) score++
  if (pwd.length >= 10) score++
  if (/[A-Z]/.test(pwd) && /[a-z]/.test(pwd)) score++
  if (/[0-9]/.test(pwd) && /[^A-Za-z0-9]/.test(pwd)) score++
  
  if (score <= 1) return { level: 1, label: 'Weak', color: 'bg-red-500', textColor: 'text-red-600' }
  if (score === 2) return { level: 2, label: 'Fair', color: 'bg-orange-500', textColor: 'text-orange-600' }
  if (score === 3) return { level: 3, label: 'Good', color: 'bg-yellow-500', textColor: 'text-yellow-600' }
  return { level: 4, label: 'Strong', color: 'bg-green-500', textColor: 'text-green-600' }
})

const profile = ref({
  name: '',
  email: '',
  phone: '',
  role: ''
})

const passwords = ref({
  current: '',
  new: ''
})

const errors = ref({})
const isSaving = ref(false)

onMounted(async () => {
  if (adminStore.users.length === 0) {
    await adminStore.fetchData()
  }
  
  if (loggedInUser.value) {
    profile.value = {
      name: loggedInUser.value.name || '',
      email: loggedInUser.value.email || '',
      phone: loggedInUser.value.phone || '',
      role: loggedInUser.value.role || 'User'
    }
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

  if (!profile.value.name.trim()) {
    errors.value.name = 'Full name is required'
    isValid = false
  }

  if (!profile.value.email.trim()) {
    errors.value.email = 'Email address is required'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(profile.value.email)) {
    errors.value.email = 'Please enter a valid email address'
    isValid = false
  }

  if (passwords.value.new) {
    if (!passwords.value.current) {
      errors.value.currentPassword = 'Current password is required to set a new password'
      isValid = false
    } else if (passwords.value.new.length < 6) {
      errors.value.newPassword = 'New password must be at least 6 characters'
      isValid = false
    }
  }

  return isValid
}

function resetForm() {
  if (loggedInUser.value) {
    profile.value = {
      name: loggedInUser.value.name || '',
      email: loggedInUser.value.email || '',
      phone: loggedInUser.value.phone || '',
      role: loggedInUser.value.role || 'User'
    }
  }
  passwords.value = { current: '', new: '' }
  errors.value = {}
}

async function saveProfile() {
  if (!validateForm()) {
    error('Please fix the errors in the form.', 'Validation Failed')
    return
  }

  isSaving.value = true

  try {
    const updates = {
      name: profile.value.name,
      email: profile.value.email,
      phone: profile.value.phone
    }
    
    if (passwords.value.new) {
      updates.password = passwords.value.new
    }
    
    await adminStore.updateUser(loggedInUser.value.id, updates)
    
    localStorage.setItem('userName', profile.value.name)
    localStorage.setItem('userEmail', profile.value.email)
    
    const names = profile.value.name.trim().split(' ')
    adminStore.currentUser.avatar = names.length > 1 
      ? (names[0][0] + names[names.length - 1][0]).toUpperCase() 
      : names[0][0].toUpperCase()
    adminStore.currentUser.name = profile.value.name
    adminStore.currentUser.email = profile.value.email

    passwords.value.current = ''
    passwords.value.new = ''
    errors.value = {}

    success('Your profile has been updated successfully!', 'Update Successful')
  } catch (err) {
    console.error('Profile update error:', err)
    error('Failed to update profile. Please try again.', 'Update Failed')
  } finally {
    isSaving.value = false
  }
}
</script>