<script setup>
import { useApi } from '~/composables/useApi'

const { fetchWithAuth } = useApi()

const donations = ref([])
const donors = ref([])
const loading = ref(true)
const errors = ref({}) // Menyimpan eror validasi

const form = ref({
  donor_id: '',
  tanggal_donasi: new Date().toISOString().split('T')[0],
  nominal: '',
  jenis_donasi: 'Zakat',
  keterangan: ''
})

const loadData = async () => {
  try {
    loading.value = true
    const [resDonations, resDonors] = await Promise.all([
      fetchWithAuth('/donations'),
      fetchWithAuth('/donors')
    ])
    donations.value = resDonations
    donors.value = resDonors
  } catch (err) {
    console.error('Gagal mengambil data transaksi', err)
  } finally {
    loading.value = false
  }
}

const handleSubmit = async () => {
  errors.value = {}
  try {
    await fetchWithAuth('/donations', {
      method: 'POST',
      body: form.value
    })
    form.value = {
      donor_id: '',
      tanggal_donasi: new Date().toISOString().split('T')[0],
      nominal: '',
      jenis_donasi: 'Zakat',
      keterangan: ''
    }
    await loadData()
  } catch (err) {
    if (err?.response?.status === 422 || err?.status === 422) {
      errors.value = err?.data?.errors || err?.response?._data?.errors || {}
    } else {
      alert('Gagal menambah transaksi donasi')
    }
  }
}

const deleteDonation = async (id) => {
  if (confirm('Yakin ingin menghapus transaksi ini?')) {
    try {
      await fetchWithAuth(`/donations/${id}`, { method: 'DELETE' })
      await loadData()
    } catch (err) {
      alert('Gagal menghapus transaksi')
    }
  }
}

onMounted(() => {
  loadData()
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Manajemen Transaksi Donasi</h2>

    <!-- Form Transaksi -->
    <div class="bg-white p-6 rounded-lg shadow-sm border mb-8">
      <h3 class="text-lg font-bold mb-4 text-gray-700">Catat Donasi Baru</h3>
      <form @submit.prevent="handleSubmit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Pilih Donatur</label>
          <select 
            v-model="form.donor_id" 
            class="w-full border rounded px-3 py-2 text-sm"
            :class="{ 'border-red-500': errors.donor_id }"
          >
            <option value="" disabled>-- Pilih Donatur --</option>
            <option v-for="d in donors" :key="d.id" :value="d.id">{{ d.nama }} ({{ d.telepon }})</option>
          </select>
          <p v-if="errors.donor_id" class="text-red-500 text-xs mt-1">{{ errors.donor_id[0] }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Tanggal Donasi</label>
          <input 
            v-model="form.tanggal_donasi" 
            type="date" 
            class="w-full border rounded px-3 py-2 text-sm"
            :class="{ 'border-red-500': errors.tanggal_donasi }"
          />
          <p v-if="errors.tanggal_donasi" class="text-red-500 text-xs mt-1">{{ errors.tanggal_donasi[0] }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Nominal (Rp)</label>
          <input 
            v-model="form.nominal" 
            type="number" 
            min="0" 
            class="w-full border rounded px-3 py-2 text-sm"
            :class="{ 'border-red-500': errors.nominal }"
          />
          <p v-if="errors.nominal" class="text-red-500 text-xs mt-1">{{ errors.nominal[0] }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Jenis Donasi</label>
          <select 
            v-model="form.jenis_donasi" 
            class="w-full border rounded px-3 py-2 text-sm"
            :class="{ 'border-red-500': errors.jenis_donasi }"
          >
            <option value="Zakat">Zakat</option>
            <option value="Infaq">Infaq</option>
            <option value="Sedekah">Sedekah</option>
          </select>
          <p v-if="errors.jenis_donasi" class="text-red-500 text-xs mt-1">{{ errors.jenis_donasi[0] }}</p>
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium mb-1">Keterangan</label>
          <input 
            v-model="form.keterangan" 
            type="text" 
            class="w-full border rounded px-3 py-2 text-sm"
            :class="{ 'border-red-500': errors.keterangan }"
          />
          <p v-if="errors.keterangan" class="text-red-500 text-xs mt-1">{{ errors.keterangan[0] }}</p>
        </div>

        <div class="md:col-span-2 flex justify-end">
          <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">
            Simpan Donasi
          </button>
        </div>
      </form>
    </div>

    <!-- Tabel Transaksi Donasi -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
      <table class="w-full text-left border-collapse text-sm">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="p-3 font-semibold">Tanggal</th>
            <th class="p-3 font-semibold">Nama Donatur</th>
            <th class="p-3 font-semibold">Jenis Donasi</th>
            <th class="p-3 font-semibold">Nominal</th>
            <th class="p-3 font-semibold">Keterangan</th>
            <th class="p-3 font-semibold text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="6" class="p-4 text-center text-gray-500">Memuat data...</td></tr>
          <tr v-else-if="donations.length === 0"><td colspan="6" class="p-4 text-center text-gray-500">Belum ada transaksi donasi.</td></tr>
          <tr v-for="donation in donations" :key="donation.id" class="border-b hover:bg-gray-50">
            <td class="p-3">{{ donation.tanggal_donasi }}</td>
            <td class="p-3 font-medium">{{ donation.donor?.nama || '-' }}</td>
            <td class="p-3">
              <span class="px-2 py-0.5 rounded text-xs font-semibold"
                :class="{
                  'bg-emerald-100 text-emerald-700': donation.jenis_donasi === 'Zakat',
                  'bg-blue-100 text-blue-700': donation.jenis_donasi === 'Infaq',
                  'bg-purple-100 text-purple-700': donation.jenis_donasi === 'Sedekah'
                }">
                {{ donation.jenis_donasi }}
              </span>
            </td>
            <td class="p-3 font-semibold text-gray-800">
              Rp {{ Number(donation.nominal).toLocaleString('id-ID') }}
            </td>
            <td class="p-3 text-gray-500">{{ donation.keterangan || '-' }}</td>
            <td class="p-3 text-center">
              <button @click="deleteDonation(donation.id)" class="text-red-600 hover:underline">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>