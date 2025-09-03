import blumilkDefault from '@blumilksoftware/eslint-config'
import globals from 'globals'

export default [
  ...blumilkDefault,
  {
    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.es2022,
      },
    },
    rules: {
      'n/no-unsupported-features/node-builtins': ['error', {
        allowExperimental: true,
        ignores: ['fetch', 'localStorage', 'sessionStorage', 'AbortController'],
      }],
    },
  },
]
