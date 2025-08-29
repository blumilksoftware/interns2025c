<template>
  <component :is="component" v-bind="$attrs" />
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
  IconMoodKid,
  IconMan,
  IconOld,
  IconHorse,
  IconWeight,
  IconDog,
  IconMoodSad,
  IconMoodNeutral,
  IconMoodSmile,
  IconHeart,
  IconMoodEmpty,
  IconZzz,
  IconWalk,
  IconRun,
  IconBolt,
  IconFlame,
} from '@tabler/icons-vue'

const props = defineProps({
  name: {
    type: String,
    required: true,
    validator: (value) => [
      'arrow-left', 'location', 'phone', 'email', 'external-link',
      'chevron-left', 'chevron-right', 'user', 'settings', 'logout',
      'close', 'edit', 'delete', 'plus', 'search', 'warning',
      'desktop', 'mobile', 'mood-kid', 'man', 'old', 'horse',
      'weight', 'dog', 'mood-sad', 'mood-neutral', 'mood-smile', 'heart', 'mood-empty',
      'zzz', 'walk', 'run', 'bolt', 'flame',
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
  'mood-kid': { comp: IconMoodKid },
  'man': { comp: IconMan },
  'old': { comp: IconOld },
  'horse': { comp: IconHorse },
  'weight': { comp: IconWeight },
  'dog': { comp: IconDog },
  'mood-sad': { comp: IconMoodSad },
  'mood-neutral': { comp: IconMoodNeutral },
  'mood-smile': { comp: IconMoodSmile },
  'heart': { comp: IconHeart },
  'mood-empty': { comp: IconMoodEmpty },
  'zzz': { comp: IconZzz },
  'walk': { comp: IconWalk },
  'run': { comp: IconRun },
  'bolt': { comp: IconBolt },
  'flame': { comp: IconFlame },
}

const component = computed(() => map[props.name].comp)
</script>
