<script setup>
import { Link } from '@inertiajs/vue3'
import { logo } from '@/helpers/mappers/logo'
</script>

<template>
  <Link :href="'/'">
    <img :src="logo.Logo300x300" alt="Logo" class="mx-auto w-1/2">
  </Link>
</template>
