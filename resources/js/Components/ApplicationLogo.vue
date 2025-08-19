<script setup>
import { Link } from '@inertiajs/vue3'
import { routes } from '@/routes'

</script>

<template>
  <Link :href="routes.dashboard">
    <img src="/Images/cat-dog.png" alt="Logo" class="size-10">
  </Link>
</template>
