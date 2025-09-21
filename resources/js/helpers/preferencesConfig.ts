export type LabeledOption<T = string | number> = {
  value: T
  labelKey: string
}

export const speciesOptions: LabeledOption<string>[] = [
  { value: 'dog', labelKey: 'preferences.species.dog' },
  { value: 'cat', labelKey: 'preferences.species.cat' },
  { value: 'other', labelKey: 'preferences.species.other' },
]

export const sexOptions: LabeledOption<string>[] = [
  { value: 'male', labelKey: 'preferences.sex.male' },
  { value: 'female', labelKey: 'preferences.sex.female' },
]

export const ageTextOptions: LabeledOption<string>[] = [
  { value: 'young', labelKey: 'preferences.age.young' },
  { value: 'adult', labelKey: 'preferences.age.adult' },
  { value: 'senior', labelKey: 'preferences.age.senior' },
]

export const colorOptions: LabeledOption<string>[] = [
  { value: 'black', labelKey: 'preferences.colors.black' },
  { value: 'white', labelKey: 'preferences.colors.white' },
  { value: 'brown', labelKey: 'preferences.colors.brown' },
  { value: 'grey', labelKey: 'preferences.colors.grey' },
  { value: 'ginger', labelKey: 'preferences.colors.ginger' },
  { value: 'mixed', labelKey: 'preferences.colors.mixed' },
]

export const healthOptions: LabeledOption<string>[] = [
  { value: 'healthy', labelKey: 'preferences.health.healthy' },
  { value: 'sick', labelKey: 'preferences.health.sick' },
  { value: 'recovering', labelKey: 'preferences.health.recovering' },
  { value: 'critical', labelKey: 'preferences.health.critical' },
  { value: 'unknown', labelKey: 'preferences.health.unknown' },
]

export const adoptionOptions: LabeledOption<string>[] = [
  { value: 'adopted', labelKey: 'preferences.adoption.adopted' },
  { value: 'waiting for adoption', labelKey: 'preferences.adoption.waiting' },
  { value: 'quarantined', labelKey: 'preferences.adoption.quarantined' },
  { value: 'in temporary home', labelKey: 'preferences.adoption.temporaryHome' },
]
