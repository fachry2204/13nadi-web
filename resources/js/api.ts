const API_BASE = import.meta.env.VITE_API_URL || '/api/v1'
const TOKEN_KEY = 'nadiku_session_token'
let lastValidatedAt = 0
let validationRequest: Promise<{data:{id:number;username:string;name:string}}> | null = null

export const getToken = () => sessionStorage.getItem(TOKEN_KEY)
export const clearSession = () => { sessionStorage.removeItem(TOKEN_KEY); lastValidatedAt = 0; validationRequest = null }

export async function apiRequest<T>(path: string, options: RequestInit = {}, authenticated = false): Promise<T> {
  const headers = new Headers(options.headers)
  headers.set('Accept', 'application/json')
  if (options.body && !(options.body instanceof FormData)) headers.set('Content-Type', 'application/json')
  if (authenticated) {
    const token = getToken()
    if (!token) throw new Error('UNAUTHENTICATED')
    headers.set('Authorization', `Bearer ${token}`)
  }
  const response = await fetch(`${API_BASE}${path}`, { ...options, headers })
  const payload = response.status === 204 ? null : await response.json().catch(() => null)
  if (!response.ok) {
    if (response.status === 401) {
      clearSession()
      throw new Error('UNAUTHENTICATED')
    }
    throw new Error(payload?.message || 'Permintaan gagal.')
  }
  return payload as T
}

export async function uploadAdminImage(file: File) {
  const body = new FormData()
  body.append('image', file)
  return apiRequest<{data:{url:string;name:string}}>('/admin/upload/image', { method: 'POST', body }, true)
}

export async function loginAdmin(username: string, password: string) {
  const payload = await apiRequest<{data:{user:{id:number;username:string;name:string};token:string}}>('/auth/login', {
    method: 'POST',
    body: JSON.stringify({ username, password }),
  })
  sessionStorage.setItem(TOKEN_KEY, payload.data.token)
  lastValidatedAt = Date.now()
  return payload.data.user
}

export const validateSession = (force = false) => {
  if (!force && lastValidatedAt && Date.now() - lastValidatedAt < 60_000) return Promise.resolve({data:{id:0,username:'admin',name:'Nadiku Admin'}})
  if (validationRequest) return validationRequest
  validationRequest = apiRequest<{data:{id:number;username:string;name:string}}>('/auth/me', {}, true)
    .then(payload => { lastValidatedAt = Date.now(); return payload })
    .finally(() => { validationRequest = null })
  return validationRequest
}
export const logoutAdmin = async () => {
  try { await apiRequest('/auth/logout', { method: 'POST' }, true) } finally { clearSession() }
}
