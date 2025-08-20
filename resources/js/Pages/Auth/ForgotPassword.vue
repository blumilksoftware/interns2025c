<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { routes } from '@/routes'

defineProps({
  status: {
    type: String,
    default: '',
  },
})

const { t } = useI18n()

const form = useForm({
  email: '',
})

const submit = () => {
  form.post(routes.password.email())
}
</script>

<template>
  <Head :title="t('title.forgotPassword')" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
      {{ t('passwords.instruction') }}
    </div>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
      {{ t(status) }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="email" :value="t('auth.email')" />
        <TextInput
          id="email"
          v-model="form.email"
          type="email"
          class="mt-1 block w-full"
          required
          autofocus
          autocomplete="username"
        />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          {{ t('passwords.resetLink') }}
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
