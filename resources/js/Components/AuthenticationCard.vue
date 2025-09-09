<script setup lang="ts">
defineProps({
  heading: {
    type: String,
    default: '',
  },
})
</script>

<template>
  <div class="min-h-screen flex flex-col sm:justify-center items-center justify-center pt-6 sm:pt-0">
    <div class="flex flex-col p-10 sm:shadow-xl/20 sm:rounded-[50px] bg-white w-full max-w-lg">
      <div>
        <slot name="logo" />
      </div>
      <h1 class="text-2xl font-bold text-left">{{ heading }}</h1>

      <div class="w-full mt-6 overflow-visible sm:rounded-lg">
        <slot />
      </div>
    </div>
  </div>
</template>
