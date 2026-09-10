<script setup>
const email = ref('')
const password = ref('')
const errorMessage = ref('')
const loading = ref(false)

const tokenCookie = useCookie('access_token', { maxAge: 60 * 60 * 24 })
const config = useRuntimeConfig()

const handleLogin = async () => {
  errorMessage.value = ''
  loading.value = true

  try {
    const res = await $fetch(`${config.public.apiBase}/login`, {
      method: 'POST',
      body: {
        email: email.value,
        password: password.value
      }
    })

    // Simpan token ke cookie
    tokenCookie.value = res.access_token

    // Redirect ke dashboard utama
    await navigateTo('/')
  } catch (err) {
    errorMessage.value = err?.data?.message || 'Email atau password salah.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="max-w-md mx-auto mt-12 bg-white p-8 border border-gray-200 rounded-lg shadow-sm">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Login Admin</h2>

    <div v-if="errorMessage" class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded-md">
      {{ errorMessage }}
    </div>

    <form @submit.prevent="handleLogin" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input
          v-model="email"
          type="email"
          required
          class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
          placeholder="admin@gmail.com"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input
          v-model="password"
          type="password"
          required
          class="w-full border rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
          placeholder="••••••••"
        />
      </div>

      <button
        type="submit"
        :disabled="loading"
        class="w-full bg-indigo-600 text-white py-2 rounded-md font-medium text-sm hover:bg-indigo-700 transition duration-150 disabled:opacity-50"
      >
        {{ loading ? 'Memproses...' : 'Masuk' }}
      </button>
    </form>
  </div>
</template>