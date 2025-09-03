<script setup>
import { ref, reactive, nextTick } from 'vue'
import DialogModal from './DialogModal.vue'
import InputError from './InputError.vue'
import PrimaryButton from './Buttons/PrimaryButton.vue'
import SecondaryButton from './Buttons/SecondaryButton.vue'
import TextInput from './TextInput.vue'
import axios from 'axios'
import { routes } from '@/routes'

const emit = defineEmits(['confirmed'])

defineProps({
  title: {
    type: String,
    default: 'Confirm Password',
  },
  content: {
    type: String,
    default: 'For your security, please confirm your password to continue.',
  },
  button: {
    type: String,
    default: 'Confirm',
  },
})

const confirmingPassword = ref(false)

const form = reactive({
  password: '',
  error: '',
  processing: false,
})

const passwordInput = ref(null)

const startConfirmingPassword = () => {
  axios.get(routes.password.confirmationStatus()).then(response => {
    if (response.data.confirmed) {
      emit('confirmed')
    } else {
      confirmingPassword.value = true
      setTimeout(() => passwordInput.value.focus(), 250)
    }
    return response
  }).catch((error) => {
    console.error('Password confirmation check failed:', error)
    throw error
  })
}

const confirmPassword = async () => {
  form.processing = true

  try {
    await axios.post(routes.password.confirm(), {
      password: form.password,
    })
    
    form.processing = false
    closeModal()
    await nextTick()
    emit('confirmed')
  } catch (error) {
    form.processing = false
    form.error = error.response.data.errors.password[0]
    passwordInput.value.focus()
    throw error
  }
}

const closeModal = () => {
  confirmingPassword.value = false
  form.password = ''
  form.error = ''
}
</script>

<template>
  <span>
    <span @click="startConfirmingPassword">
      <slot />
    </span>

    <DialogModal :show="confirmingPassword" @close="closeModal">
      <template #title>
        {{ title }}
      </template>

      <template #content>
        {{ content }}

        <div class="mt-4">
          <TextInput
            ref="passwordInput"
            v-model="form.password"
            type="password"
            class="mt-1 block w-3/4"
            placeholder="Password"
            autocomplete="current-password"
            @keyup.enter="confirmPassword"
          />

          <InputError :message="form.error" class="mt-2" />
        </div>
      </template>

      <template #footer>
        <SecondaryButton @click="closeModal">
          Cancel
        </SecondaryButton>

        <PrimaryButton
          class="ms-3"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
          @click="confirmPassword"
        >
          {{ button }}
        </PrimaryButton>
      </template>
    </DialogModal>
  </span>
</template>
