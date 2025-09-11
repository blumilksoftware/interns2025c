import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { routes } from '@/routes'

export function useCrud() {
  const isLoading = ref(false)
  const error = ref(null)

  const getUrlForDataType = (dataSetType, operation, itemId = null) => {
    const routeMap = {
      pets: routes.pets,
      incomingPetsRequests: routes.pets,
      shelters: routes.petShelters,
      users: routes.users,
    }

    const routeGroup = routeMap[dataSetType]
    if (!routeGroup) {
      throw new Error(`No routes found for data type: ${dataSetType}`)
    }

    switch (operation) {
    case 'update':
      return routeGroup.update(itemId)
    case 'destroy':
      return routeGroup.destroy(itemId)
    default:
      throw new Error(`Unknown operation: ${operation}`)
    }
  }

  const updateItem = async (dataSetType, item, onSuccess = null, onError = null) => {
    console.log('UPDATE ITEM', dataSetType, item)
    if (isLoading.value) return

    isLoading.value = true
    error.value = null

    try {
      const updateUrl = getUrlForDataType(dataSetType, 'update', item.id)

      const dataToSend = { ...item }
      delete dataToSend.created_at
      delete dataToSend.updated_at
      delete dataToSend.deleted_at
      delete dataToSend.id

      await router.put(updateUrl, dataToSend, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
          if (onSuccess) onSuccess(response)
        },
        onError: (errors) => {
          error.value = errors
          console.error('Update failed:', errors)
          if (onError) onError(errors)
        },
      })
    } catch (err) {
      error.value = err.message
      console.error('Update error:', err)
      if (onError) onError(err)
    } finally {
      isLoading.value = false
    }
  }

  const deleteItem = async (dataSetType, item, onSuccess = null, onError = null) => {
    if (isLoading.value) return

    isLoading.value = true
    error.value = null

    try {
      const deleteUrl = getUrlForDataType(dataSetType, 'destroy', item.id)

      await router.delete(deleteUrl, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (response) => {
          if (onSuccess) onSuccess(response)
        },
        onError: (errors) => {
          error.value = errors
          console.error('Delete failed:', errors)
          if (onError) onError(errors)
        },
      })
    } catch (err) {
      error.value = err.message
      console.error('Delete error:', err)
      if (onError) onError(err)
    } finally {
      isLoading.value = false
    }
  }

  const clearError = () => {
    error.value = null
  }

  return {
    isLoading,
    error,
    updateItem,
    deleteItem,
    clearError,
  }
}
