import type { StatusMapper, StatusInfo } from './types';

export const statusMapper: StatusMapper = {
  available: {
    bgColor: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    dotColor: 'bg-emerald-500',
  },
  unavailable: {
    bgColor: 'bg-gray-50 text-gray-700 border-gray-200',
    dotColor: 'bg-gray-400',
  },
};

const normalize = (s: string): string => {
  return s
    .toLowerCase()
    .normalize('NFD')
    .replace(/\p{Diacritic}+/gu, '')
    .trim();
}

const AVAILABLE_KEYWORDS = [
  'available',
  'dostepny',
  'dostepna',
  'dostepne',
  'dostepni',
  'wolny',
  'wolna',
  'wolne',
  'gotowy do adopcji',
  'ready for adoption',
]

export const getStatusInfo = (status: string, availableStatuses: string[]): StatusInfo => {
  const normalized = normalize(String(status || ''))
  const isLocalizedAvailable = availableStatuses.map(normalize).includes(normalized)
  const matchesKeywords = AVAILABLE_KEYWORDS.some((kw) => normalized.includes(kw))
  const isAvailable = isLocalizedAvailable || matchesKeywords
  return isAvailable ? statusMapper.available : statusMapper.unavailable
};
