<script setup lang="ts">
// Interface Data & Pagination
interface Category {
  id: number
  nama: string
  tipe: 'income' | 'expense'
}

interface ChartOfAccount {
  id: number
  kode: string
  nama: string
  id_kategori: number
  category?: Category
}

interface MetaPagination {
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number | null
  to: number | null
}

interface ApiResponse<T> {
  status: string
  message?: string
  data: T
  meta?: MetaPagination
}

interface Notification {
  type: 'success' | 'error'
  message: string
}

const config = useRuntimeConfig()
const apiBase = config.public.apiBase as string

// State Management
const coas = ref<ChartOfAccount[]>([])
const categories = ref<Category[]>([])
const isEditing = ref<boolean>(false)
const selectedId = ref<number | null>(null)

const isLoading = ref<boolean>(false)
const isSubmitting = ref<boolean>(false)

// State Pagination
const currentPage = ref<number>(1)
const perPage = ref<number>(10)
const meta = ref<MetaPagination | null>(null)

// State Notifikasi
const notification = ref<Notification | null>(null)
let notificationTimeout: NodeJS.Timeout | null = null

const showNotification = (type: 'success' | 'error', message: string, duration = 4000): void => {
  if (notificationTimeout) clearTimeout(notificationTimeout)
  notification.value = { type, message }
  notificationTimeout = setTimeout(() => {
    notification.value = null
  }, duration)
}

const clearNotification = (): void => {
  if (notificationTimeout) clearTimeout(notificationTimeout)
  notification.value = null
}

const form = ref<{
  kode: string
  nama: string
  id_kategori: number | ''
}>({
  kode: '',
  nama: '',
  id_kategori: '',
})

// Fetch Data dengan Query Pagination untuk COA
const fetchData = async (page = 1): Promise<void> => {
  isLoading.value = true
  currentPage.value = page
  try {
    // Kategori diambil seluruhnya (unpaginated) untuk pilihan dropdown form
    const fetchCategoriesPromise = categories.value.length === 0 
      ? $fetch<ApiResponse<Category[]>>(`${apiBase}/categories`) 
      : Promise.resolve(null)

    const [resCoas, resCats] = await Promise.all([
      $fetch<ApiResponse<ChartOfAccount[]>>(`${apiBase}/chart-of-accounts`, {
        query: { page: currentPage.value, per_page: perPage.value },
      }),
      fetchCategoriesPromise,
    ])

    coas.value = resCoas.data || []
    meta.value = resCoas.meta || null

    if (resCats?.data) {
      categories.value = resCats.data
    }
  } catch (err: any) {
    showNotification('error', 'Gagal memuat data COA atau Kategori. Periksa koneksi API Anda.')
  } finally {
    isLoading.value = false
  }
}

const saveCoa = async (): Promise<void> => {
  isSubmitting.value = true
  try {
    if (isEditing.value && selectedId.value !== null) {
      await $fetch(`${apiBase}/chart-of-accounts/${selectedId.value}`, {
        method: 'PUT',
        body: form.value,
      })
      showNotification('success', 'Chart of Account berhasil diperbarui!')
    } else {
      await $fetch(`${apiBase}/chart-of-accounts`, {
        method: 'POST',
        body: form.value,
      })
      showNotification('success', 'Chart of Account baru berhasil ditambahkan!')
    }
    
    resetForm(false)
    await fetchData(currentPage.value)
  } catch (err: any) {
    showNotification('error', err.data?.message || 'Gagal menyimpan Chart of Account.')
  } finally {
    isSubmitting.value = false
  }
}

const editCoa = (item: ChartOfAccount): void => {
  isEditing.value = true
  selectedId.value = item.id
  form.value = {
    kode: item.kode,
    nama: item.nama,
    id_kategori: item.category?.id || item.id_kategori || '',
  }
}

const deleteCoa = async (id: number): Promise<void> => {
  if (!confirm('Yakin ingin menghapus COA ini?')) return

  try {
    await $fetch(`${apiBase}/chart-of-accounts/${id}`, { method: 'DELETE' })
    showNotification('success', 'Chart of Account berhasil dihapus!')
    await fetchData(currentPage.value)
  } catch (err: any) {
    showNotification('error', err.data?.message || 'Gagal menghapus COA.')
  }
}

const resetForm = (clearNotif = true): void => {
  isEditing.value = false
  selectedId.value = null
  form.value = { kode: '', nama: '', id_kategori: '' }
  if (clearNotif) clearNotification()
}

