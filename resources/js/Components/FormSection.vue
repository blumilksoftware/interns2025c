<script setup>
import { computed, useSlots } from 'vue'
import SectionTitle from './SectionTitle.vue'

defineEmits(['submitted'])

const hasActions = computed(() => !! useSlots().actions)
</script>

<template>
  <div class="flex flex-row min-h-[300px] justify-center">
    <div class="flex flex-row justify-around items-center w-1/4 px-8">
      <SectionTitle>
        <template #title>
          <div class="text-end text-xl">
            <slot name="title" />
          </div>
        </template>
        <template #description>
          <div class="mt-2 text-end">
            <slot name="description" />
          </div>
        </template>
      </SectionTitle>
    </div>

    <div class="flex flex-row justify-center items-center w-1/2 px-8">
      <form class="w-full max-w-md" @submit.prevent="$emit('submitted')">
        <div
          class="px-4 py-5 sm:p-6 rounded bg-white shadow "
          :class="hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'"
        >
          <div class="mb-4 gap-6 flex flex-col ">
            <slot name="form" />
          </div>
          <div v-if="hasActions" class="flex items-center justify-end px-4 py-3 text-end sm:px-6 rounded sm:rounded-b-md">
            <slot name="actions" />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
