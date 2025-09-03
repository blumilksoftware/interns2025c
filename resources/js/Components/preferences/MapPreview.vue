<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'

const props = defineProps({
  query: { type: String, default: '' },
  radiusKm: { type: [Number, null], default: null },
})

const containerRef = ref(null)
let mapInstance = null
let marker = null
let circle = null
let polygon = null
let L = null

// Caches
let polandGeo = null
const geoCache = new Map()

const POLAND_BOUNDS = [
  [49.0, 14.07],
  [54.84, 24.15],
]

function isWholeCountry(q) {
  const s = (q || '').trim().toLowerCase()
  return s === 'polska' || s === 'cała polska' || s === 'cala polska'
}
function normalize(s) { return (s || '').toLowerCase().trim() }

async function ensureLeafletLoaded() {
  if (L) return
  L = await import('https://unpkg.com/leaflet@1.9.4/dist/leaflet-src.esm.js')
  if (!document.getElementById('leaflet-css')) {
    const link = document.createElement('link')
    link.id = 'leaflet-css'
    link.rel = 'stylesheet'
    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
    document.head.appendChild(link)
  }
}

async function geocodeNominatim(q, signal) {
  const viewbox = '14.07,54.84,24.15,49.00'
  const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&bounded=1&viewbox=${viewbox}&q=${encodeURIComponent(q)}`
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
    const item = Array.isArray(data) ? data.find(it => it?.geojson) : null
    if (item?.geojson) { polandGeo = item.geojson; return polandGeo }
    throw new Error('geojson empty')
  } catch {
    return null
  }
}

function renderCenter(center) {
  // Update marker
  if (!marker) marker = L.marker(center).addTo(mapInstance)
  else marker.setLatLng(center)
  // Update radius
  const radiusMeters = (props.radiusKm ? Number(props.radiusKm) : 0) * 1000
  if (radiusMeters > 0) {
    if (!circle) circle = L.circle(center, { radius: radiusMeters, color: '#4f46e5', fillColor: '#6366f1', fillOpacity: 0.15 }).addTo(mapInstance)
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
  const qRaw = (props.query || '')
  const q = qRaw.trim()

  if (!mapInstance) {
    mapInstance = L.map(containerRef.value, { scrollWheelZoom: false, attributionControl: true })
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap contributors' }).addTo(mapInstance)
  }

  if (polygon) { mapInstance.removeLayer(polygon); polygon = null }

  if (isWholeCountry(q) || q === '') {
    const geo = await fetchPolandGeoJSON()
    if (geo) {
      polygon = L.geoJSON(geo, { style: { color: '#4f46e5', weight: 1, fillColor: '#6366f1', fillOpacity: 0.15 } }).addTo(mapInstance)
      try { mapInstance.fitBounds(polygon.getBounds(), { padding: [10, 10] }) } catch { mapInstance.fitBounds(POLAND_BOUNDS, { padding: [10, 10] }) }
    } else {
      polygon = L.rectangle(POLAND_BOUNDS, { color: '#4f46e5', weight: 1, fillColor: '#6366f1', fillOpacity: 0.15 }).addTo(mapInstance)
      mapInstance.fitBounds(POLAND_BOUNDS, { padding: [10, 10] })
    }
    if (marker) { mapInstance.removeLayer(marker); marker = null }
    if (circle) { mapInstance.removeLayer(circle); circle = null }
    return
  }

  const key = normalize(q)
  const cached = geoCache.get(key)
  if (cached) {
    renderCenter(cached)
  }

  // Debounced fresh geocode with cancel
  clearTimeout(debounceTimer)
  if (inFlight) inFlight.abort()
  inFlight = new AbortController()
  debounceTimer = setTimeout(async () => {
    try {
      const center = await geocodeNominatim(q, inFlight.signal)
      if (center) { geoCache.set(key, center); renderCenter(center) }
    } catch (e) { /* ignore abort/errors */ }
  }, 300)
}

let stopWatch = null
onMounted(() => {
  render()
  stopWatch = watch(() => [props.query, props.radiusKm], () => { render() }, { deep: false })
})

onBeforeUnmount(() => {
  if (stopWatch) stopWatch()
  if (inFlight) inFlight.abort()
  if (mapInstance) { mapInstance.remove(); mapInstance = null }
  marker = null; circle = null; polygon = null
})
</script>

<template>
  <div ref="containerRef" class="w-full" style="height: 260px;" />
</template>
