<template>
  <component :is="component" />
</template>

<script setup>
import { computed } from 'vue'
import {
  IconArrowLeft,
  IconMapPin,
  IconPhone,
  IconMail,
  IconExternalLink,
  IconChevronLeft,
  IconChevronRight,
  IconUser,
  IconSettings,
  IconLogout,
  IconX,
  IconPencil,
  IconTrash,
  IconPlus,
  IconSearch,
  IconAlertTriangle,
  IconDeviceDesktop,
  IconDeviceMobile,
} from '@tabler/icons-vue'

const props = defineProps({
  name: {
    type: String,
    required: true,
    validator: (value) => [
      'arrow-left', 'location', 'phone', 'email', 'external-link',
      'chevron-left', 'chevron-right', 'user', 'settings', 'logout',
      'close', 'edit', 'delete', 'plus', 'search', 'warning',
      'desktop', 'mobile',
    ].includes(value),
  },
})

const map = {
  'arrow-left': { comp: IconArrowLeft },
  'location': { comp: IconMapPin },
  'phone': { comp: IconPhone },
  'email': { comp: IconMail },
  'external-link': { comp: IconExternalLink },
  'chevron-left': { comp: IconChevronLeft },
  'chevron-right': { comp: IconChevronRight },
  'user': { comp: IconUser },
  'settings': { comp: IconSettings },
  'logout': { comp: IconLogout },
  'close': { comp: IconX },
  'edit': { comp: IconPencil },
  'delete': { comp: IconTrash },
  'plus': { comp: IconPlus },
  'search': { comp: IconSearch },
  'warning': { comp: IconAlertTriangle },
  'desktop': { comp: IconDeviceDesktop },
  'mobile': { comp: IconDeviceMobile },
}

const component = computed(() => map[props.name].comp)
</script>
