<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 space-y-6">
      <div class="text-center space-y-2">
        <div class="w-16 h-16 bg-red-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-red-600/30">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
          </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">User Portal</h1>
        <p class="text-sm text-gray-500">Sign in to submit and track your tickets</p>
      </div>

      <div 
        v-if="globalError" 
        class="bg-red-50 border border-red-200 rounded-xl p-3 flex items-start gap-3 animate-fade-in"
      >
        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <div>
          <p class="text-sm font-semibold text-red-800">Login Failed</p>
          <p class="text-xs text-red-600 mt-0.5">{{ globalError }}</p>
        </div>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
          <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <input 
              v-model="formData.email" 
              type="email" 
              placeholder="your.email@company.com"
              class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
              :class="{ 'border-red-300 bg-red-50': errors.email }"
              @input="clearError('email')"
            />
          </div>
          <p v-if="errors.email" class="text-xs text-red-500 mt-1 flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            {{ errors.email }}
          </p>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
          <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <input 
              v-model="formData.password" 
              :type="showPassword ? 'text' : 'password'" 
              placeholder="••••••••"
              class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all"
              :class="{ 'border-red-300 bg-red-50': errors.password }"
              @input="clearError('password')"
            />
            <button 
              type="button"
              @click="showPassword = !showPassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
            >
              <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
              </svg>
            </button>
          </div>
          <p v-if="errors.password" class="text-xs text-red-500 mt-1 flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            {{ errors.password }}
          </p>
        </div>

        <div class="flex items-center justify-between">
          <label class="flex items-center gap-2 cursor-pointer">
            <input v-model="rememberMe" type="checkbox" class="w-4 h-4 rounded border-gray-300 text-red-600 focus:ring-red-500" />
            <span class="text-sm text-gray-600">Remember me</span>
          </label>
          <a href="#" class="text-sm font-semibold text-red-600 hover:text-red-700 transition-colors">Forgot password?</a>
        </div>

        <button 
          type="submit" 
          :disabled="isLoading"
          class="w-full py-3 bg-red-600 text-white rounded-xl text-sm font-semibold hover:bg-red-700 transition-all shadow-lg shadow-red-600/25 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
          <svg v-if="isLoading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
          </svg>
          {{ isLoading ? 'Signing in...' : 'Sign In' }}
        </button>
      </form>

      <div class="pt-4 border-t border-gray-100">
        <p class="text-xs text-center text-gray-400 mb-3">Demo Credentials:</p>
        <div class="flex justify-center gap-2">
          <button 
            @click="fillDemo('user')" 
            class="px-5 py-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors text-gray-600 text-xs font-medium border border-gray-200 flex items-center gap-2"
          >
            User Account
          </button>
        </div>
        <p class="text-[10px] text-center text-gray-400 mt-2">
          user@google.com / password
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useUserStore } from '../../stores/userStore'

const router = useRouter()
const userStore = useUserStore()

const formData = ref({ email: '', password: '' })
const showPassword = ref(false)
const rememberMe = ref(false)
const isLoading = ref(false)
const globalError = ref('')
const errors = ref({})

function fillDemo(type) {
  if (type === 'user') {
    formData.value.email = 'user@google.com'
    formData.value.password = 'password'
    globalError.value = ''
    errors.value = {}
  }
}

function clearError(field) {
  if (errors.value[field]) {
    errors.value[field] = ''
  }
  if (globalError.value) {
    globalError.value = ''
  }
}

function validateForm() {
  errors.value = {}
  globalError.value = ''
  let isValid = true

  if (!formData.value.email.trim()) {
    errors.value.email = 'Email is required'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.value.email)) {
    errors.value.email = 'Please enter a valid email address'
    isValid = false
  }

  if (!formData.value.password) {
    errors.value.password = 'Password is required'
    isValid = false
  } else if (formData.value.password.length < 4) {
    errors.value.password = 'Password must be at least 4 characters'
    isValid = false
  }

  return isValid
}

async function handleLogin() {
  if (!validateForm()) return
  
  isLoading.value = true
  globalError.value = ''

  try {
    const success = await userStore.login(formData.value.email, formData.value.password)
    
    if (success) {
      console.log('✅ Login successful:', userStore.currentUser)
      router.push('/dashboard')
    } else {
      globalError.value = 'Invalid email or password. Please try again.'
      errors.value.email = 'Invalid credentials'
      errors.value.password = 'Invalid credentials'
    }
  } catch (error) {
    console.error('❌ Login error:', error)
    globalError.value = 'Network error. Please check your connection and try again.'
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
@keyframes fade-in {
  from { 
    opacity: 0; 
    transform: translateY(-10px); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0); 
  }
}
.animate-fade-in {
  animation: fade-in 0.3s ease-out forwards;
}
</style>