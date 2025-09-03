<script setup>
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue'
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue'
import { routes } from '@/routes'

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

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <div class="mb-4 text-center text-m text-gray-600 dark:text-gray-400">
      {{ t('auth.emailVerification') }}
    </div>

    <div v-if="verificationLinkSent" class="mb-4 text-center font-medium text-sm text-green-600 dark:text-green-400">
      {{ t('auth.emailVerificationSent') }}
    </div>

    <form @submit.prevent="submit">
      <div class="mt-4 flex flex-col items-center justify-between gap-4">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          {{ t('auth.resendVerificationEmail') }}
        </PrimaryButton>

        <div class="flex items-center justify-around w-full">
          <Link
            :href="routes.profile.show()"
            class="cursor-pointer"
          >
            <PrimaryButton>
              {{ t('auth.editProfile') }}
            </PrimaryButton>
          </Link>

          <Link
            :href="routes.logout()"
            method="post"
            class="cursor-pointer"
          >
            <PrimaryButton>
              {{ t('auth.logOut') }}
            </PrimaryButton>
          </Link>
        </div>
      </div>
    </form>
  </AuthenticationCard>
</template>
