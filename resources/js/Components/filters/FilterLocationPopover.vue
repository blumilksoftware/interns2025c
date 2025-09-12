<script setup>
import { computed, ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps({
  filterId: { type: String, default: 'location' },
  label: { type: String, required: true },
  modelValue: { type: String, default: '' },
  open: { type: Boolean, default: false },
  recentLocations: { type: Array, default: () => [] },
  filteredLocations: { type: Array, default: () => [] },
})

const emit = defineEmits(['update:modelValue', 'update:open', 'use', 'clear', 'changed'])

const { t } = useI18n()

const valueProxy = computed({
  get: () => props.modelValue,
  set: (newValue) => {
    emit('update:modelValue', newValue)
    emit('changed', props.filterId)
  },
})

const isOpen = computed({
  get: () => props.open,
  set: (newValue) => emit('update:open', newValue),
})

const rootRef = ref(null)
function handleClickOutside(e) {
  if (!isOpen.value) return
  const root = rootRef.value
  if (root && !root.contains(e.target)) isOpen.value = false
}
onMounted(() => document.addEventListener('click', handleClickOutside, true))
onBeforeUnmount(() => document.removeEventListener('click', handleClickOutside, true))

const loading = ref(false)
const errorMsg = ref('')
const remoteResults = ref([]) // { label, display, lat, lon, kind, importance }
const cache = new Map()
let inFlight = null

let debounceTimer = null
function debounceFetch(q) {
  clearTimeout(debounceTimer)
  if (!q || q.trim().length < 3) { remoteResults.value = []; loading.value = false; errorMsg.value = ''; return }
  loading.value = true; errorMsg.value = ''
  debounceTimer = setTimeout(() => fetchNominatim(q.trim()), 400)
}

function normalize(s) { return (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim() }

function rankOf(kind) {
  if (kind === 'city') return 3
  if (kind === 'town') return 2
  if (kind === 'village') return 1
  return 0
}

function dedupeByCanonical(items) {
  const pick = new Map()
  for (const it of items) {
    const key = normalize(`${it.main || ''}|${it.county || ''}|${it.state || ''}`)
    const prev = pick.get(key)
    if (!prev) { pick.set(key, it); continue }
    const a = rankOf(it.kind) + (Number(it.importance) || 0)
    const b = rankOf(prev.kind) + (Number(prev.importance) || 0)
    if (a > b) pick.set(key, it)
  }
  const seenLabel = new Set()
  const out = []
  for (const it of pick.values()) {
    const lk = normalize(it.label)
    if (seenLabel.has(lk)) continue
    seenLabel.add(lk)
    out.push(it)
  }
  return out
}

async function fetchNominatim(q) {
  const nq = normalize(q)
  if (cache.has(nq)) { remoteResults.value = cache.get(nq); loading.value = false; return }
  if (inFlight) inFlight.abort()
  inFlight = new AbortController()
  try {
    const viewbox = '14.07,54.84,24.15,49.00'
    const url = `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&countrycodes=pl&bounded=1&viewbox=${viewbox}&limit=25&q=${encodeURIComponent(q)}`
    const res = await fetch(url, { headers: { 'Accept': 'application/json', 'Accept-Language': 'pl,en' }, signal: inFlight.signal })
    if (!res.ok) throw new Error('HTTP')
    const data = await res.json()
    const raw = (Array.isArray(data) ? data : []).map(it => {
      const a = it.address || {}
      const main = a.city || a.town || a.village || a.hamlet || (it.display_name?.split(',')[0] || it.name) || ''
      const county = a.county || a.municipality || ''
      const state = a.state || ''
      const admin = state || county
      const label = [main, admin].filter(Boolean).join(', ')
      const kind = a.city ? 'city' : a.town ? 'town' : a.village ? 'village' : 'other'
      return { label: label || it.display_name, display: it.display_name, lat: it.lat, lon: it.lon, kind, importance: it.importance || 0, main, county, state }
    })
    const sorted = raw.sort((a,b) => {
      const rk = rankOf(b.kind) - rankOf(a.kind)
      if (rk !== 0) return rk
      return (Number(b.importance)||0) - (Number(a.importance)||0)
    })
    const results = dedupeByCanonical(sorted).slice(0, 12)
    cache.set(nq, results)
    remoteResults.value = results
    loading.value = false
  } catch (e) {
    if (e?.name === 'AbortError') return
    loading.value = false
    errorMsg.value = t('preferences.location.loadingError')
    remoteResults.value = []
  }
}

watch(() => valueProxy.value, (q) => { if (isOpen.value) debounceFetch(q) })

const listRef = ref(null)
const itemHeight = 36 // px
const viewportHeight = 240 // px
const scrollTop = ref(0)
function onScroll() { scrollTop.value = listRef.value ? listRef.value.scrollTop : 0 }
const startIndex = computed(() => Math.max(0, Math.floor(scrollTop.value / itemHeight) - 3))
const endIndex = computed(() => startIndex.value + Math.ceil(viewportHeight / itemHeight) + 6)
const totalHeight = computed(() => (remoteResults.value.length * itemHeight))
const visibleItems = computed(() => remoteResults.value.slice(startIndex.value, endIndex.value))
const offsetTop = computed(() => startIndex.value * itemHeight)

function pickRemote(item) { emit('use', item.label) }
</script>

<template>
  <div ref="rootRef" class="filter-item transition-all duration-200 ease-in-out hover:-translate-y-0.5 hover:shadow-lg" :data-filter-id="filterId" :style="{ zIndex: isOpen ? 1000 : 'auto' }">
    <label class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
    <div class="sm:relative z-[3000]">
      <input :id="`${filterId}-input`" v-model="valueProxy" type="text" class="w-full rounded-md border-gray-300 focus:border-indigo-500 transition-all duration-150 ease-in-out focus:scale-[1.01] focus:ring-2 focus:ring-indigo-500/10" :placeholder="t('preferences.placeholders.cityOrZip')" @focus="isOpen = true" @input="isOpen = true; $emit('changed', filterId)">
      <div v-if="isOpen" class="mt-1 w-full bg-white border border-gray-200 rounded-md p-2 sm:absolute sm:z-[4000] sm:shadow-lg">
        <template v-if="(valueProxy || '').length >= 3">
          <div class="px-2 py-1 text-xs font-semibold uppercase text-gray-500">{{ t('preferences.location.suggestions') }}</div>
          <div ref="listRef" :style="{ height: viewportHeight + 'px' }" class="relative overflow-auto" @scroll="onScroll">
            <div :style="{ height: totalHeight + 'px', position: 'relative' }">
              <div :style="{ position: 'absolute', top: offsetTop + 'px', left: 0, right: 0 }">
                <button v-for="it in visibleItems" :key="it.display + it.lat + it.lon" type="button" class="w-full text-left px-2 py-1 rounded hover:bg-gray-50 text-sm text-gray-700" @click="pickRemote(it)">
                  {{ it.label }}
                </button>
              </div>
            </div>
          </div>
          <div v-if="loading" class="flex items-center gap-2 p-2 text-xs text-gray-500">
            <svg class="animate-spin size-4 text-indigo-600" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
            </svg>
            <span>{{ t('preferences.location.loading') }}</span>
          </div>
          <div v-if="!loading && visibleItems.length === 0" class="px-2 py-1 text-xs text-gray-500">{{ t('preferences.location.noResults') }}</div>
        </template>

        <template v-else>
          <div class="max-h-64 overflow-auto">
            <template v-if="valueProxy && filteredLocations.length > 0">
              <div class="px-2 py-1 text-xs font-semibold uppercase text-gray-500">{{ t('preferences.location.results') }}</div>
              <button v-for="loc in filteredLocations" :key="'f-'+loc" type="button" class="w-full text-left px-2 py-1 rounded hover:bg-gray-50 text-sm text-gray-700" @click="$emit('use', loc)">
                {{ loc }}
              </button>
            </template>
            <template v-else-if="valueProxy">
              <div class="px-2 py-1 text-xs text-gray-500">{{ t('preferences.location.noResultsUseEntered') }}</div>
            </template>
            <div v-if="recentLocations.length > 0" class="mt-2">
              <div class="px-2 py-1 text-xs font-semibold uppercase text-gray-500">{{ t('preferences.location.recent') }}</div>
              <button v-for="loc in recentLocations" :key="'r-'+loc" type="button" class="w-full text-left px-2 py-1 rounded hover:bg-gray-50 text-sm text-gray-700" @click="$emit('use', loc)">
                {{ loc }}
              </button>
            </div>
          </div>
        </template>

        <div class="mt-2 flex justify-between gap-2">
          <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100" @click="$emit('clear')">{{ t('preferences.actions.clear') }}</button>
          <button type="button" class="text-xs px-2 py-1 rounded-md bg-indigo-600 text-white hover:bg-indigo-500" @click="$emit('use', valueProxy)">{{ t('preferences.location.useThis') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>
