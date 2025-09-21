<script setup>
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'

const props = defineProps({
  user: {
    type: Object,
    default: () => ({}),
  },
  sessions: {
    type: Array,
    default: () => [],
  },
})

</script>

<template>
  <div class="flex flex-col min-w-full space-y-8">
    <div v-if="$page.props.jetstream.canUpdateProfileInformation">
      <UpdateProfileInformationForm :user="user" />
    </div>

    <div v-if="$page.props.jetstream.canUpdatePassword">
      <UpdatePasswordForm />
    </div>
  </div>
</template>
