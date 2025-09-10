export const renderRules = [
  { pattern: /^status$/i, kind: 'status' },
  { pattern: /(created_at|updated_at|deleted_at|email_verified_at|last_login|timestamp|date)/i, kind: 'date' },
  { pattern: /^email$/i, kind: 'email' },
  { pattern: /^ip(_address)?$/i, kind: 'ip' },
  { pattern: /(details|user_agent)/i, kind: 'details' },
  { pattern: /^rating$/i, kind: 'rating' },
  { pattern: /^age$/i, kind: 'age' },
  { pattern: /^tags$/i, kind: 'tags' },
]

export function getKindForColumn(columnKey) {
  const key = String(columnKey ?? '').toLowerCase()
  for (const rule of renderRules) {
    if (rule.pattern.test(key)) return rule.kind
  }
  return 'text'
}
