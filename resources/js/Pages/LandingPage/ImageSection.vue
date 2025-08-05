<script setup>
const posts = [
{
  id: 1,
  title: 'Azul',
  href: '#',
  description:
    'Azul is a 1 year old male cat. He is a very friendly and social cat. He loves to play with his toys and he loves to be around people.',
  imageUrl:
    'https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3603&q=80',
  date: 'Mar 16, 2020',
  datetime: '2020-03-16',
},
{
  id: 2,
  title: 'Bella',
  href: '#',
  description: 'Bella is a 1 year old female cat. She is a very friendly and social cat. She loves to play with her toys and she loves to be around people.',
  imageUrl:
    'https://images.unsplash.com/photo-1547586696-ea22b4d4235d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80',
  date: 'Mar 10, 2020',
  datetime: '2020-03-10',
},
{
  id: 3,
  title: 'Luna',
  href: '#',
  description:
    'Luna is a 1 year old female cat. She is a very friendly and social cat. She loves to play with her toys and she loves to be around people.',
  imageUrl:
    'https://images.unsplash.com/photo-1492724441997-5dc865305da7?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=3270&q=80',
  date: 'Feb 12, 2020',
  datetime: '2020-02-12',
},
]
</script>

<template>
    <div class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-4xl font-semibold tracking-tight text-balance text-gray-900 sm:text-5xl">From the blog</h2>
        <p class="mt-2 text-lg/8 text-gray-600">Learn how to grow your business with our expert advice.</p>
      </div>
      <div class="mx-auto mt-16 grid max-w-2xl auto-rows-fr grid-cols-1 gap-8 sm:mt-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
        <article v-for="post in posts" :key="post.id" class="relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-gray-900 px-8 pt-80 pb-8 sm:pt-48 lg:pt-80">
          <img :src="post.imageUrl" alt="" class="absolute inset-0 -z-10 size-full object-cover" />
          <div class="absolute inset-0 -z-10 bg-gradient-to-t from-gray-900 via-gray-900/40" />
          <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-gray-900/10" />

          <div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm/6 text-gray-300">
            <time :datetime="post.datetime" class="mr-8">{{ post.date }}</time>
          </div>
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