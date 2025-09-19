<template>
  <component :is="component" :class="classes" />
</template>

<script setup>
import { computed } from 'vue'
import {
  IconCake,
  IconDog,
  IconGenderMale,
  IconGenderFemale,
  IconCircleCheck,
  IconHeart,
  IconMoodSmile,
  IconMoodSad,
  IconBolt,
  IconAlertCircle,
  IconHelpCircle,
  IconFileDescription,
  IconVaccine,
  IconBug,
  IconCpu,
  IconScissors,
} from '@tabler/icons-vue'

const props = defineProps({
  name: {
    type: String,
    required: true,
    validator: (value) => [
      'age', 'breed', 'male', 'female', 'default', 'heart', 
      'healthy', 'sick', 'recovering', 'critical', 'unknown',
      'treatment', 'vaccinated', 'dewormed', 'microchipped', 'sterilized',
    ].includes(value),
  },
})

const map = {
  'age': { comp: IconCake, class: 'mt-1 size-4 text-amber-500 flex-none' },
  'breed': { comp: IconDog, class: 'mt-1 size-4 text-green-400 flex-none' },
  'male': { comp: IconGenderMale, class: 'mt-1 size-4 text-sky-500 flex-none' },
  'female': { comp: IconGenderFemale, class: 'mt-1 size-4 text-pink-500 flex-none' },
  'default': { comp: IconCircleCheck, class: 'mt-1 size-4 text-blue-500 flex-none' },
  'heart': { comp: IconHeart, class: 'size-5 text-blue-600 dark:text-blue-400' },
  'healthy': { comp: IconMoodSmile, class: 'size-4 text-green-600 dark:text-green-400' },
  'sick': { comp: IconMoodSad, class: 'size-4 text-red-600 dark:text-red-400' },
  'recovering': { comp: IconBolt, class: 'size-4 text-yellow-600 dark:text-yellow-400' },
  'critical': { comp: IconAlertCircle, class: 'size-4 text-red-600 dark:text-red-400' },
  'unknown': { comp: IconHelpCircle, class: 'size-4 text-gray-600 dark:text-gray-400' },
  'treatment': { comp: IconFileDescription, class: 'size-3 text-amber-600 dark:text-amber-400' },
  'vaccinated': { comp: IconVaccine, class: 'size-4 text-green-600 dark:text-green-400' },
  'dewormed': { comp: IconBug, class: 'size-4 text-amber-600 dark:text-amber-400' },
  'microchipped': { comp: IconCpu, class: 'size-4 text-cyan-600 dark:text-cyan-400' },
  'sterilized': { comp: IconScissors, class: 'size-4 text-fuchsia-600 dark:text-fuchsia-400' },
}

const component = computed(() => map[props.name].comp)
const classes = computed(() => map[props.name].class)
</script>
