<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import AuthButton from '@/Components/Buttons/AuthButton.vue'
import AuthTextInput from '@/Components/AuthTextInput.vue'
import { routes } from '@/routes'
import AuthLayout from '@/Pages/Auth/AuthLayout.vue'

const { t } = useI18n()

const props = defineProps({
  email: {
    type: String,
    default: '',
  },
  token: {
    type: String,
    default: '',
  },
})

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(routes.password.update(), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head :title="t('titles.resetPassword')" />

  <AuthLayout>
    <AuthenticationCard>
      <template #logo>
        <AuthenticationCardLogo />
      </template>

      <form @submit.prevent="submit">
        <div>
          <InputLabel for="email" :value="t('auth.email')" />
          <AuthTextInput
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

        <div class="mt-4">
          <InputLabel for="password" :value="t('auth.password')" />
          <AuthTextInput
            id="password"
            v-model="form.password"
            type="password"
            class="mt-1 block w-full"
            required
            autocomplete="new-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4">
          <InputLabel for="password_confirmation" :value="t('auth.confirmPassword')" />
          <AuthTextInput
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            class="mt-1 block w-full"
            required
            autocomplete="new-password"
          />
          <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>

        <div class="flex items-center justify-end mt-8">
          <AuthButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            {{ t('auth.resetPassword') }}
          </AuthButton>
        </div>
      </form>
    </AuthenticationCard>
  </AuthLayout>
</template>
