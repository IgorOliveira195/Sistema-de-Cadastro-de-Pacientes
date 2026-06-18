export function formatCep (value) {
  const digits = String(value || '').replace(/\D/g, '')

  if (digits.length !== 8) return digits

  return `${digits.slice(0, 5)}-${digits.slice(5)}`
}

export function formatCpf (value) {
  const digits = String(value || '').replace(/\D/g, '')

  if (digits.length !== 11) return digits

  return `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6, 9)}-${digits.slice(9)}`
}

export function formatCns (value) {
  const digits = String(value || '').replace(/\D/g, '')

  if (digits.length !== 15) return digits

  return `${digits.slice(0, 3)} ${digits.slice(3, 7)} ${digits.slice(7, 11)} ${digits.slice(11)}`
}

export function formatPhone (value) {
  const digits = String(value || '').replace(/\D/g, '')

  if (digits.length === 10) {
    return `(${digits.slice(0, 2)}) ${digits.slice(2, 6)}-${digits.slice(6)}`
  }

  if (digits.length === 11) {
    return `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7)}`
  }

  return digits
}

export function formatDate (value) {
  if (!value) return ''

  const [year, month, day] = String(value).split('T')[0].split('-')

  return `${day}/${month}/${year}`
}

export function genderLabel (value) {
  const labels = { M: 'Masculino', F: 'Feminino', O: 'Outro' }

  return labels[value] || value
}
