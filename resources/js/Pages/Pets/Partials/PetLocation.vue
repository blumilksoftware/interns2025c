<template>
  <div class="mx-auto max-w-6xl px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-gray-800 py-8 px-6 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
      <h2 class="heading-xl dark:text-white mb-6 text-center">
        📍 Lokalizacja schroniska
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-4">
          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">Schronisko dla Zwierząt "Przyjazne Łapki"</h3>
              <p class="text-gray-600 dark:text-gray-300">ul. Leśna 15, 00-001 Warszawa</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0 w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">Godziny otwarcia</h3>
              <p class="text-gray-600 dark:text-gray-300">Pon-Pt: 9:00 - 18:00<br>Weekend: 10:00 - 16:00</p>
            </div>
          </div>
          
          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0 w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
              </svg>
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">Kontakt</h3>
              <p class="text-gray-600 dark:text-gray-300">Tel: +48 22 123 45 67<br>Email: kontakt@przyjaznelapki.pl</p>
            </div>
          </div>
        </div>
        
        <div class="space-y-4">
          <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 h-48 flex items-center justify-center">
            <div class="text-center">
              <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
              </svg>
              <p class="text-gray-500 dark:text-gray-400 text-sm">Podgląd mapy</p>
            </div>
          </div>
          
          <a 
            href="https://maps.google.com/?q=ul.+Leśna+15,+Warszawa" 
            target="_blank" 
            rel="noopener noreferrer"
            class="inline-flex items-center justify-center w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 group"
          >
            <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            Otwórz w Google Maps
          </a>
          
          <div class="text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
              🚗 Parking dostępny na miejscu<br>
              🚌 Autobus: linie 123, 456 (przystanek "Schronisko")
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
</script>

<style scoped>
</style>
