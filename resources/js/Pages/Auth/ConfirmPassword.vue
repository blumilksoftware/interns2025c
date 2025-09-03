<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import AuthButton from '@/Components/Buttons/AuthButton.vue'
import AuthTextInput from '@/Components/AuthTextInput.vue'
import { routes } from '@/routes'
import { useI18n } from 'vue-i18n'
import AuthLayout from '@/Pages/Auth/AuthLayout.vue'

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

  <AuthLayout>
    <AuthenticationCard>
      <template #logo>
        <AuthenticationCardLogo />
      </template>

      <div class="mb-4 text-gray-400 text-sm transition-all duration-150 ease-out font-semibold">
        {{ t('auth.confirmPassword') }}
      </div>

      <form @submit.prevent="submit">
        <div>
          <InputLabel for="password" :value="t('auth.password')" />
          <AuthTextInput
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

        <div class="flex justify-end mt-8">
          <AuthButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            {{ t('confirm') }}
          </AuthButton>
        </div>
      </form>
    </AuthenticationCard>
  </AuthLayout>
</template>
