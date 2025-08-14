<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const animals = [
  {
    id: 4,
    title: 'Max',
    href: '#',
    description: t('landing.imageSection.pets.max.description'),
    imageUrl:
    'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80',
  },
  {
    id: 5,
    title: 'Sophie',
    href: '#',
    description: t('landing.imageSection.pets.sophie.description'),
    imageUrl:
    'https://images.unsplash.com/photo-1513360371669-4adf3dd7dff8?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80',
  },
  {
    id: 6,
    title: 'Oliver',
    href: '#',
    description: t('landing.imageSection.pets.oliver.description'),
    imageUrl:
    'https://images.unsplash.com/photo-1574158622682-e40e69881006?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80',
  },
  {
    id: 7,
    title: 'Buddy',
    href: '#',
    description: t('landing.imageSection.pets.buddy.description'),
    imageUrl:
    'https://images.unsplash.com/photo-1552053831-71594a27632d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80',
  },
  {
    id: 8,
    title: 'Luna',
    href: '#',
    description: t('landing.imageSection.pets.luna.description'),
    imageUrl:
    'https://images.unsplash.com/photo-1547407139-3c921a66005c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80',
  },
  {
    id: 9,
    title: 'Rocky',
    href: '#',
    description: t('landing.imageSection.pets.rocky.description'),
    imageUrl:
    'https://images.unsplash.com/photo-1587300003388-59208cc962cb?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80',
  },
]
</script>

<template>
  <div class="relative py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-4xl font-semibold tracking-tight text-balance text-gray-900 sm:text-5xl">{{ t('landing.imageSection.title') }}</h2>
        <p class="mt-2 text-lg/8 text-gray-600">{{ t('landing.imageSection.subtitle') }}</p>
      </div>
      <div class="mx-auto mt-16 grid max-w-2xl auto-rows-fr grid-cols-1 gap-8 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
        <article v-for="post in animals" :key="post.id" class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pt-80 pb-8 sm:pt-48 lg:pt-80">
          <img :src="post.imageUrl" alt="Pet image" class="absolute inset-0 -z-10 size-full object-cover" width="1200" height="800" loading="lazy" decoding="async">
          <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40" />
          <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-gray-900/10" />
          <h3 class="mt-3 text-lg/6 font-semibold text-white">
            <a :href="post.href">
              <span class="absolute inset-0" />
              {{ post.title }}
            </a>
          </h3>
          <p class="mt-2 text-sm/6 text-gray-300">{{ post.description }}</p>
        </article>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
