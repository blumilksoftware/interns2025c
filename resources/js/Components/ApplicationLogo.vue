<script setup>
import { Link } from '@inertiajs/vue3'
import { routes } from '@/routes'
import { logo } from '@/helpers/mappers/logo'

</script>

<template>
  <Link :href="routes.dashboard()">
    <img :src="logo.Logo" alt="Logo" class="size-10">
  </Link>
</template>
