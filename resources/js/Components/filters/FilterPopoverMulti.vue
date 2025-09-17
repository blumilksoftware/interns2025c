<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  filterId: { type: String, required: true },
  label: { type: String, required: true },
  options: { type: Array, required: true },
  modelValue: { type: Array, default: () => [] },
  open: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue', 'update:open', 'changed'])

const { t } = useI18n()

const selected = computed({
  get: () => Array.isArray(props.modelValue) ? props.modelValue : [],
  set: (newValue) => {
    emit('update:modelValue', newValue)
    emit('changed', props.filterId)
  },
})

const isOpen = computed({
  get: () => props.open,
  set: (newValue) => emit('update:open', newValue),
})

const summary = computed(() => {
  if (!Array.isArray(selected.value) || selected.value.length === 0) return t('preferences.placeholders.any')
  const map = new Map(props.options.map(o => [o.value, o.labelKey ? t(o.labelKey) : (o.label || o.value)]))
  return selected.value.map(v => map.get(v) || v).join(', ')
})

const ariaLabel = computed(() => {
  const s = summary.value
  const anyText = t('preferences.placeholders.any')
  return s === anyText ? props.label : `${props.label}: ${s}`
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
  <div ref="rootRef" class="filter-item" :data-filter-id="filterId" :style="{ zIndex: isOpen ? 1000 : 'auto' }">
    <span class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</span>
    <div class="relative z-30">
      <button type="button" :aria-label="ariaLabel" :aria-expanded="isOpen" class="w-full text-left cursor-pointer text-black rounded-md border border-gray-300 px-3 py-2 flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-150 ease-in-out" @click="isOpen = !isOpen">
        <span>{{ summary }}</span>
        <div class="flex items-center gap-2">
          <span v-if="selected.length > 0" class="bg-indigo-600 text-white text-xs px-2 py-1 rounded-full">{{ selected.length }}</span>
          <svg class="size-4 text-gray-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
          </svg>
        </div>
      </button>
      <div v-if="isOpen" class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg p-2">
        <div class="max-h-60 overflow-auto">
          <label v-for="opt in options" :key="opt.value" class="checkbox-wrapper cursor-pointer flex items-center gap-2 text-sm text-gray-700 px-2 py-1 rounded hover:bg-gray-50">
            <input v-model="selected" type="checkbox" :value="opt.value" class="rounded border-gray-300 transition-all duration-150 ease-in-out checked:scale-105">
            <span>{{ opt.labelKey ? t(opt.labelKey) : (opt.label || opt.value) }}</span>
          </label>
        </div>
        <div class="mt-2 flex justify-end gap-2">
          <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition-all cursor-pointer duration-150 ease-in-out" @click="selected = [];">{{ t('preferences.placeholders.any') }}</button>
          <button type="button" class="text-xs px-2 py-1 rounded-md bg-indigo-600 text-white hover:bg-indigo-500 transition-all cursor-pointer duration-150 ease-in-out" @click="isOpen = false">{{ t('preferences.actions.ok') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>
