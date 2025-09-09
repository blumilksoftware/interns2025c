<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import Checkbox from '@/Components/Checkbox.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import AuthButton from '@/Components/Buttons/AuthButton.vue'
import AuthTextInput from '@/Components/AuthTextInput.vue'
import { routes } from '@/routes'
import AuthLayout from '@/Pages/Auth/AuthLayout.vue'

const { t } = useI18n()
const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
})

const submit = () => {
  form.post(routes.register(), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Head :title="t('titles.register')" />

  <AuthLayout>
    <AuthenticationCard :heading="t('titles.register')">
      <template #logo>
        <AuthenticationCardLogo />
      </template>

      <form @submit.prevent="submit">
        <div>
          <InputLabel for="name" :value="t('auth.name')" />
          <AuthTextInput
            id="name"
            v-model="form.name"
            type="text"
            class="mt-1 block w-full"
            required
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div class="mt-4">
          <InputLabel for="email" :value="t('auth.email')" />
          <AuthTextInput
            id="email"
            v-model="form.email"
            type="email"
            class="mt-1 block w-full"
            required
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

        <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
          <InputLabel for="terms">
            <div class="flex items-center">
              <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />
              <div class="ms-2">
                {{ t('terms.agreement') }}
              </div>
            </div>
          </InputLabel>
          <InputError class="mt-2" :message="form.errors.terms" />
        </div>

        <div class="flex flex-col items-center justify-center mt-8">
          <Link
            :href="routes.login()"
            class="text-gray-400  text-sm transition-all duration-150 ease-out hover:text-gray-900 font-semibold mb-4"
          >
            {{ t('auth.alreadyRegistered') }}
          </Link>

          <AuthButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            {{ t('auth.register') }}
          </AuthButton>
        </div>
      </form>
    </AuthenticationCard>
  </AuthLayout>
</template>
