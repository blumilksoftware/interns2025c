<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { useI18n } from 'vue-i18n'
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'

const { t } = useI18n()

defineProps({
  sessions: {
    type: Array,
    default: () => [],
  },
})
</script>

<template>
  <AppLayout :title="t('titles.userProfile')">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Profile
      </h2>
    </template>

    <div>
      <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8 space-y-16">
        <div v-if="$page.props.jetstream.canUpdateProfileInformation">
          <UpdateProfileInformationForm :user="$page.props.auth.user" />
        </div>

        <div v-if="$page.props.jetstream.canUpdatePassword">
          <UpdatePasswordForm />
        </div>

        <div>
          <LogoutOtherBrowserSessionsForm :sessions="sessions" />
        </div>

        <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
          <DeleteUserForm />
        </template>
      </div>
    </div>
  </AppLayout>
</template>
