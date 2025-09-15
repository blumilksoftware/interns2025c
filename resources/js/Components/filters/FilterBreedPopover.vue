<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  filterId: { type: String, default: 'breed' },
  label: { type: String, required: true },
  dogBreeds: { type: Array, default: () => [] },
  catBreeds: { type: Array, default: () => [] },
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
  return selected.value.join(', ')
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
  <div ref="rootRef" class="filter-item transition-all duration-200 ease-in-out hover:shadow-lg" :data-filter-id="filterId" :style="{ zIndex: isOpen ? 1000 : 'auto' }">
    <span class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</span>
    <div class="relative">
      <button type="button" :disabled="(dogBreeds.length + catBreeds.length) === 0" class="w-full text-left text-black rounded-md border border-gray-300 px-3 py-2 flex items-center justify-between focus:outline-none focus:ring-2 focus:ring-indigo-500 disabled:opacity-50" @click="isOpen = !isOpen">
        <span>{{ summary }}</span>
        <div class="flex items-center gap-2">
          <span v-if="Array.isArray(selected) && selected.length > 0" class="bg-indigo-600 text-white text-xs px-2 py-1 rounded-full">{{ selected.length }}</span>
          <svg class="size-4 text-gray-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
          </svg>
        </div>
      </button>
      <div v-if="isOpen" class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg p-2">
        <div class="max-h-64 overflow-auto space-y-2">
          <div v-if="dogBreeds.length > 0">
            <div class="px-2 py-1 text-xs font-semibold uppercase text-gray-500">{{ t('preferences.breeds.dogs') }}</div>
            <label v-for="dogBreed in dogBreeds" :key="'dog-'+dogBreed" class="checkbox-wrapper flex items-center gap-2 text-sm text-gray-700 px-2 py-1 rounded hover:bg-gray-50">
              <input v-model="selected" type="checkbox" :value="dogBreed" class="rounded border-gray-300 ">
              <span>{{ dogBreed }}</span>
            </label>
          </div>
          <div v-if="catBreeds.length > 0">
            <div class="px-2 py-1 text-xs font-semibold uppercase text-gray-500">{{ t('preferences.breeds.cats') }}</div>
            <label v-for="catBreed in catBreeds" :key="'cat-'+catBreed" class="checkbox-wrapper flex items-center gap-2 text-sm text-gray-700 px-2 py-1 rounded hover:bg-gray-50">
              <input v-model="selected" type="checkbox" :value="catBreed" class="rounded border-gray-300">
              <span>{{ catBreed }}</span>
            </label>
          </div>
        </div>
        <div class="mt-2 flex justify-end gap-2">
          <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100" @click="selected = []">{{ t('preferences.placeholders.any') }}</button>
          <button type="button" class="text-xs px-2 py-1 rounded-md bg-indigo-600 text-white hover:bg-indigo-500" @click="isOpen = false">{{ t('preferences.actions.ok') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>
