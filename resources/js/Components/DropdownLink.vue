<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  href: {
    type: String,
    default: '',
  },
  as: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <div>
    <button v-if="as == 'button'" type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-900 font-semibold transition duration-150 ease-in-out hover:cursor-pointer hover:bg-soft-cream">
      <slot />
    </button>

    <a v-else-if="as =='a'" :href="href" class="block px-4 py-2 text-sm leading-5 text-gray-900 font-semibold transition duration-150 ease-in-out hover:cursor-pointer hover:bg-soft-cream">
      <slot />
    </a>

    <Link v-else :href="href" class="block px-4 py-2 text-sm leading-5 text-gray-900 hover:bg-soft-cream font-semibold transition duration-150 ease-in-out hover:cursor-pointer">
      <slot />
    </Link>
  </div>
</template>
