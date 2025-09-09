<script setup>
import Modal from './Modal.vue'

const emit = defineEmits(['close'])

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  maxWidth: {
    type: String,
    default: '2xl',
  },
  closeable: {
    type: Boolean,
    default: true,
  },
})

const close = () => {
  emit('close')
}
</script>

<template>
  <Modal
    :show="show"
    :max-width="maxWidth"
    :closeable="closeable"
    @close="close"
  >
    <div class="px-4 py-5 sm:p-6">
      <div class="text-lg font-medium text-gray-900">
        <slot name="title" />
      </div>

      <div class="mt-1 text-sm text-gray-600">
        <slot name="content" />
      </div>
    </div>

    <div class="flex flex-row justify-end px-4 py-3 sm:px-6 text-end">
      <slot name="footer" />
    </div>
  </Modal>
</template>
