<script setup>
import PreferencesForm from '@/Components/preferences/PreferencesForm.vue'
import { Head } from '@inertiajs/vue3'

import { useI18n } from 'vue-i18n'

const { t } = useI18n()



</script>

<template>
  <div>
    <Head :title="t('titles.preferences')" />
    <PreferencesForm />
  </div>
</template>
