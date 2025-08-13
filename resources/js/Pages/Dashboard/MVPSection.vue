<script setup>
import { useI18n } from 'vue-i18n'
import { computed, ref } from 'vue'
import { HeartIcon, StarIcon, CalendarIcon, MapPinIcon, ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/20/solid'

const { t } = useI18n()

const showPersonality = ref(false)
const showCharacteristics = ref(false)

const petData = {
  name: 'Rex',
  type: 'Dog',
  breed: 'Golden Retriever',
  age: 5,
  status: 'Available',
  shelter: 'Happy Paws',
  location: 'Warsaw',
  created_at: '2024-01-15',
  personality: [
    t('landing.mvp.friendly'),
    t('landing.mvp.goodWithChildren'),
    t('landing.mvp.intelligent'),
    t('landing.mvp.lovesOutdoor'),
    t('landing.mvp.familyCompanion'),
    t('landing.mvp.wellTrained')
  ]
}

const characteristics = computed(() => [
  `${t('landing.mvp.age')}: ${petData.age} ${t('landing.mvp.yearsOld')}`,
  `${t('landing.mvp.breed')}: ${petData.breed}`,
  `${t('landing.mvp.status')}: ${t('landing.mvp.availableForAdoption')}`,
  `${t('landing.mvp.location')}: ${petData.shelter}, ${petData.location}`,
  `${t('landing.mvp.health')}: ${t('landing.mvp.vaccinated')}`,
  `${t('landing.mvp.temperament')}: ${t('landing.mvp.gentle')}`
])

const togglePersonality = () => {
  showPersonality.value = !showPersonality.value
}

const toggleCharacteristics = () => {
  showCharacteristics.value = !showCharacteristics.value
}
</script>

<template>
<div class="overflow-hidden py-8 sm:py-12">
    <div class="relative isolate">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
         <div class="mx-auto flex max-w-4xl bg-gradient-to-br from-white to-yellow-50 flex-col sm:flex-row sm:ring-2 ring-[#FFD700] sm:shadow-[0_0_30px_rgba(255,215,0,0.3)] rounded-xl sm:rounded-2xl lg:mx-0 lg:max-w-none xl:gap-x-16">
           <div class="flex items-center justify-center sm:relative overflow-hidden flex-shrink-0 w-full sm:w-72 md:w-80 lg:w-1/3 aspect-[4/3] md:aspect-auto min-h-[240px] md:min-h-[340px] lg:min-h-[420px] rounded-xl lg:rounded-l-xl md:rounded-r-none sm:ring-2 sm:ring-[#FFD700] sm:hadow-[0_0_20px_rgba(255,215,0,0.4)] md:ring-0 md:shadow-none">
             <img class="w-4/5 sm:w-full rounded-xl md:rounded-none h-full object-cover object-center" src="https://images.unsplash.com/photo-1552053831-71594a27632d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=800&q=80" alt="Rex the Golden Retriever" />
            </div>
                                                                                            <div class="w-full flex-auto px-4 py-4 sm:px-8 sm:py-8">
              <div class="flex items-center gap-2 mb-3">
                <HeartIcon class="h-5 w-5 text-red-500" />
                <span class="text-sm font-bold text-red-600">{{ t('landing.mvp.featuredPet') }}</span>
              </div>
              <h2 class="text-xl sm:text-2xl md:text-4xl font-bold tracking-tight text-pretty text-[#3B2F1A]">{{ t('landing.mvp.meetPet') }} {{ petData.name }}</h2>
              <p class="mt-2 sm:mt-3 text-sm/6 sm:text-base/6 font-medium text-pretty text-gray-700">{{ t('landing.mvp.description', { breed: petData.breed, name: petData.name }) }}</p>
            
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm font-medium">
              <div class="flex items-center gap-2">
                <CalendarIcon class="h-5 w-5 text-gray-600" />
                <span class="text-gray-700">{{ petData.age }} {{ t('landing.mvp.yearsOld') }}</span>
              </div>
              <div class="flex items-center gap-2">
                <MapPinIcon class="h-5 w-5 text-gray-600" />
                <span class="text-gray-700">{{ petData.shelter }}</span>
              </div>
            </div>

            <div class="mt-4">
               <button 
                 @click="togglePersonality" 
                 class="flex items-center justify-between w-full text-base font-bold text-[#3B2F1A] mb-3 lg:hidden hover:text-gray-700 transition-colors"
                 aria-expanded="showPersonality"
               >
                <span>{{ t('landing.mvp.personalityTraits') }}</span>
                <ChevronDownIcon v-if="!showPersonality" class="h-5 w-5" />
                <ChevronUpIcon v-else class="h-5 w-5" />
              </button>
              <h3 class="text-base font-bold text-[#3B2F1A] mb-3 hidden lg:block">{{ t('landing.mvp.personalityTraits') }}</h3>
              <ul role="list" class="grid grid-cols-1 gap-x-6 gap-y-2 text-sm/5 font-medium text-gray-700 sm:grid-cols-2 lg:grid" :class="{ 'hidden lg:block': !showPersonality }">
                <li v-for="trait in petData.personality" :key="trait" class="flex gap-x-3">
                  <StarIcon class="h-5 w-5 flex-none text-yellow-500" aria-hidden="true" />
                  {{ trait }}
                </li>
              </ul>
            </div>

            <div class="mt-4">
               <button 
                 @click="toggleCharacteristics" 
                 class="flex items-center justify-between w-full text-base font-bold text-[#3B2F1A] mb-3 lg:hidden hover:text-gray-700 transition-colors"
                 aria-expanded="showCharacteristics"
               >
                <span>{{ t('landing.mvp.characteristics') }}</span>
                <ChevronDownIcon v-if="!showCharacteristics" class="h-5 w-5" />
                <ChevronUpIcon v-else class="h-5 w-5" />
              </button>
              <h3 class="text-base font-bold text-[#3B2F1A] mb-3 hidden lg:block">{{ t('landing.mvp.characteristics') }}</h3>
              <ul role="list" class="grid grid-cols-1 gap-x-6 gap-y-2 text-sm/5 font-medium text-gray-700 sm:grid-cols-2 lg:grid" :class="{ 'hidden lg:block': !showCharacteristics }">
                <li v-for="characteristic in characteristics" :key="characteristic" class="flex gap-x-3">
                  <div class="h-2 w-2 rounded-full bg-blue-500 mt-2 flex-none"></div>
                  {{ characteristic }}
                </li>
              </ul>
            </div>

            <div class="mt-6 flex">
              <a href="#" class="text-sm/6 font-bold text-indigo-600 hover:text-indigo-500">
                {{ t('landing.mvp.adoptPet') }} {{ petData.name }}
                <span aria-hidden="true">&rarr;</span>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="absolute inset-x-0 -top-6 -z-10 flex transform-gpu justify-center overflow-hidden blur-3xl" aria-hidden="true">
        <div class=""/>
      </div>
    </div>
  </div>
</template>
<style scoped>

</style>