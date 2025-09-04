<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import Checkbox from '@/Components/Checkbox.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import AuthLayout from '@/Pages/Auth/AuthLayout.vue'
import AuthTextInput from '@/Components/AuthTextInput.vue'
import AuthButton from '@/Components/Buttons/AuthButton.vue'
import { routes } from '@/routes'

defineProps({
  canResetPassword: {
    type: Boolean,
    default: false,
  },
  status: {
    type: String,
    default: '',
  },
})

const { t } = useI18n()

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.transform(data => ({
    ...data,
    remember: form.remember ? 'on' : '',
  })).post(routes.login(), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<template>
  <Head :title="t('titles.login')" />
  <AuthLayout>
    <AuthenticationCard :heading="t('titles.login')">
      <template #logo>
        <AuthenticationCardLogo />
      </template>

      <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
        {{ status }}
      </div>

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
            autocomplete="current-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="block mt-4">
          <Checkbox v-model:checked="form.remember" name="remember" />
          <span class="ms-2 text-gray-900 text-sm transition-all duration-150 ease-out font-semibold"> {{ t('auth.remember_me') }} </span>
        </div>
        <div class="mt-8">
          <div class="flex justify-center gap-8 mb-8 ">
            <Link v-if="canResetPassword" :href="routes.password.request()" class="text-gray-400 text-sm transition-all duration-150 ease-out hover:text-gray-900 font-semibold">
              {{ t('auth.forgotPassword') }}
            </Link>
            <Link v-if="canResetPassword" :href="routes.register()" class="text-gray-400 text-sm transition-all duration-150 ease-out hover:text-gray-900 font-semibold">
              {{ t('auth.register') }}
            </Link>
          </div>

          <div>
            <AuthButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              {{ t('auth.login') }}
            </AuthButton>
          </div>
        </div>
      </form>
    </AuthenticationCard>
  </AuthLayout>
</template>
