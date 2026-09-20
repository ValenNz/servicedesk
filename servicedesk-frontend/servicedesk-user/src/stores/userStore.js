import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

const API_URL = 'http://localhost:3000'

export const useUserStore = defineStore('user', () => {
  const currentUser = ref(null)
  
  const savedUserName = localStorage.getItem('userName')
  const savedUserEmail = localStorage.getItem('userEmail')
  const savedUserId = localStorage.getItem('userId')
  const savedUserRole = localStorage.getItem('userRole')
  
  if (savedUserName && savedUserEmail) {
    currentUser.value = {
      id: savedUserId ? parseInt(savedUserId) : null,
      name: savedUserName,
      email: savedUserEmail,
      role: savedUserRole || 'user',
      avatar: savedUserName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()
    }
  }

  const tickets = ref([])
  const categories = ref([])
  const activities = ref([])

  async function login(email, password) {
    try {
      const res = await fetch(`${API_URL}/users?email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`)
      const usersData = await res.json()
      
      if (usersData.length > 0) {
        const user = usersData[0]
        currentUser.value = { ...user }
        delete currentUser.value.password 
        
        localStorage.setItem('userToken', 'user-token-' + user.id)
        localStorage.setItem('userRole', user.role)
        localStorage.setItem('userName', user.name)
        localStorage.setItem('userEmail', user.email)
        localStorage.setItem('userId', String(user.id))
        
        await addActivity({
          type: 'user_login',
          description: `${user.name} logged in to the system`,
          user: user.name,
          details: {}
        })
        
        return true
      }
      return false
    } catch (error) {
      console.error('Login error:', error)
      return false
    }
  }

  async function logout() {
    if (currentUser.value) {
      await addActivity({
        type: 'user_logout',
        description: `${currentUser.value.name} logged out of the system`,
        user: currentUser.value.name,
        details: {}
      })
    }
    
    currentUser.value = null
    localStorage.removeItem('userToken')
    localStorage.removeItem('userRole')
    localStorage.removeItem('userName')
    localStorage.removeItem('userEmail')
    localStorage.removeItem('userId')
  }

  async function fetchData() {
    try {
      const [ticketsRes, categoriesRes, activitiesRes] = await Promise.all([
        fetch(`${API_URL}/tickets`),
        fetch(`${API_URL}/categories`),
        fetch(`${API_URL}/activities`).catch(() => ({ ok: false }))
      ])

      if (ticketsRes.ok) tickets.value = await ticketsRes.json()
      if (categoriesRes.ok) categories.value = await categoriesRes.json()
      
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


  const myTickets = computed(() => {
    const userName = currentUser.value?.name || localStorage.getItem('userName')
    if (!userName) return []
    
    return tickets.value.filter(t => 
      t.createdBy === userName || t.assignedTo === userName
    )
  })

  const myStats = computed(() => {
    const tickets = myTickets.value
    return {
      total: tickets.length,
      open: tickets.filter(t => t.status === 'Open').length,
      inProgress: tickets.filter(t => t.status === 'In Progress').length,
      resolved: tickets.filter(t => t.status === 'Resolved').length
    }
  })

  async function addActivity(activityData) {
    const newActivity = {
      id: Date.now().toString() + Math.random().toString(36).substring(2, 7),
      timestamp: new Date().toISOString().replace('T', ' ').substring(0, 19),
      ...activityData
    }
    
    try {
      const res = await fetch(`${API_URL}/activities`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(newActivity)
      })
      
      if (res.ok) {
        const created = await res.json()
        activities.value.unshift(created) 
      } else {
        console.error('Failed to save activity to database')
      }
    } catch (error) {
      console.error('Error network saat menyimpan activity:', error)
    }
    
    return newActivity
  }

  async function addTicket(ticketData) {
    const catShort = ticketData.category 
      ? ticketData.category.replace(/[^a-zA-Z]/g, '').substring(0, 3).toUpperCase() 
      : 'GEN' 
    const now = new Date()
    const yy = String(now.getFullYear()).slice(-2)
    const mm = String(now.getMonth() + 1).padStart(2, '0')
    const dd = String(now.getDate()).padStart(2, '0')
    const dateStr = `${yy}${mm}${dd}`

    const prefix = `TKT-${catShort}-${dateStr}-`
    const existingTickets = tickets.value.filter(t => t.id && t.id.startsWith(prefix))
    
    const maxSeq = existingTickets.length > 0 
      ? Math.max(...existingTickets.map(t => {
          const seqStr = t.id.split('-').pop() 
          return parseInt(seqStr, 10) || 0
        }))
      : 0
    
    const newSeq = String(maxSeq + 1).padStart(3, '0')
    const newId = `${prefix}${newSeq}`

    const creatorName = currentUser.value?.name || localStorage.getItem('userName') || 'Unknown User'

    const payload = {
      id: newId,
      ...ticketData,
      createdBy: creatorName, 
      assignedTo: null, 
      status: 'Open',
      comments: [],
      file: ticketData.file || null,
      attachments: ticketData.attachments || [],
      createdAt: now.toISOString().replace('T', ' ').substring(0, 19),
      updatedAt: now.toISOString().replace('T', ' ').substring(0, 19),
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
      user: creatorName,
      details: { ticket: newId }
    })
    
    return created
  }

  function getTicketById(id) {
    return tickets.value.find(t => String(t.id) === String(id))
  }


  async function addComment(ticketId, commentData) {
    const ticket = tickets.value.find(t => String(t.id) === String(ticketId))
    if (!ticket) throw new Error('Tiket tidak ditemukan')

    const authorName = currentUser.value?.name || localStorage.getItem('userName') || 'Unknown User'

    const newComment = {
      author: authorName,
      text: commentData.text || '',
      time: 'Just now',
      attachment: commentData.attachment || null
    }

    const updatedComments = [...(ticket.comments || []), newComment]
    
    let updatedAttachments = [...(ticket.attachments || [])]
    if (commentData.attachment) {
      updatedAttachments.push(commentData.attachment)
    }

    const res = await fetch(`${API_URL}/tickets/${ticketId}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ 
        comments: updatedComments,
        attachments: updatedAttachments,
        updatedAt: new Date().toISOString().replace('T', ' ').substring(0, 19)
      })
    })

    if (!res.ok) throw new Error('Gagal menambahkan komentar')

    const updated = await res.json()
    const index = tickets.value.findIndex(t => String(t.id) === String(ticketId))
    if (index !== -1) tickets.value[index] = updated
    
    await addActivity({
      type: 'comment_added',
      description: `Added a comment on ticket ${ticketId}`,
      user: authorName,
      details: { ticket: ticketId }
    })
    
    return true
  }


  async function updateProfile(updates) {
    if (!currentUser.value) throw new Error('User tidak ditemukan')

    const res = await fetch(`${API_URL}/users/${currentUser.value.id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(updates)
    })

    if (!res.ok) throw new Error('Gagal memperbarui profil')

    const updated = await res.json()
    
    currentUser.value = { ...currentUser.value, ...updated }
    
    localStorage.setItem('userName', updated.name)
    localStorage.setItem('userEmail', updated.email)
    
    await addActivity({
      type: 'user_updated',
      description: `Updated profile information`,
      user: currentUser.value.name,
      details: { user: updated.name }
    })
    
    return true
  }

  return {
    currentUser,
    tickets,
    categories,
    activities,
    myTickets,
    myStats,
    login,
    logout,
    fetchData,
    addActivity,
    addTicket,
    addComment,
    getTicketById,
    updateProfile
  }
})