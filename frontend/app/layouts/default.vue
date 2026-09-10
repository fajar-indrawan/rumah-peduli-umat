<script setup>
import { useApi } from '~/composables/useApi'

const { fetchWithAuth } = useApi()
const token = useCookie('access_token')
const user = ref(null)

const fetchUser = async () => {
  if (!token.value) return
  try {
    const res = await fetchWithAuth('/me')
    user.value = res?.data || res
  } catch (err) {
    console.error('Gagal mengambil profil admin', err)
  }
}

const handleLogout = async () => {
  try {
    await fetchWithAuth('/logout', { method: 'POST' })
  } catch (err) {
    console.error('Gagal proses logout', err)
  } finally {
    token.value = null
    user.value = null
    await navigateTo('/login')
  }
}

onMounted(() => {
  fetchUser()
})
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-indigo-600 text-white px-6 py-4 flex justify-between items-center shadow-md">
      <div class="flex items-center space-x-6">
        <h1 class="text-xl font-bold">Rumah Peduli Umat</h1>
        <div v-if="token" class="space-x-4">
          <NuxtLink to="/" class="hover:text-indigo-200">Dashboard</NuxtLink>
          <NuxtLink to="/donors" class="hover:text-indigo-200">Data Donatur</NuxtLink>
          <NuxtLink to="/donations" class="hover:text-indigo-200">Transaksi Donasi</NuxtLink>
        </div>
      </div>

      <div class="flex items-center space-x-4">
        <!-- Tampil jika sudah login -->
        <div v-if="token" class="flex items-center space-x-3">
          <span class="text-sm font-medium bg-indigo-700 px-3 py-1 rounded-full">
            👤 {{ user?.name || 'Admin' }}
          </span>
          <button 
            @click="handleLogout" 
            class="bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-3 py-1.5 rounded transition"
          >
            Logout
          </button>
        </div>

        <!-- Tampil jika belum login -->
        <NuxtLink 
          v-else 
          to="/login" 
          class="bg-white text-indigo-600 px-3 py-1.5 rounded text-sm font-medium hover:bg-indigo-50"
        >
          Login
        </NuxtLink>
      </div>
    </nav>

    <main class="p-6 max-w-7xl mx-auto">
      <slot />
    </main>
  </div>
</template>