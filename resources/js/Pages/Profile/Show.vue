<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useI18n } from 'vue-i18n'
import EditProfile from '@/Pages/Profile/Components/EditProfile.vue'
import SecurityProfile from '@/Pages/Profile/Components/SecurityProfile.vue'
import ProfileSidebar from '@/Pages/Profile/Partials/ProfileSidebar.vue'
import { usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const { t } = useI18n()
defineProps({
  sessions: {
    type: Array,
    default: () => [],
  },
})

const page = usePage()

const user = page.props.auth.user

const view = ref('editProfile')

const handleViewChange = (newView) => {
  view.value = newView
  console.log(view.value)
}

</script>

<template>
  <AppLayout :title="t('titles.userProfile')">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Profile
      </h2>
    </template>
    
    <div class="flex flex-row min-w-full">
      <ProfileSidebar :user="user" @view-change="handleViewChange" />

      <div v-if="view === 'editProfile'" class="flex-1">
        <EditProfile :user="user" />
      </div>

      <div v-if="view === 'security'" class="flex-1">
        <SecurityProfile :sessions="sessions" />
      </div>
    </div>
  </AppLayout>
</template>
