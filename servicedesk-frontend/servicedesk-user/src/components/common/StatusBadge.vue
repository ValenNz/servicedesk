<template>
  <span
    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
    :class="statusClasses"
  >
    <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="dotClasses"></span>
    {{ status }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  status: {
    type: String,
    required: true
  }
})

const statusClasses = computed(() => {
  const classes = {
    'Open': 'bg-blue-50 text-blue-700',
    'In Progress': 'bg-yellow-50 text-yellow-700',
    'Resolved': 'bg-green-50 text-green-700',
    'Closed': 'bg-gray-50 text-gray-700',
    'Pending': 'bg-orange-50 text-orange-700'
  }
  return classes[props.status] || 'bg-gray-50 text-gray-700'
})

const dotClasses = computed(() => {
  const classes = {
    'Open': 'bg-blue-500',
    'In Progress': 'bg-yellow-500',
    'Resolved': 'bg-green-500',
    'Closed': 'bg-gray-500',
    'Pending': 'bg-orange-500'
  }
  return classes[props.status] || 'bg-gray-500'
})
</script>