onMounted(() => fetchData())
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Chart of Accounts (COA)</h1>

    <!-- Banner Notifikasi Dinamis -->
    <Transition name="fade">
      <div 
        v-if="notification" 
        :class="[
          'mb-4 p-3.5 rounded-lg text-sm font-medium flex items-center justify-between border shadow-sm transition-all',
          notification.type === 'success' 
            ? 'bg-emerald-50 border-emerald-200 text-emerald-800' 
            : 'bg-rose-50 border-rose-200 text-rose-800'
        ]"
      >
        <div class="flex items-center space-x-2">
          <span v-if="notification.type === 'success'" class="text-emerald-600 font-bold">✓</span>
          <span v-else class="text-rose-600 font-bold">✕</span>
          <span>{{ notification.message }}</span>
        </div>
        <button 
          @click="clearNotification" 
          class="text-gray-400 hover:text-gray-600 text-base font-bold px-1"
        >
          &times;
        </button>
      </div>
    </Transition>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Form Input -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 h-fit">
        <h2 class="text-lg font-semibold mb-4 text-gray-700">
          {{ isEditing ? 'Edit Chart Of Account' : 'Tambah Chart Of Account Baru' }}
        </h2>
        <form @submit.prevent="saveCoa" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Kode</label>
            <input 
              v-model="form.kode" 
              type="text" 
              required 
              placeholder="401"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500" 
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <input 
              v-model="form.nama" 
              type="text" 
              required 
              placeholder="Parkir"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500" 
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Kategori</label>
            <select 
              v-model="form.id_kategori" 
              required 
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
            >
              <option value="" disabled>Pilih Kategori</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.nama }} ({{ category.tipe }})
              </option>
            </select>
          </div>
          <div class="flex space-x-2 pt-2">
            <button 
              type="submit" 
              :disabled="isSubmitting"
              class="flex-1 bg-indigo-600 text-white py-2 rounded-md text-sm font-medium hover:bg-indigo-700 disabled:opacity-50 transition"
            >
              {{ isSubmitting ? 'Menyimpan...' : 'Simpan' }}
            </button>
            <button 
              v-if="isEditing" 
              type="button" 
              @click="resetForm(true)" 
              class="bg-gray-400 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-500 transition"
            >
              Batal
            </button>
          </div>
        </form>
      </div>

      <!-- Tabel Data + Pagination -->
      <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col justify-between">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 text-sm">
            <!-- Loading State -->
            <tr v-if="isLoading">
              <td colspan="4" class="px-6 py-8 text-center text-gray-500 animate-pulse">
                Memuat data Chart of Accounts...
              </td>
            </tr>

            <!-- Data Rows -->
            <template v-else>
              <tr v-for="item in coas" :key="item.id" class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-semibold text-indigo-600">{{ item.kode }}</td>
                <td class="px-6 py-4 font-medium text-gray-900">{{ item.nama }}</td>
                <td class="px-6 py-4 text-gray-600">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ item.category?.nama || '-' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-right space-x-3">
                  <button @click="editCoa(item)" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</button>
                  <button @click="deleteCoa(item.id)" class="text-rose-600 hover:text-rose-900 font-medium">Hapus</button>
                </td>
              </tr>

              <!-- Empty State -->
              <tr v-if="coas.length === 0">
                <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada data Chart of Accounts.</td>
              </tr>
            </template>
          </tbody>
        </table>

        <!-- Controls Pagination -->
        <div v-if="meta && meta.total > 0" class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between text-xs text-gray-600">
          <div>
            Menampilkan <span class="font-medium text-gray-900">{{ meta.current_page ?? 0 }}</span> sampai <span class="font-medium text-gray-900">{{ meta.last_page ?? 0 }}</span> dari <span class="font-medium text-gray-900">{{ meta.total }}</span> data
          </div>
          <div class="flex space-x-1">
            <button 
              @click="fetchData(currentPage - 1)" 
              :disabled="currentPage === 1 || isLoading"
              class="px-3 py-1.5 border rounded-md bg-white text-gray-700 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition"
            >
              &larr; Prev
            </button>
            <button 
              @click="fetchData(currentPage + 1)" 
              :disabled="currentPage === meta.last_page || isLoading"
              class="px-3 py-1.5 border rounded-md bg-white text-gray-700 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed transition"
            >
              Next &rarr;
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>