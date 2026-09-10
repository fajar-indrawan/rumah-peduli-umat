export const useApi = () => {
  const config = useRuntimeConfig()
  const token = useCookie('access_token')

  const fetchWithAuth = async (url: string, options: any = {}) => {
    return await $fetch(`${config.public.apiBase}${url}`, {
      ...options,
      headers: {
        Accept: 'application/json',
        ...(token.value ? { Authorization: `Bearer ${token.value}` } : {}),
        ...options.headers,
      },
    })
  }

  return { fetchWithAuth }
}