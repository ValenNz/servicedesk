<template>
  <span
    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold"
    :class="priorityClasses"
  >
    {{ priority }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  priority: {
    type: String,
    required: true
  }
})

const priorityClasses = computed(() => {
  const classes = {
    'High': 'bg-red-50 text-red-700 border border-red-200',
    'Medium': 'bg-yellow-50 text-yellow-700 border border-yellow-200',
    'Low': 'bg-green-50 text-green-700 border border-green-200'
  }
  return classes[props.priority] || 'bg-gray-50 text-gray-700'
})
</script>