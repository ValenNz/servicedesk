import { ref, computed, watch } from 'vue'

export function useTableFilter(sourceData, filterFunction) {
  const searchQuery = ref('')
  const filterStatus = ref('')
  const filterCategory = ref('')
  const filterPriority = ref('')
  const filterStartDate = ref('')
  const filterEndDate = ref('')
  
  const currentPage = ref(1)
  const itemsPerPage = ref(10)
  const debounceTimer = ref(null)

  const hasActiveFilters = computed(() => {
    return searchQuery.value || filterStatus.value || filterCategory.value || 
           filterPriority.value || filterStartDate.value || filterEndDate.value
  })


  const filteredData = computed(() => {
    const data = sourceData.value || sourceData
    if (!Array.isArray(data)) return []
    
    return filterFunction(data, {
      searchQuery: searchQuery.value,
      status: filterStatus.value,
      category: filterCategory.value,
      priority: filterPriority.value,
      startDate: filterStartDate.value,
      endDate: filterEndDate.value
    })
  })

  const totalPages = computed(() => Math.max(1, Math.ceil(filteredData.value.length / itemsPerPage.value)))

  const paginatedData = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value
    const end = start + itemsPerPage.value
    return filteredData.value.slice(start, end)
  })

  const startItem = computed(() => {
    return filteredData.value.length === 0 ? 0 : (currentPage.value - 1) * itemsPerPage.value + 1
  })

  const endItem = computed(() => {
    return Math.min(currentPage.value * itemsPerPage.value, filteredData.value.length)
  })

  const displayPages = computed(() => {
    const pages = []
    const maxVisible = 5
    let startPage = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
    let endPage = Math.min(totalPages.value, startPage + maxVisible - 1)
    
    if (endPage - startPage + 1 < maxVisible) {
      startPage = Math.max(1, endPage - maxVisible + 1)
    }
    
    for (let i = startPage; i <= endPage; i++) {
      pages.push(i)
    }
    return pages
  })

  watch([searchQuery, filterStatus, filterCategory, filterPriority, filterStartDate, filterEndDate, itemsPerPage], () => {
    currentPage.value = 1
  })

  const handleSearch = () => {
    clearTimeout(debounceTimer.value)
    debounceTimer.value = setTimeout(() => {}, 300)
  }

  const clearAllFilters = () => {
    searchQuery.value = ''
    filterStatus.value = ''
    filterCategory.value = ''
    filterPriority.value = ''
    filterStartDate.value = ''
    filterEndDate.value = ''
  }

  const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--
  }

  const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++
  }

  return {
    searchQuery,
    filterStatus,
    filterCategory,
    filterPriority,
    filterStartDate,
    filterEndDate,
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
  }
}