import { ref } from 'vue'

export function useNotification() {
  const notificationRef = ref(null)

  function show(type, message, title = null, duration = 4000) {
    if (notificationRef.value) {
      notificationRef.value.show(type, message, title, duration)
    }
  }

  function success(message, title = 'Success!', duration = 4000) {
    show('success', message, title, duration)
  }

  function error(message, title = 'Error', duration = 4000) {
    show('error', message, title, duration)
  }

  function warning(message, title = 'Warning', duration = 4000) {
    show('warning', message, title, duration)
  }

  function info(message, title = 'Information', duration = 4000) {
    show('info', message, title, duration)
  }

  return {
    notificationRef,
    show,
    success,
    error,
    warning,
    info
  }
}