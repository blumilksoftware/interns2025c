<script setup lang="ts">
import { computed } from 'vue'
import CommonIcons from '@/Components/Icons/CommonIcons.vue'

type ChoiceOption = {
  value: number | string
  icon?: string
  iconClass?: string
  label?: string
  labelKey?: string
}

const props = defineProps<{
  modelValue: number | string | null | Array<number | string>
  options: ChoiceOption[]
  columns?: number
  anyLabel?: string
  multiple?: boolean
}>()

const emit = defineEmits<{ (e: 'update:modelValue', value: number | string | null | Array<number | string>): void }>()

function toggle(value: number | string) {
  if (props.multiple) {
    const current = Array.isArray(props.modelValue) ? [...props.modelValue] as Array<number | string> : []
    const idx = current.findIndex(v => v === value)
    if (idx === -1) current.push(value)
    else current.splice(idx, 1)
    emit('update:modelValue', current)
  } else {
    emit('update:modelValue', props.modelValue === value ? null : value)
  }
}

// Ensure Tailwind sees concrete class names; map common cases
const gridClasses = computed(() => {
  const cols = props.columns ?? 3
  switch (cols) {
    case 1: return 'grid grid-cols-1 gap-3'
    case 2: return 'grid grid-cols-2 gap-3'
    case 3: return 'grid grid-cols-3 gap-3'
    case 4: return 'grid grid-cols-4 gap-3'
    case 5: return 'grid grid-cols-5 gap-3'
    default: return 'grid grid-cols-3 gap-3'
  }
})
</script>

<template>
  <div>
    <div :class="gridClasses">
      <label v-for="opt in options" :key="String(opt.value)" class="tile-checkbox">
        <input
          type="checkbox"
          class="sr-only"
          :checked="multiple ? Array.isArray(modelValue) && modelValue.includes(opt.value) : modelValue === opt.value"
          @change="toggle(opt.value)"
        />
        <div class="checkbox-content">
          <div class="icon-container">
            <CommonIcons v-if="opt.icon" :name="opt.icon" :class="opt.iconClass || 'w-5 h-5'" />
          </div>
          <span class="text-xs font-medium">
            <div class="label-container">
              <slot name="label" :option="opt">
                {{ opt.label || opt.labelKey }}
              </slot>
            </div>
          </span>
        </div>
      </label>
    </div>
  </div>
</template>

<style scoped>
</style>


