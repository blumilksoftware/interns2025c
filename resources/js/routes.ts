export const routes = {
  home: '/',
  dashboard: '/dashboard',
  login: '/login',
  register: '/register',
  logout: '/logout',
  password: {
    request: '/forgot-password',
    email: '/forgot-password',
    update: '/reset-password',
    reset: (token: string) => `/reset-password/${token}`,
    confirm: '/user/confirm-password',
    confirmationStatus: '/user/confirmed-password-status',
  },
  verification: {
    send: '/email/verification-notification',
  },
  profile: {
    show: '/user/profile',
    informationUpdate: '/user/profile-information',
    photoDestroy: '/user/profile-photo',
    passwordUpdate: '/user/password',
    otherBrowserSessionsDestroy: '/user/other-browser-sessions',
    currentUserDestroy: '/user',
  },
  apiTokens: {
    index: '/user/api-tokens',
    store: '/user/api-tokens',
    update: (id: number | string) => `/user/api-tokens/${id}`,
    destroy: (id: number | string) => `/user/api-tokens/${id}`,
  },
  pets: {
    index: '/pets',
    show: (id: number | string) => `/pets/${id}`,
    store: '/pets',
    update: (id: number | string) => `/pets/${id}`,
    destroy: (id: number | string) => `/pets/${id}`,
  },
  tags: {
    store: '/tags',
    update: (id: number | string) => `/tags/${id}`,
    destroy: (id: number | string) => `/tags/${id}`,
  },
  petShelters: {
    index: '/pet-shelters',
    store: '/pet-shelters',
    update: (id: number | string) => `/pet-shelters/${id}`,
    destroy: (id: number | string) => `/pet-shelters/${id}`,
  },
  petShelterAddresses: {
    store: '/pet-shelter-addresses',
    update: (id: number | string) => `/pet-shelter-addresses/${id}`,
    destroy: (id: number | string) => `/pet-shelter-addresses/${id}`,
  },
} as const

export function isActive(currentUrl: string, path: string): boolean {
  const currentPath = currentUrl.split('?')[0]
  return currentPath === path
}

