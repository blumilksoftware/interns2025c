export type ChoiceOption = {
  value: number | string
  icon?: string
  iconClass?: string
  label?: string
  labelKey?: string
}

export type ChoiceConfig = {
  columns: number
  options: ChoiceOption[]
}

export const selectorConfigs = {
  age: {
    columns: 3,
    options: [
      { value: 0, icon: 'mood-kid', iconClass: 'w-6 h-6', labelKey: 'preferences.age.young' },
      { value: 1, icon: 'man',      iconClass: 'w-6 h-6', labelKey: 'preferences.age.adult' },
      { value: 2, icon: 'old',      iconClass: 'w-6 h-6', labelKey: 'preferences.age.senior' },
    ],
  } as ChoiceConfig,

  size: {
    columns: 3,
    options: [
      { value: 0, icon: 'horse', iconClass: 'w-4 h-4', labelKey: 'preferences.size.small' },
      { value: 1, icon: 'horse', iconClass: 'w-6 h-6', labelKey: 'preferences.size.medium' },
      { value: 2, icon: 'horse', iconClass: 'w-8 h-8', labelKey: 'preferences.size.large' },
    ],
  } as ChoiceConfig,

  weight: {
    columns: 3,
    options: [
      { value: 0, icon: 'weight', iconClass: 'w-5 h-5', label: 'chudy' },
      { value: 1, icon: 'weight', iconClass: 'w-6 h-6', label: 'średni' },
      { value: 2, icon: 'weight', iconClass: 'w-7 h-7', label: 'gruby' },
    ],
  } as ChoiceConfig,

  attitudes: {
    columns: 5,
    options: [
      { value: 0, icon: 'mood-sad',   iconClass: 'w-6 h-10', label: 'wrogi' },
      { value: 1, icon: 'warning',    iconClass: 'w-6 h-7', label: 'ostrożny' },
      { value: 2, icon: 'mood-empty', iconClass: 'w-6 h-7', label: 'obojętny' },
      { value: 3, icon: 'mood-smile', iconClass: 'w-6 h-7', label: 'przyjazny' },
      { value: 4, icon: 'heart',      iconClass: 'w-6 h-7', label: 'bardzo przyjazny' },
    ],
  } as ChoiceConfig,

  activity: {
    columns: 5,
    options: [
      { value: 0, icon: 'zzz',  iconClass: 'w-5 h-5', label: 'bardzo niski' },
      { value: 1, icon: 'walk', iconClass: 'w-5 h-5', label: 'niski' },
      { value: 2, icon: 'run',  iconClass: 'w-5 h-5', label: 'średni' },
      { value: 3, icon: 'bolt', iconClass: 'w-5 h-5', label: 'wysoki' },
      { value: 4, icon: 'flame',iconClass: 'w-5 h-5', label: 'bardzo wysoki' },
    ],
  } as ChoiceConfig,
}

export type SelectorConfigs = typeof selectorConfigs


