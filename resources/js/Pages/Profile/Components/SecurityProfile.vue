<script setup>
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue'
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
import { usePage } from '@inertiajs/vue3'
const props = defineProps({
  sessions: {
    type: Array,
    default: () => [],
  },
})

const page = usePage()

</script>

<template>
  <div class="flex flex-col">
    <div>
      <LogoutOtherBrowserSessionsForm :sessions="sessions" />
    </div>

    <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
      <DeleteUserForm />
    </template>
  </div>
</template>
