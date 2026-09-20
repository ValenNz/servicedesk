import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', redirect: '/login' },
  { path: '/login', name: 'Login', component: () => import('../views/auth/Login.vue') },
  { path: '/dashboard', name: 'Dashboard', component: () => import('../views/dashboard/Index.vue'), meta: { requiresAuth: true } },
  { path: '/tickets', name: 'Tickets', component: () => import('../views/tickets/Index.vue'), meta: { requiresAuth: true } },
  { path: '/tickets/:id', name: 'TicketDetail', component: () => import('../views/tickets/Detail.vue'), meta: { requiresAuth: true } },
  { path: '/activity', name: 'Activity', component: () => import('../views/activity/Index.vue'), meta: { requiresAuth: true } },
  { path: '/profile', name: 'Profile', component: () => import('../views/profile/Index.vue'), meta: { requiresAuth: true } },
  { path: '/users', name: 'Users', component: () => import('../views/users/Index.vue'), meta: { requiresAuth: true, role: 'admin' } },
  { path: '/users/create', name: 'UserCreate', component: () => import('../views/users/Create.vue'), meta: { requiresAuth: true, role: 'admin' } },
  { path: '/users/:id/edit', name: 'UserEdit', component: () => import('../views/users/Detail.vue'), meta: { requiresAuth: true, role: 'admin' } },
  { path: '/categories', name: 'Categories', component: () => import('../views/categories/Index.vue'), meta: { requiresAuth: true, role: 'admin' } },
  { path: '/categories/create', name: 'CategoryCreate', component: () => import('../views/categories/Create.vue'), meta: { requiresAuth: true, role: 'admin' } },
  { path: '/categories/:id', name: 'CategoryDetail', component: () => import('../views/categories/Detail.vue'), meta: { requiresAuth: true, role: 'admin' } },
  { path: '/:pathMatch(.*)*', redirect: '/dashboard' }
]

const router = createRouter({ history: createWebHistory(), routes })

router.beforeEach((to, from, next) => {
  const userRole = (localStorage.getItem('userRole') || '').toLowerCase()
  const isAuthenticated = userRole !== ''
  
  if (to.path === '/login' && isAuthenticated) {
    if (userRole === 'user') {
      window.location.replace('http://localhost:5173/dashboard')
      return
    }
    next('/dashboard')
    return
  }

  if (to.meta.requiresAuth) {
    if (!isAuthenticated) {
      next('/login')
    } else if (userRole === 'user') {
      window.location.replace('http://localhost:5173/dashboard')
      return
    } else if (to.meta.role && to.meta.role.toLowerCase() !== userRole) {
      next('/dashboard')
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router