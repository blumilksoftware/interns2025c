<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import ActionMessage from '@/Components/ActionMessage.vue'
import FormSection from '@/Components/FormSection.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import AuthButton from '@/Components/Buttons/AuthButton.vue'
import AuthTextInput from '@/Components/AuthTextInput.vue'
import { routes } from '@/routes'

const passwordInput = ref(null)
const currentPasswordInput = ref(null)

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const updatePassword = () => {
  form.put(routes.profile.passwordUpdate(), {
    errorBag: 'updatePassword',
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation')
        passwordInput.value.focus()
      }

      if (form.errors.current_password) {
        form.reset('current_password')
        currentPasswordInput.value.focus()
      }
    },
  })
}
</script>

<template>
  <FormSection @submitted="updatePassword">
    <template #title>
      <span class="text-gray-900 text-sm transition-all duration-150 ease-out font-semibold">Update Password</span>
    </template>

    <template #description>
      <span class="text-gray-400 text-sm transition-all duration-150 ease-out font-semibold">Ensure your account is using a long, random password to stay secure.</span>
    </template>

    <template #form>
      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="current_password" value="Current Password" />
        <AuthTextInput
          id="current_password"
          ref="currentPasswordInput"
          v-model="form.current_password"
          type="password"
          class="mt-1 block w-full"
          autocomplete="current-password"
        />
        <InputError :message="form.errors.current_password" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="password" value="New Password" />
        <AuthTextInput
          id="password"
          ref="passwordInput"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full"
          autocomplete="new-password"
        />
        <InputError :message="form.errors.password" class="mt-2" />
      </div>

      <div class="col-span-6 sm:col-span-4">
        <InputLabel for="password_confirmation" value="Confirm Password" />
        <AuthTextInput
          id="password_confirmation"
          v-model="form.password_confirmation"
          type="password"
          class="mt-1 block w-full"
          autocomplete="new-password"
        />
        <InputError :message="form.errors.password_confirmation" class="mt-2" />
      </div>
    </template>

    <template #actions>
      <ActionMessage :on="form.recentlySuccessful" class="me-3">
        Saved.
      </ActionMessage>

      <AuthButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        Save
      </AuthButton>
    </template>
  </FormSection>
</template>
