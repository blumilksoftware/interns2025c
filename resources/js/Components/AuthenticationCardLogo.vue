<script setup>
import { Link } from '@inertiajs/vue3'
import { logo } from '@/helpers/mappers/logo'
</script>

<template>
  <Link :href="'/'">
    <img :src="logo.Logo" alt="Logo pokazujące zwierzęcą łapkę z tekstem Łap Go" class="ml-auto w-1/4">
  </Link>
</template>
