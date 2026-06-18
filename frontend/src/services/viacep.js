import axios from 'axios'

const viacep = axios.create({
  baseURL: 'https://viacep.com.br/ws',
  timeout: 8000
})

export async function fetchAddressByCep (cep) {
  const digits = String(cep || '').replace(/\D/g, '')

  if (digits.length !== 8) {
    throw new Error('CEP inválido.')
  }

  const { data } = await viacep.get(`/${digits}/json/`)

  if (data.erro) {
    throw new Error('CEP não encontrado.')
  }

  return {
    street: data.logradouro || '',
    neighborhood: data.bairro || '',
    city: data.localidade || '',
    state: data.uf || ''
  }
}
