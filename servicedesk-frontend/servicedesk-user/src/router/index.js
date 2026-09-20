import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', redirect: '/login' },
  
  { path: '/login', name: 'Login', component: () => import('../views/auth/Login.vue') },
  
  { path: '/dashboard', name: 'Dashboard', component: () => import('../views/dashboard/Index.vue'), meta: { requiresAuth: true } },
  
  { path: '/tickets', name: 'Tickets', component: () => import('../views/tickets/Index.vue'), meta: { requiresAuth: true } },
  { path: '/tickets/create', name: 'TicketCreate', component: () => import('../views/tickets/Create.vue'), meta: { requiresAuth: true } },
  { path: '/tickets/:id', name: 'TicketDetail', component: () => import('../views/tickets/Detail.vue'), meta: { requiresAuth: true } },
  
  { path: '/activity', name: 'Activity', component: () => import('../views/activity/Index.vue'), meta: { requiresAuth: true } }, 
  { path: '/profile', name: 'Profile', component: () => import('../views/profile/Index.vue'), meta: { requiresAuth: true } },
  
  { path: '/:pathMatch(.*)*', redirect: '/dashboard' }
]

const router = createRouter({ 
  history: createWebHistory(), 
  routes 
})

router.beforeEach((to, from, next) => {
  const userRole = (localStorage.getItem('userRole') || '').toLowerCase()
  const isAuthenticated = !!localStorage.getItem('userToken')
  
  if (to.path === '/login') {
    if (isAuthenticated) {
      if (userRole === 'admin' || userRole === 'employee') {
        window.location.replace('http://localhost:5174/dashboard')
        return
      }
      next('/dashboard')
      return
    }
    next()
    return
  }

  if (to.meta.requiresAuth) {
    if (!isAuthenticated) {
      next('/login')
      return
    } 
    
    if (userRole === 'admin' || userRole === 'employee') {
      window.location.replace('http://localhost:5174/dashboard')
      return
    }
    
    next()
  } else {
    next()
  }
})

export default router