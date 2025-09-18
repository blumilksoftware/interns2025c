<script setup>
import { useI18n } from 'vue-i18n'
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { HeartIcon, StarIcon, CalendarIcon, MapPinIcon } from '@heroicons/vue/20/solid'
import { bestMatches } from '@/data/petsData.js'
import { getPetTags } from '@/helpers/mappers'
import { routes } from '@/routes'

const { t } = useI18n()

const petData = bestMatches[0]

const petTags = getPetTags()
const petPersonality = computed(() => {
  if (!petData.tags || !Array.isArray(petData.tags)) return []
  return petData.tags.map(tagId => petTags[tagId]?.name).filter(Boolean)
})

const characteristics = computed(() => [
  `${t('dashboard.mvp.age')}: ${petData.age}`,
  `${t('dashboard.mvp.breed')}: ${petData.breed}`,
  `${t('dashboard.mvp.status')}: ${petData.status}`,
  `${t('dashboard.mvp.gender')}: ${petData.gender === 'male' ? t('dashboard.mvp.male') : t('dashboard.mvp.female')}`,
  `${t('dashboard.mvp.health')}: ${t('dashboard.mvp.vaccinated')}`,
  `${t('dashboard.mvp.temperament')}: ${t('dashboard.mvp.gentle')}`,
])

</script>

<template>
  <div class="overflow-hidden py-8 sm:py-12">
    <div class="relative isolate">
      <div class="flex items-center justify-center">
        <h1 class="text-4xl font-bold text-center text-gray-900">{{ t('dashboard.mvp.title') }}</h1>
      </div>
      <div class="mx-auto max-w-6xl sm:px-6 lg:px-8 py-12">
        <div class="mx-auto flex max-w-4xl bg-gradient-to-br from-white to-yellow-50 flex-col sm:flex-row border-b-2 border-[#FFD700] sm:border-b-0 sm:ring-2 ring-[#FFD700] sm:shadow-[0_0_30px_rgba(255,215,0,0.3)]  sm:rounded-2xl lg:mx-0 lg:max-w-none xl:gap-x-16">
          <div class="flex items-center justify-center sm:relative overflow-hidden shrink-0 w-full sm:w-72 md:w-80 lg:w-1/3 aspect-[4/3] md:aspect-auto min-h-[240px] md:min-h-[340px] lg:min-h-[420px] rounded-xl lg:rounded-l-xl md:rounded-r-none sm:ring-2 sm:ring-[#FFD700] sm:hadow-[0_0_20px_rgba(255,215,0,0.4)] md:ring-0 md:shadow-none">
            <img class="w-4/5 sm:w-full rounded-l-xl md:rounded-l-2xl h-full object-cover object-center" :src="petData.imageUrl" :alt="`${petData.name} - ${petData.breed}`">
          </div>
          <div class="w-full flex-auto p-4 sm:p-8">
            <div class="flex items-center justify-between mb-3">
              <h2 class="text-xl sm:text-2xl md:text-4xl font-bold tracking-tight text-pretty text-[#3B2F1A]">{{ t('dashboard.mvp.meetPet') }} {{ petData.name }}</h2>
              <div class="flex items-center gap-2">
                <HeartIcon class="size-5 text-red-500 animate-heartbeat" />
                <span class="text-sm font-bold text-red-600">{{ t('dashboard.mvp.featuredPet') }}</span>
              </div>
            </div>
            <p class="mt-2 sm:mt-3 text-sm/6 sm:text-base/6 font-medium text-pretty text-gray-700">{{ t('dashboard.mvp.description', { breed: petData.breed, name: petData.name }) }}</p>
            
            <div class="mt-4 flex gap-4 text-sm font-medium">
              <div class="flex items-center gap-2">
                <CalendarIcon class="size-5 text-gray-600" />
                <span class="text-gray-700">{{ petData.age }}</span>
              </div>
              <div class="flex items-center gap-2">
                <MapPinIcon class="size-5 text-gray-600" />
                <span class="text-gray-700">{{ petData.breed }}</span>
              </div>
            </div>

            <div class="mt-4">
              <h3 class="text-base font-bold text-[#3B2F1A] mb-3">{{ t('dashboard.mvp.personalityTraits') }}</h3>
              <ul role="list" class="grid grid-cols-1 gap-x-6 gap-y-2 text-sm/5 font-medium text-gray-700 sm:grid-cols-2 lg:grid">
                <li v-for="trait in petPersonality" :key="trait" class="flex gap-x-3">
                  <StarIcon class="size-5 flex-none text-yellow-500" aria-hidden="true" />
                  {{ trait }}
                </li>
              </ul>
            </div>

            <div class="mt-4">
              <h3 class="text-base font-bold text-[#3B2F1A] mb-3">{{ t('dashboard.mvp.characteristics') }}</h3>
              <ul role="list" class="grid grid-cols-1 gap-x-6 gap-y-2 text-sm/5 font-medium text-gray-700 sm:grid-cols-2 lg:grid">
                <li v-for="characteristic in characteristics" :key="characteristic" class="flex gap-x-3">
                  <div class="size-2 rounded-full bg-blue-500 mt-2 flex-none" />
                  {{ characteristic }}
                </li>
              </ul>
            </div>

            <div class="mt-6 flex">
              <Link :href="routes.pets.show(petData.id)" class="group relative inline-flex items-center justify-center overflow-hidden rounded-md bg-indigo-600 px-6 py-3 text-sm/6 font-bold text-white transition hover:scale-110">
                <span>{{ t('dashboard.mvp.adoptPet') }}</span>
                <div class="absolute inset-0 flex size-full justify-center [transform:skew(-12deg)_translateX(-100%)] group-hover:duration-1000 group-hover:[transform:skew(-12deg)_translateX(100%)]">
                  <div class="relative h-full w-8 bg-white/20" />
                </div>
              </Link>
            </div>
          </div>
        </div>
      </div>
      <div class="absolute inset-x-0 -top-6 -z-10 flex transform-gpu justify-center overflow-hidden blur-3xl" aria-hidden="true">
        <div class="" />
      </div>
    </div>
  </div>
</template>
