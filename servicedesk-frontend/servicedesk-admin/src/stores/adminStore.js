import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

const API_URL = 'http://localhost:3000'

export const useAdminStore = defineStore('admin', () => {
  const currentUser = ref({
    id: 5,
    name: 'Admin User',
    email: 'admin@servicedesk.com',
    role: 'admin',
    avatar: 'AU',
    phone: '+62 812-3456-7890'
  })

  const tickets = ref([])
  const users = ref([])
  const categories = ref([])
  const employees = ref([]) 
  const activities = ref([])

  async function fetchData() {
    try {
      const [ticketsRes, usersRes, categoriesRes, employeesRes, activitiesRes] = await Promise.all([
        fetch(`${API_URL}/tickets`),
        fetch(`${API_URL}/users`),
        fetch(`${API_URL}/categories`),
        fetch(`${API_URL}/employees`).catch(() => ({ ok: false })),
        fetch(`${API_URL}/activities`).catch(() => ({ ok: false }))
      ])

      if (!ticketsRes.ok || !usersRes.ok || !categoriesRes.ok) {
        throw new Error('Gagal mengambil data dari server')
      }

      tickets.value = await ticketsRes.json()
      users.value = await usersRes.json()
      categories.value = await categoriesRes.json()
      
      if (employeesRes && employeesRes.ok) {
        employees.value = await employeesRes.json()
      }
      
      if (activitiesRes && activitiesRes.ok) {
        const activitiesData = await activitiesRes.json()
        activities.value = activitiesData.sort((a, b) => 
          new Date(b.timestamp).getTime() - new Date(a.timestamp).getTime()
        )
      }
    } catch (error) {
      console.error('Failed to fetch data:', error)
    }
  }

  async function addActivity(activityData) {
    const newActivity = {
      id: Date.now().toString() + Math.random().toString(36).substring(2, 7),
      timestamp: new Date().toISOString().replace('T', ' ').substring(0, 19),
      ...activityData
    }
    
    console.log('📝 [DEBUG] Mencoba menyimpan activity:', newActivity)
    
    try {
      const res = await fetch(`${API_URL}/activities`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(newActivity)
      })
      
      if (res.ok) {
        const created = await res.json()
        activities.value.unshift(created)
        console.log('✅ [DEBUG] Activity berhasil disimpan ke database!')
      } else {
        const errorText = await res.text()
        console.error('❌ [DEBUG] Gagal menyimpan activity. Status:', res.status, 'Error:', errorText)
      }
    } catch (error) {
      console.error('❌ [DEBUG] Error network saat menyimpan activity:', error)
    }
    
    return newActivity
  }

  const stats = computed(() => ({
    totalTickets: tickets.value.length,
    openTickets: tickets.value.filter(t => t.status === 'Open').length,
    inProgressTickets: tickets.value.filter(t => t.status === 'In Progress').length,
    resolvedTickets: tickets.value.filter(t => t.status === 'Resolved').length
  }))

  async function addTicket(ticketData) {
    const maxIdNum = tickets.value.length > 0 
      ? Math.max(...tickets.value.map(t => {
          const num = parseInt(String(t.id).replace('TKT-', ''))
          return isNaN(num) ? 0 : num
        }))
      : 0
    
    const newId = `TKT-${String(maxIdNum + 1).padStart(3, '0')}`

    const payload = {
      id: newId,
      ...ticketData,
      comments: ticketData.comments || [],
      file: ticketData.file || null,
      attachments: ticketData.attachments || [],
      createdAt: new Date().toISOString().replace('T', ' ').substring(0, 19),
      updatedAt: new Date().toISOString().replace('T', ' ').substring(0, 19),
      timeAgo: 'Just now'
    }

    const res = await fetch(`${API_URL}/tickets`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })

    if (!res.ok) throw new Error('Gagal menambahkan tiket')

    const created = await res.json()
    tickets.value.unshift(created)

    await addActivity({
      type: 'ticket_created',
      description: `Created a new ticket: ${ticketData.title}`,
      user: currentUser.value?.name || 'Unknown User',
      details: { ticket: newId }
    })
    
    return created
  }

  async function updateTicket(id, updates) {
    const oldTicket = tickets.value.find(t => String(t.id) === String(id))
    
    console.log('🔄 [DEBUG] Updating ticket. ID:', id, 'Old Ticket:', oldTicket, 'Updates:', updates)

    const res = await fetch(`${API_URL}/tickets/${id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ 
        ...updates, 
        updatedAt: new Date().toISOString().replace('T', ' ').substring(0, 19) 
      })
    })
    
    if (!res.ok) throw new Error('Gagal memperbarui tiket')

    const updated = await res.json()
    const index = tickets.value.findIndex(t => String(t.id) === String(id))
    if (index !== -1) tickets.value[index] = updated
    
    const userName = currentUser.value?.name || 'Unknown User'
    if (oldTicket) {
      if (String(updates.assignedTo) !== String(oldTicket.assignedTo)) {
        console.log('📌 [DEBUG] Trigger: assignedTo berubah')
        await addActivity({
          type: 'ticket_assigned',
          description: `Assigned ticket ${id} to ${updates.assignedTo || 'Unassigned'}`,
          user: userName,
          details: { ticket: id }
        })
      }
      if (updates.status && String(updates.status) !== String(oldTicket.status)) {
        console.log('📌 [DEBUG] Trigger: status berubah')
        await addActivity({
          type: 'status_changed',
          description: `Changed status of ticket ${id} from ${oldTicket.status} to ${updates.status}`,
          user: userName,
          details: { ticket: id, from: oldTicket.status, to: updates.status }
        })
      }
      if (updates.comments && updates.comments.length > (oldTicket.comments?.length || 0)) {
        console.log('📌 [DEBUG] Trigger: comment bertambah')
        await addActivity({
          type: 'comment_added',
          description: `Added a comment on ticket ${id}`,
          user: userName,
          details: { ticket: id }
        })
      }
    } else {
      console.warn('⚠️ [DEBUG] oldTicket tidak ditemukan untuk ID:', id)
    }
    
    return true
  }

  async function deleteTicket(id) {
    const res = await fetch(`${API_URL}/tickets/${id}`, { method: 'DELETE' })
    if (!res.ok) throw new Error('Gagal menghapus tiket')

    await addActivity({
      type: 'ticket_deleted',
      description: `Deleted ticket ${id}`,
      user: currentUser.value?.name || 'Unknown User',
      details: { ticket: id }
    })

    tickets.value = tickets.value.filter(t => String(t.id) !== String(id))
    return true
  }

  function getTicketById(id) {
    return tickets.value.find(t => String(t.id) === String(id))
  }

  function searchTickets(query, filters = {}) {
    return tickets.value.filter(ticket => {
      const matchesSearch = query === '' || 
        ticket.id.toLowerCase().includes(query.toLowerCase()) ||
        ticket.title.toLowerCase().includes(query.toLowerCase()) ||
        ticket.description.toLowerCase().includes(query.toLowerCase())
      
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

  async function addUser(userData) {
    const newId = users.value.length > 0 
      ? Math.max(...users.value.map(u => typeof u.id === 'number' ? u.id : 0)) + 1 
      : 1

    const payload = {
      id: newId,
      ...userData,
      avatar: userData.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase(),
      password: userData.password || 'password'
    }

    const res = await fetch(`${API_URL}/users`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })

    if (!res.ok) throw new Error('Gagal menambahkan user')

    const created = await res.json()
    users.value.push(created)

    await addActivity({
      type: 'user_created',
      description: `Created new user account: ${userData.name}`,
      user: currentUser.value?.name || 'Unknown User',
      details: { user: userData.name }
    })
    
    return created
  }

  async function updateUser(id, updates) {
    const oldUser = users.value.find(u => String(u.id) === String(id))

    const res = await fetch(`${API_URL}/users/${id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(updates)
    })

    if (!res.ok) throw new Error('Gagal memperbarui user')

    const updated = await res.json()
    const index = users.value.findIndex(u => String(u.id) === String(id))
    if (index !== -1) users.value[index] = updated

    if (oldUser) {
      await addActivity({
        type: 'user_updated',
        description: `Updated user account: ${oldUser.name}`,
        user: currentUser.value?.name || 'Unknown User',
        details: { user: updated.name }
      })
    }
    
    return true
  }

  async function deleteUser(id) {
    const oldUser = users.value.find(u => String(u.id) === String(id))

    const res = await fetch(`${API_URL}/users/${id}`, { method: 'DELETE' })
    if (!res.ok) throw new Error('Gagal menghapus user')

    if (oldUser) {
      await addActivity({
        type: 'user_deleted',
        description: `Deleted user account: ${oldUser.name}`,
        user: currentUser.value?.name || 'Unknown User',
        details: { user: oldUser.name }
      })
    }

    users.value = users.value.filter(u => String(u.id) !== String(id))
    return true
  }

  async function addCategory(categoryData) {
    const newId = Date.now().toString(36) + Math.random().toString(36).substring(2, 7)
    
    const payload = { 
      id: newId, 
      ...categoryData, 
      ticketCount: 0 
    }
    
    const res = await fetch(`${API_URL}/categories`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    })

    if (!res.ok) throw new Error('Gagal menambahkan kategori')

    const created = await res.json()
    categories.value.push(created)

    await addActivity({
      type: 'category_created',
      description: `Created new category: ${categoryData.name}`,
      user: currentUser.value?.name || 'Unknown User',
      details: { category: categoryData.name }
    })

    return true
  }

  async function updateCategory(id, updates) {
    const oldCategory = categories.value.find(c => String(c.id) === String(id))

    const res = await fetch(`${API_URL}/categories/${id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(updates)
    })

    if (!res.ok) throw new Error('Gagal memperbarui kategori')

    const updated = await res.json()
    const index = categories.value.findIndex(c => String(c.id) === String(id))
    if (index !== -1) {
      categories.value[index] = updated
    }

    if (oldCategory) {
      await addActivity({
        type: 'category_updated',
        description: `Updated category: ${oldCategory.name}`,
        user: currentUser.value?.name || 'Unknown User',
        details: { category: updated.name }
      })
    }
    
    return true
  }

  async function deleteCategory(id) {
    const oldCategory = categories.value.find(c => String(c.id) === String(id))

    const res = await fetch(`${API_URL}/categories/${id}`, { method: 'DELETE' })
    if (!res.ok) throw new Error('Gagal menghapus kategori')

    if (oldCategory) {
      await addActivity({
        type: 'category_deleted',
        description: `Deleted category: ${oldCategory.name}`,
        user: currentUser.value?.name || 'Unknown User',
        details: { category: oldCategory.name }
      })
    }

    categories.value = categories.value.filter(c => String(c.id) !== String(id))
    return true
  }

  return {
    currentUser,
    tickets,
    users,
    categories,
    employees,
    activities,
    stats,
    fetchData,
    addActivity,
    addTicket,       
    updateTicket,
    deleteTicket,
    getTicketById,
    searchTickets,
    addUser,
    updateUser,
    deleteUser,
    addCategory,
    updateCategory,
    deleteCategory
  }
})