<script setup>
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import AuthButton from '@/Components/Buttons/AuthButton.vue'
import { routes } from '@/routes'
import AuthLayout from '@/Pages/Auth/AuthLayout.vue'

const { t } = useI18n()

const props = defineProps({
  status: {
    type: String,
    default: '',
  },
})

const form = useForm({})

const submit = () => {
  form.post(routes.verification.send())
}

const verificationLinkSent = computed(() => props.status === 'verification-link-sent')
</script>

<template>
  <Head :title="t('titles.emailVerification')" />

  <AuthLayout>
    <AuthenticationCard :heading="t('titles.emailVerification')">
      <template #logo>
        <AuthenticationCardLogo />
      </template>

      <div class="mb-4 text-center text-gray-400 text-sm transition-all duration-150 ease-out font-semibold">
        {{ t('auth.emailVerification') }}
      </div>

      <div v-if="verificationLinkSent" class="mb-4 text-center font-medium text-sm text-green-600">
        {{ t('auth.emailVerificationSent') }}
      </div>

      <form @submit.prevent="submit">
        <div class="mt-8 flex flex-col items-center justify-between gap-8">
          <AuthButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            {{ t('auth.resendVerificationEmail') }}
          </AuthButton>

          <div class="flex items-center justify-around w-full">
            <Link
              :href="routes.profile.show()"
              class="cursor-pointer"
            >
              <AuthButton>
                {{ t('auth.editProfile') }}
              </AuthButton>
            </Link>

            <Link
              :href="routes.logout()"
              method="post"
              class="cursor-pointer"
            >
              <AuthButton>
                {{ t('auth.logOut') }}
              </AuthButton>
            </Link>
          </div>
        </div>
      </form>
    </AuthenticationCard>
  </AuthLayout>
</template>
