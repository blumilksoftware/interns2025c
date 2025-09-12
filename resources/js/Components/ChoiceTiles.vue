<script setup>
import { computed, ref } from 'vue'
import CommonIcons from '@/Components/Icons/CommonIcons.vue'

const props = defineProps({
  modelValue: { type: [Number, String, Array], default: null },
  options: { type: Array, required: true },
  columns: { type: Number, default: 3 },
  anyLabel: { type: String, default: '' },
  multiple: { type: Boolean, default: false },
})

const emit = defineEmits(['update:modelValue'])

// Refs to tile DOM elements to enable keyboard navigation
const tileRefs = ref([])

function toggle(value) {
  if (props.multiple) {
    const current = Array.isArray(props.modelValue) ? [...props.modelValue] : []
    const idx = current.findIndex(v => v === value)
    if (idx === -1) current.push(value)
    else current.splice(idx, 1)
    emit('update:modelValue', current)
  } else {
    emit('update:modelValue', props.modelValue === value ? null : value)
  }
}

const gridClasses = computed(() => {
  const cols = props.columns ?? 3
  switch (cols) {
  case 1:
    return 'grid grid-cols-1 gap-3'
  case 2:
    return 'grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 gap-3'
  case 3:
    return 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-3'
  case 4:
    return 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3'
  case 5:
    return 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3'
  default:
    return 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-3 gap-3'
  }
})

function moveFocus(currentIndex, delta) {
  const elements = (tileRefs.value || []).filter(Boolean)
  if (!elements.length) return
  let next = currentIndex + delta
  if (next < 0) next = 0
  if (next >= elements.length) next = elements.length - 1
  elements[next]?.focus()
}
</script>

<template>
  <div>
    <div :class="gridClasses">
      <label
        v-for="(opt, idx) in options"
        :key="String(opt.value)"
        class="tile-checkbox focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-500 rounded-lg"
        :tabindex="0"
        :role="multiple ? 'checkbox' : 'radio'"
        :aria-checked="multiple ? (Array.isArray(modelValue) && modelValue.includes(opt.value)) : (modelValue === opt.value)"
        :aria-label="String(opt.label || opt.labelKey || opt.value)"
        :ref="el => (tileRefs[idx] = el)"
        @click.prevent="toggle(opt.value)"
        @keydown.enter.prevent="toggle(opt.value)"
        @keydown.space.prevent="toggle(opt.value)"
        @keydown.right.prevent="moveFocus(idx, 1)"
        @keydown.left.prevent="moveFocus(idx, -1)"
        @keydown.down.prevent="moveFocus(idx, columns || 3)"
        @keydown.up.prevent="moveFocus(idx, -(columns || 3))"
      >
        <input
          type="checkbox"
          class="sr-only"
          tabindex="-1"
          aria-hidden="true"
          :checked="multiple ? Array.isArray(modelValue) && modelValue.includes(opt.value) : modelValue === opt.value"
          @change="toggle(opt.value)"
        >
        <div class="checkbox-content">
          <div class="icon-container">
            <CommonIcons v-if="opt.icon" :name="opt.icon" :class="opt.iconClass || 'w-5 h-5'" />
          </div>
          <span class="text-[11px] sm:text-xs font-medium">
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
