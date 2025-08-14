import { useI18n } from 'vue-i18n'

export function getPetTags() {
  const { t } = useI18n()
  
  return {
    friendly: { name: t('landing.petTags.friendly'), emoji: '😊', color: 'bg-white text-green-600 border-green-300' },
    gentle: { name: t('landing.petTags.gentle'), emoji: '🥰', color: 'bg-white text-pink-600 border-pink-300' },
    energetic: { name: t('landing.petTags.energetic'), emoji: '⚡', color: 'bg-white text-yellow-600 border-yellow-300' },
    playful: { name: t('landing.petTags.playful'), emoji: '🎾', color: 'bg-white text-blue-600 border-blue-300' },
    calm: { name: t('landing.petTags.calm'), emoji: '😌', color: 'bg-white text-indigo-600 border-indigo-300' },
    loyal: { name: t('landing.petTags.loyal'), emoji: '❤️', color: 'bg-white text-red-600 border-red-300' },
    smart: { name: t('landing.petTags.smart'), emoji: '🧠', color: 'bg-white text-purple-600 border-purple-300' },
    protective: { name: t('landing.petTags.protective'), emoji: '🛡️', color: 'bg-white text-gray-600 border-gray-300' },
    social: { name: t('landing.petTags.social'), emoji: '👥', color: 'bg-white text-teal-600 border-teal-300' },
    active: { name: t('landing.petTags.active'), emoji: '🏃', color: 'bg-white text-orange-600 border-orange-300' }
  }
} 