<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import ChoiceTiles from '@/Components/ChoiceTiles.vue'
import FilterPopoverMulti from '@/Components/filters/FilterPopoverMulti.vue'
import FilterPopoverSingle from '@/Components/filters/FilterPopoverSingle.vue'
import FilterBreedPopover from '@/Components/filters/FilterBreedPopover.vue'

const props = defineProps({
  form: { type: Object, required: true },
  selectorConfigs: { type: Object, required: true },
  speciesOptions: { type: Array, required: true },
  sexOptions: { type: Array, required: true },
  colorOptions: { type: Array, required: true },
  dogBreedsAvailable: { type: Object, required: true },
  catBreedsAvailable: { type: Object, required: true },
  speciesOpen: { type: Boolean, required: true },
  breedOpen: { type: Boolean, required: true },
  sexOpen: { type: Boolean, required: true },
  colorOpen: { type: Boolean, required: true },
  moveFilterById: { type: Function, required: true }
})

const emit = defineEmits(['update:speciesOpen','update:breedOpen','update:sexOpen','update:colorOpen'])

const { t } = useI18n()

const speciesOpenModel = computed({
  get: () => props.speciesOpen,
  set: (v) => emit('update:speciesOpen', v)
})

const breedOpenModel = computed({
  get: () => props.breedOpen,
  set: (v) => emit('update:breedOpen', v)
})

const sexOpenModel = computed({
  get: () => props.sexOpen,
  set: (v) => emit('update:sexOpen', v)
})

const colorOpenModel = computed({
  get: () => props.colorOpen,
  set: (v) => emit('update:colorOpen', v)
})
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <FilterPopoverMulti
      filter-id="species"
      :label="t('preferences.labels.species')"
      :options="speciesOptions"
      v-model="form.species"
      v-model:open="speciesOpenModel"
      @changed="() => moveFilterById('species')"
    />

    <FilterBreedPopover
      filter-id="breed"
      :label="t('preferences.labels.breed')"
      :dog-breeds="dogBreedsAvailable"
      :cat-breeds="catBreedsAvailable"
      v-model="form.breed"
      v-model:open="breedOpenModel"
      @changed="() => moveFilterById('breed')"
    />

    <FilterPopoverSingle
      filter-id="sex"
      :label="t('preferences.labels.sex')"
      :options="sexOptions"
      v-model="form.sex"
      v-model:open="sexOpenModel"
      @changed="() => moveFilterById('sex')"
    />

    <FilterPopoverMulti
      filter-id="color"
      :label="t('preferences.labels.color')"
      :options="colorOptions"
      v-model="form.color"
      v-model:open="colorOpenModel"
      @changed="() => moveFilterById('color')"
    />

    <div class="filter-item" data-filter-id="age">
      <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.age') }}</label>
        <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.ageIndex = []; moveFilterById('age')">{{ t('preferences.placeholders.any') }}</button>
      </div>
      <ChoiceTiles :columns="selectorConfigs.age.columns" :options="selectorConfigs.age.options" :model-value="form.ageIndex" :multiple="true" @update:modelValue="val => { form.ageIndex = val; moveFilterById('age') }">
        <template #label="{ option }">
          <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
        </template>
      </ChoiceTiles>
    </div>

    <div class="filter-item" data-filter-id="size">
      <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.size') }}</label>
        <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.sizeIndex = []; moveFilterById('size')">{{ t('preferences.placeholders.any') }}</button>
      </div>
      <ChoiceTiles :columns="selectorConfigs.size.columns" :options="selectorConfigs.size.options" :model-value="form.sizeIndex" :multiple="true" @update:modelValue="val => { form.sizeIndex = val; moveFilterById('size') }">
        <template #label="{ option }">
          <span class="text-sm font-medium">{{ option.labelKey ? t(option.labelKey) : option.label }}</span>
        </template>
      </ChoiceTiles>
    </div>

    <div class="filter-item" data-filter-id="weight">
      <div class="flex items-center justify-between mb-3">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">{{ t('preferences.labels.weightKg') }}</label>
        <button type="button" class="text-xs px-2 py-1 rounded-md border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800" @click="form.weightState = []; moveFilterById('weight')">{{ t('preferences.placeholders.any') }}</button>
      </div>
      <ChoiceTiles :columns="selectorConfigs.weight.columns" :options="selectorConfigs.weight.options" :model-value="form.weightState" :multiple="true" @update:modelValue="val => { form.weightState = val; moveFilterById('weight') }" />
    </div>
  </div>
</template>



