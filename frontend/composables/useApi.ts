import { useFetch, useRuntimeConfig } from 'nuxt/app'
import type { UseFetchOptions } from 'nuxt/app'
import { defu } from 'defu'

export const useApi = <T>(endpoint: string, options: UseFetchOptions<T> = {}) => {
  const config = useRuntimeConfig()

  const defaults: UseFetchOptions<T> = {
    baseURL: config.public.apiBase as string,
    headers: {
      Accept: 'application/json'
    }
  }

  const params = defu(options, defaults)

  return useFetch(endpoint, params)
}