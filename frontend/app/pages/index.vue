<script setup>
import { useApi } from '~/composables/useApi'
const { fetchWithAuth } = useApi()

const stats = ref({
  total_donatur: 0,
  total_transaksi: 0,
  total_nominal: 0
})
const loading = ref(true)
const errorMessage = ref('')

const loadDashboard = async () => {
  try {
    loading.value = true
    const data = await fetchWithAuth('/dashboard')
    if (data) {
      stats.value = data
    }
  } catch (err) {
    console.error('Gagal mengambil data dashboard:', err)
    errorMessage.value = 'Gagal terhubung ke server backend.'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadDashboard()
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Ringkasan</h2>

    <p v-if="loading" class="text-gray-500">Memuat data...</p>
    <p v-else-if="errorMessage" class="text-red-500 mb-4">{{ errorMessage }}</p>

    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <p class="text-sm font-semibold text-gray-500 uppercase">Total Donatur</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.total_donatur }} Orang</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <p class="text-sm font-semibold text-gray-500 uppercase">Total Transaksi</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ stats.total_transaksi }} Transaksi</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <p class="text-sm font-semibold text-gray-500 uppercase">Total Donasi Terkumpul</p>
        <p class="text-3xl font-bold text-emerald-600 mt-2">
          Rp {{ Number(stats.total_nominal || 0).toLocaleString('id-ID') }}
        </p>
      </div>
    </div>
  </div>
</template>