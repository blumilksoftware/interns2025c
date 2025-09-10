<script setup>
import { ref, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'

const props = defineProps({
  query: { type: String, default: '' },
  radiusKm: { type: [Number, null], default: null },
})

const containerRef = ref(null)
let mapInstance = null
let marker = null
let circle = null
let polygon = null
let leaflet = null

let polandGeo = null
const geoCache = new Map()

const POLAND_BOUNDS = [
  [49.0, 14.07],
  [54.84, 24.15],
]

function isWholeCountry(query) {
  const normalizedQuery = (query || '').trim().toLowerCase()
  return normalizedQuery === 'polska' || normalizedQuery === 'cała polska' || normalizedQuery === 'cala polska'
}
function normalizeString(text) { return (text || '').toLowerCase().trim() }

async function ensureLeafletLoaded() {
  if (leaflet) return
  leaflet = await import('https://unpkg.com/leaflet@1.9.4/dist/leaflet-src.esm.js')
  if (!document.getElementById('leaflet-css')) {
    const link = document.createElement('link')
    link.id = 'leaflet-css'
    link.rel = 'stylesheet'
    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
    document.head.appendChild(link)
  }
}

async function geocodeNominatim(query, signal) {
  const viewbox = '14.07,54.84,24.15,49.00'
  const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&bounded=1&viewbox=${viewbox}&q=${encodeURIComponent(query)}`
  const res = await fetch(url, { headers: { 'Accept': 'application/json', 'Accept-Language': 'pl,en' }, signal })
  if (!res.ok) throw new Error('nom http')
  const data = await res.json()
  if (!Array.isArray(data) || data.length === 0) throw new Error('nom empty')
  const { lat, lon } = data[0]
  return [parseFloat(lat), parseFloat(lon)]
}

async function fetchPolandGeoJSON() {
  if (polandGeo) return polandGeo
  try {
    const url = 'https://nominatim.openstreetmap.org/search?country=Poland&polygon_geojson=1&format=json'
    const res = await fetch(url, { headers: { 'Accept': 'application/json' } })
    if (!res.ok) throw new Error('geojson http')
    const data = await res.json()
    const item = Array.isArray(data) ? data.find(entry => entry?.geojson) : null
    if (item?.geojson) { polandGeo = item.geojson; return polandGeo }
    throw new Error('geojson empty')
  } catch {
    return null
  }
}

function renderCenter(center) {
  if (!mapInstance) return
  if (!marker) marker = leaflet.marker(center).addTo(mapInstance)
  else marker.setLatLng(center)
  const radiusMeters = (props.radiusKm ? Number(props.radiusKm) : 0) * 1000
  if (radiusMeters > 0) {
    if (!circle) circle = leaflet.circle(center, { radius: radiusMeters, color: '#4f46e5', fillColor: '#6366f1', fillOpacity: 0.15 }).addTo(mapInstance)
    else { circle.setLatLng(center); circle.setRadius(radiusMeters) }
    try { mapInstance.fitBounds(circle.getBounds(), { padding: [20, 20] }) } catch { mapInstance.setView(center, 11) }
  } else {
    if (circle) { mapInstance.removeLayer(circle); circle = null }
    mapInstance.setView(center, 11)
  }
}

let inFlight = null
let debounceTimer = null

async function render() {
  await ensureLeafletLoaded()
  if (!containerRef.value) {
    await nextTick()
    if (!containerRef.value) return
  }
  const searchQueryRaw = (props.query || '')
  const searchQuery = searchQueryRaw.trim()

  if (!mapInstance) {
    mapInstance = leaflet.map(containerRef.value, { scrollWheelZoom: false, attributionControl: true })
    leaflet.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap contributors' }).addTo(mapInstance)
  }

  if (mapInstance && polygon) { mapInstance.removeLayer(polygon); polygon = null }

  if (isWholeCountry(searchQuery) || searchQuery === '') {
    const geo = await fetchPolandGeoJSON()
    if (!mapInstance) return
    if (geo) {
      polygon = leaflet.geoJSON(geo, { style: { color: '#4f46e5', weight: 1, fillColor: '#6366f1', fillOpacity: 0.15 } }).addTo(mapInstance)
      try { mapInstance.fitBounds(polygon.getBounds(), { padding: [10, 10] }) } catch { mapInstance.fitBounds(POLAND_BOUNDS, { padding: [10, 10] }) }
    } else {
      polygon = leaflet.rectangle(POLAND_BOUNDS, { color: '#4f46e5', weight: 1, fillColor: '#6366f1', fillOpacity: 0.15 }).addTo(mapInstance)
      mapInstance.fitBounds(POLAND_BOUNDS, { padding: [10, 10] })
    }
    if (mapInstance && marker) { mapInstance.removeLayer(marker); marker = null }
    if (mapInstance && circle) { mapInstance.removeLayer(circle); circle = null }
    return
  }

  const cacheKey = normalizeString(searchQuery)
  const cached = geoCache.get(cacheKey)
  if (cached) {
    renderCenter(cached)
  }

  clearTimeout(debounceTimer)
  if (inFlight) inFlight.abort()
  inFlight = new AbortController()
  debounceTimer = setTimeout(async () => {
    try {
      const center = await geocodeNominatim(searchQuery, inFlight.signal)
      if (center) { geoCache.set(cacheKey, center); renderCenter(center) }
    } catch (error) { /* ignore abort/errors */ }
  }, 300)
}

let stopWatch = null
onMounted(async () => {
  await nextTick()
  await render()
  stopWatch = watch(() => [props.query, props.radiusKm], () => { render() }, { deep: false })
})

onBeforeUnmount(() => {
  if (stopWatch) stopWatch()
  if (inFlight) inFlight.abort()
  if (debounceTimer) { clearTimeout(debounceTimer); debounceTimer = null }
  if (mapInstance) { mapInstance.remove(); mapInstance = null }
  marker = null; circle = null; polygon = null
})
</script>

<template>
  <div ref="containerRef" class="w-full" style="height: 260px;" />
</template>
