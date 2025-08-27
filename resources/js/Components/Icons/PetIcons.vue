<template>
  <!-- Age Icon -->
  <svg v-if="name === 'age'" class="mt-1 size-4 text-amber-500 flex-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M17 10c.7-.7 1.69 0 2.5 0a2.5 2.5 0 1 0 0-5 .5.5 0 0 1-.5-.5 2.5 2.5 0 1 0-5 0c0 .81.7 1.8 0 2.5l-7 7c-.7.7-1.69 0-2.5 0a2.5 2.5 0 0 0 0 5c.28 0 .5.22.5.5a2.5 2.5 0 1 0 5 0c0-.81-.7-1.8 0-2.5Z" />
  </svg>

  <!-- Breed Icon -->
  <svg v-else-if="name === 'breed'" class="mt-1 size-4 text-green-400 flex-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M20 3v10a8 8 0 1 1 -16 0v-10l3.432 3.432a7.963 7.963 0 0 1 4.568 -1.432c1.769 0 3.403 .574 4.728 1.546l3.272 -3.546z" />
    <path d="M2 16h5l-4 4" />
    <path d="M22 16h-5l4 4" />
    <path d="M12 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
    <path d="M9 11v.01" />
    <path d="M15 11v.01" />
  </svg>

  <!-- Male Gender Icon -->
  <svg v-else-if="name === 'male'" class="mt-1 size-4 text-sky-500 flex-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <circle cx="9" cy="15" r="5" />
    <path d="M14 10l6-6" />
    <path d="M15 4h5v5" />
  </svg>

  <!-- Female Gender Icon -->
  <svg v-else-if="name === 'female'" class="mt-1 size-4 text-pink-500 flex-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <circle cx="12" cy="8" r="5" />
    <path d="M12 13v8" />
    <path d="M9 18h6" />
  </svg>

  <!-- Default Icon -->
  <svg v-else-if="name === 'default'" class="mt-1 size-4 text-blue-500 flex-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M9 12l2 2 4-4" />
    <circle cx="12" cy="12" r="9" />
  </svg>

  <!-- Heart Icon -->
  <svg v-else-if="name === 'heart'" class="size-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
  </svg>

  <!-- Healthy Status Icon -->
  <svg v-else-if="name === 'healthy'" class="size-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>

  <!-- Sick Status Icon -->
  <svg v-else-if="name === 'sick'" class="size-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
  </svg>

  <!-- Recovering Status Icon -->
  <svg v-else-if="name === 'recovering'" class="size-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
  </svg>

  <!-- Critical Status Icon -->
  <svg v-else-if="name === 'critical'" class="size-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>

  <!-- Unknown Status Icon -->
  <svg v-else-if="name === 'unknown'" class="size-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>

  <!-- Treatment Icon -->
  <svg v-else-if="name === 'treatment'" class="size-3 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
  </svg>

  <!-- Vaccinated Icon -->
  <svg v-else-if="name === 'vaccinated'" class="size-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>

  <!-- Dewormed Icon -->
  <svg v-else-if="name === 'dewormed'" class="size-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>

  <!-- Microchipped Icon -->
  <svg v-else-if="name === 'microchipped'" class="size-4 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <rect x="4" y="4" width="16" height="16" rx="3" />
    <path d="M8 8h8M8 12h8M8 16h5" />
  </svg>

  <!-- Sterilized Icon -->
  <svg v-else-if="name === 'sterilized'" class="size-4 text-fuchsia-600 dark:text-fuchsia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
    <path d="M17 3v1c-.007 2.46 -.91 4.554 -2.705 6.281m-2.295 1.719c-3.328 1.99 -5 4.662 -5.008 8.014v1" />
    <path d="M17 21.014v-1c0 -1.44 -.315 -2.755 -.932 -3.944m-4.068 -4.07c-1.903 -1.138 -3.263 -2.485 -4.082 -4.068" />
    <path d="M8 4h9" />
    <path d="M7 20h10" />
    <path d="M12 8h4" />
    <path d="M8 16h8" />
    <path d="M3 3l18 18" />
  </svg>
</template>

<script setup>
defineProps({
  name: {
    type: String,
    required: true,
    validator: (value) => [
      'age', 'breed', 'male', 'female', 'default', 'heart', 
      'healthy', 'sick', 'recovering', 'critical', 'unknown',
      'treatment', 'vaccinated', 'dewormed', 'microchipped', 'sterilized',
    ].includes(value),
  },
})
</script>
