<script setup>
import { useApi } from '~/composables/useApi'

const { fetchWithAuth } = useApi()

const donors = ref([])
const loading = ref(true)
const errors = ref({}) // Menyimpan eror validasi dari Laravel

const form = ref({ nama: '', telepon: '', email: '', alamat: '' })
const isEditing = ref(false)
const selectedId = ref(null)

const loadDonors = async () => {
  try {
    loading.value = true
    const data = await fetchWithAuth('/donors')
    donors.value = data
  } catch (err) {
    console.error('Gagal mengambil data donatur', err)
  } finally {
    loading.value = false
  }
}

const handleSubmit = async () => {
  errors.value = {} // Reset eror setiap submit
  try {
    if (isEditing.value) {
      await fetchWithAuth(`/donors/${selectedId.value}`, {
        method: 'PUT',
        body: form.value
      })
    } else {
      await fetchWithAuth('/donors', {
        method: 'POST',
        body: form.value
      })
    }
    resetForm()
    await loadDonors()
  } catch (err) {
    if (err?.response?.status === 422 || err?.status === 422) {
      errors.value = err?.data?.errors || err?.response?._data?.errors || {}
    } else {
      alert('Gagal menyimpan data donatur.')
    }
  }
}

const editDonor = (donor) => {
  errors.value = {}
  isEditing.value = true
  selectedId.value = donor.id
  form.value = { nama: donor.nama, telepon: donor.telepon, email: donor.email, alamat: donor.alamat }
}

const deleteDonor = async (id) => {
  if (confirm('Yakin ingin menghapus donatur ini?')) {
    try {
      await fetchWithAuth(`/donors/${id}`, { method: 'DELETE' })
      await loadDonors()
    } catch (err) {
      alert('Gagal menghapus donatur')
    }
  }
}

const resetForm = () => {
  form.value = { nama: '', telepon: '', email: '', alamat: '' }
  errors.value = {}
  isEditing.value = false
  selectedId.value = null
}

onMounted(() => {
  loadDonors()
})
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Manajemen Data Donatur</h2>

    <!-- Form Tambah / Edit -->
    <div class="bg-white p-6 rounded-lg shadow-sm border mb-8">
      <h3 class="text-lg font-bold mb-4 text-gray-700">
        {{ isEditing ? 'Edit Data Donatur' : 'Tambah Donatur Baru' }}
      </h3>
      <form @submit.prevent="handleSubmit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
          <input 
            v-model="form.nama" 
            type="text" 
            class="w-full border rounded px-3 py-2 text-sm"
            :class="{ 'border-red-500': errors.nama }"
          />
          <p v-if="errors.nama" class="text-red-500 text-xs mt-1">{{ errors.nama[0] }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Telepon</label>
          <input 
            v-model="form.telepon" 
            type="text" 
            class="w-full border rounded px-3 py-2 text-sm"
            :class="{ 'border-red-500': errors.telepon }"
          />
          <p v-if="errors.telepon" class="text-red-500 text-xs mt-1">{{ errors.telepon[0] }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Email</label>
          <input 
            v-model="form.email" 
            type="email" 
            class="w-full border rounded px-3 py-2 text-sm"
            :class="{ 'border-red-500': errors.email }"
          />
          <p v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email[0] }}</p>
        </div>

        <div>
          <label class="block text-sm font-medium mb-1">Alamat</label>
          <input 
            v-model="form.alamat" 
            type="text" 
            class="w-full border rounded px-3 py-2 text-sm"
            :class="{ 'border-red-500': errors.alamat }"
          />
          <p v-if="errors.alamat" class="text-red-500 text-xs mt-1">{{ errors.alamat[0] }}</p>
        </div>

        <div class="md:col-span-2 flex gap-2 justify-end mt-2">
          <button v-if="isEditing" type="button" @click="resetForm" class="px-4 py-2 border rounded text-sm text-gray-600">Batal</button>
          <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">
            {{ isEditing ? 'Simpan Perubahan' : 'Tambah Donatur' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Tabel Data Donatur -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
      <table class="w-full text-left border-collapse text-sm">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="p-3 font-semibold">Nama</th>
            <th class="p-3 font-semibold">Telepon</th>
            <th class="p-3 font-semibold">Email</th>
            <th class="p-3 font-semibold">Alamat</th>
            <th class="p-3 font-semibold text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading"><td colspan="5" class="p-4 text-center text-gray-500">Memuat data...</td></tr>
          <tr v-else-if="donors.length === 0"><td colspan="5" class="p-4 text-center text-gray-500">Belum ada data donatur.</td></tr>
          <tr v-for="donor in donors" :key="donor.id" class="border-b hover:bg-gray-50">
            <td class="p-3 font-medium">{{ donor.nama }}</td>
            <td class="p-3">{{ donor.telepon }}</td>
            <td class="p-3">{{ donor.email }}</td>
            <td class="p-3">{{ donor.alamat || '-' }}</td>
            <td class="p-3 text-center space-x-2">
              <button @click="editDonor(donor)" class="text-blue-600 hover:underline">Edit</button>
              <button @click="deleteDonor(donor.id)" class="text-red-600 hover:underline">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>