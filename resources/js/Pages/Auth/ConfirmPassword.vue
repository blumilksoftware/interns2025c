<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import { routes } from '@/routes'
import { useI18n } from 'vue-i18n'

const form = useForm({
  password: '',
})

const { t } = useI18n()

const passwordInput = ref(null)

const submit = () => {
  form.post(routes.password.confirm(), {
    onFinish: () => {
      form.reset()

      passwordInput.value.focus()
    },
  })
}
</script>

<template>
  <Head :title="t('titles.confirmPassword')" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
      {{ t('auth.confirmPassword') }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="password" :value="t('auth.password')" />
        <TextInput
          id="password"
          ref="passwordInput"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full"
          required
          autocomplete="current-password"
          autofocus
        />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="flex justify-end mt-4">
        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          {{ t('confirm') }}
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
