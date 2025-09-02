<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  filterId: { type: String, required: true },
  label: { type: String, required: true },
  options: { type: Array, required: true },
  modelValue: { type: [String, Number, null], default: '' },
  open: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue', 'update:open'])

const { t } = useI18n()

const valueProxy = computed({
  get: () => props.modelValue,
  set: (val) => {
    emit('update:modelValue', val)
    emit('changed', props.filterId)
  }
})

const isOpen = computed({
  get: () => props.open,
  set: (val) => emit('update:open', val)
})

const summary = computed(() => {
  const v = valueProxy.value
  if (!v) return t('preferences.placeholders.any')
  const opt = props.options.find(o => o.value === v)
  return opt ? (opt.labelKey ? t(opt.labelKey) : (opt.label || v)) : v
})

const rootRef = ref(null)
function handleClickOutside(event) {
  if (!isOpen.value) return
  const root = rootRef.value
  if (root && !root.contains(event.target)) {
    isOpen.value = false
  }
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside, true)
})

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside, true)
})
</script>

<template>
  <div class="filter-item" :data-filter-id="filterId" :style="{ zIndex: isOpen ? 1000 : 'auto' }" ref="rootRef">
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">{{ label }}</label>
    <div class="relative z-30">
      <button type="button" @click="isOpen = !isOpen" class="w-full text-left text-black dark:text-gray-100 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-900 px-3 py-2 flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <span>{{ summary }}</span>
        <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
        </svg>
      </button>
      <div v-if="isOpen" class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg p-2">
        <div class="max-h-60 overflow-auto">
          <label v-for="opt in options" :key="opt.value" class="checkbox-wrapper flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200 px-2 py-1 rounded hover:bg-gray-50 dark:hover:bg-gray-800">
            <input type="radio" :name="filterId" :value="opt.value" v-model="valueProxy" class="border-gray-300 dark:border-gray-700" />
            <span>{{ opt.labelKey ? t(opt.labelKey) : (opt.label || opt.value) }}</span>
          </label>
        </div>
        <div class="mt-2 flex justify-end gap-2">
          <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="valueProxy = ''">{{ t('preferences.placeholders.any') }}</button>
          <button type="button" class="text-xs px-2 py-1 rounded-md bg-indigo-600 text-white hover:bg-indigo-500" @click="isOpen = false">OK</button>
        </div>
      </div>
    </div>
  </div>
</template>


