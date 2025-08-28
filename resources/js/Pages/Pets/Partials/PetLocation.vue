<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>

<template>
  <div class="mx-auto max-w-6xl px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 py-8 px-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
      <h2 class="heading-xl dark:text-white mb-6 text-center">
        📍 {{ t('pets.location.heading') }}
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-4">
          <div class="flex items-start space-x-3">
            <div class="shrink-0 size-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
              <svg class="size-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">{{ t('pets.location.shelterName') }}</h3>
              <p class="text-gray-600 dark:text-gray-300">{{ t('pets.location.address') }}</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3">
            <div class="shrink-0 size-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
              <svg class="size-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">{{ t('pets.location.openingHoursTitle') }}</h3>
              <p class="text-gray-600 dark:text-gray-300">{{ t('pets.location.openingHoursWeek') }}<br>{{ t('pets.location.openingHoursWeekend') }}</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3">
            <div class="shrink-0 size-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
              <svg class="size-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">{{ t('pets.location.contactTitle') }}</h3>
              <p class="text-gray-600 dark:text-gray-300">{{ t('pets.location.contactPhone') }}<br>{{ t('pets.location.contactEmail', { email: 'kontakt@przyjaznelapki.pl' }) }}</p>
            </div>
          </div>
        </div>
        
        <div class="space-y-4">
          <div class="rounded-lg overflow-hidden h-48">
            <iframe
              class="size-full border-0"
              loading="lazy"
              allowfullscreen
              referrerpolicy="no-referrer-when-downgrade"
              src="https://www.google.com/maps?q=ul.+Leśna+15,+Warszawa&output=embed"
            />
          </div>
          
          <a 
            href="https://maps.google.com/?q=ul.+Leśna+15,+Warszawa" 
            target="_blank" 
            rel="noopener noreferrer"
            class="inline-flex items-center justify-center w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 group"
          >
            <svg class="size-5 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            {{ t('pets.location.openInGoogleMaps') }}
          </a>
          
          <div class="text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
              {{ t('pets.location.extraInfo') }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
