<template>
  <Teleport to="body">
    <div class="fixed top-4 right-4 z-50 space-y-3 max-w-md w-full pointer-events-none">
      <transition-group
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="transform translate-x-full opacity-0"
        enter-to-class="transform translate-x-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="transform translate-x-0 opacity-100"
        leave-to-class="transform translate-x-full opacity-0"
      >
        <div
          v-for="notification in notifications"
          :key="notification.id"
          class="pointer-events-auto"
        >
          <div
            class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-4 flex items-start gap-3 overflow-hidden"
            :class="notificationClasses(notification.type)"
          >
            <div class="flex-shrink-0">
              <div
                class="w-10 h-10 rounded-xl flex items-center justify-center"
                :class="iconBgClasses(notification.type)"
              >
                <component
                  :is="getIcon(notification.type)"
                  class="w-5 h-5"
                  :class="iconClasses(notification.type)"
                />
              </div>
            </div>

            <div class="flex-1 min-w-0">
              <h4 class="text-sm font-bold text-gray-800">
                {{ notification.title || getTitle(notification.type) }}
              </h4>
              <p class="text-sm text-gray-600 mt-0.5 leading-relaxed">
                {{ notification.message }}
              </p>
            </div>

            <button
              @click="remove(notification.id)"
              class="flex-shrink-0 p-1.5 rounded-lg hover:bg-gray-100 transition-colors"
            >
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

            <div
              class="absolute bottom-0 left-0 h-1 bg-current opacity-20"
              :class="progressColor(notification.type)"
              :style="{
                width: '100%',
                animation: `shrink ${notification.duration}ms linear forwards`
              }"
            ></div>
          </div>
        </div>
      </transition-group>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { 
  CheckCircle, 
  XCircle, 
  AlertCircle, 
  Info,
  Bell
} from 'lucide-vue-next'

const notifications = ref([])
let notificationId = 0

onMounted(() => {
  window.addEventListener('show-notification', handleNotification)
})

function handleNotification(event) {
  const { type, title, message, duration = 4000 } = event.detail
  show(type, message, title, duration)
}

function show(type, message, title = null, duration = 4000) {
  const id = ++notificationId
  const notification = {
    id,
    type,
    title,
    message,
    duration
  }
  
  notifications.value.push(notification)
  
  if (duration > 0) {
    setTimeout(() => {
      remove(id)
    }, duration)
  }
}

function remove(id) {
  const index = notifications.value.findIndex(n => n.id === id)
  if (index > -1) {
    notifications.value.splice(index, 1)
  }
}

function notificationClasses(type) {
  const classes = {
    success: 'border-l-4 border-l-green-500',
    error: 'border-l-4 border-l-red-500',
    warning: 'border-l-4 border-l-amber-500',
    info: 'border-l-4 border-l-blue-500'
  }
  return classes[type] || classes.info
}

function iconBgClasses(type) {
  const classes = {
    success: 'bg-green-100',
    error: 'bg-red-100',
    warning: 'bg-amber-100',
    info: 'bg-blue-100'
  }
  return classes[type] || classes.info
}

function iconClasses(type) {
  const classes = {
    success: 'text-green-600',
    error: 'text-red-600',
    warning: 'text-amber-600',
    info: 'text-blue-600'
  }
  return classes[type] || classes.info
}

function progressColor(type) {
  const colors = {
    success: 'text-green-500',
    error: 'text-red-500',
    warning: 'text-amber-500',
    info: 'text-blue-500'
  }
  return colors[type] || colors.info
}

function getIcon(type) {
  const icons = {
    success: CheckCircle,
    error: XCircle,
    warning: AlertCircle,
    info: Info
  }
  return icons[type] || Bell
}

function getTitle(type) {
  const titles = {
    success: 'Success!',
    error: 'Error',
    warning: 'Warning',
    info: 'Information'
  }
  return titles[type] || 'Notification'
}

defineExpose({
  show,
  success: (message, title, duration) => show('success', message, title, duration),
  error: (message, title, duration) => show('error', message, title, duration),
  warning: (message, title, duration) => show('warning', message, title, duration),
  info: (message, title, duration) => show('info', message, title, duration)
})
</script>

<style scoped>
@keyframes shrink {
  from {
    width: 100%;
  }
  to {
    width: 0%;
  }
}
</style>