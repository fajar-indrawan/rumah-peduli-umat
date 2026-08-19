<script setup lang="ts">
// Interface Data & Pagination
interface ChartOfAccount {
  id?: number
  kode: string
  nama: string
}

interface Transaction {
  id: number
  tanggal: string
  kode_coa: string
  desc?: string
  debit: number
  credit: number
  chart_of_account?: ChartOfAccount
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

// 2. State Management & Pagination
const transactions = ref<Transaction[]>([])
const coas = ref<ChartOfAccount[]>([])
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
  tanggal: string
  kode_coa: string
  desc: string
  debit: number
  credit: number
}>({
  tanggal: '',
  kode_coa: '',
  desc: '',
  debit: 0,
  credit: 0,
})

// Fetch Data dengan Query Pagination untuk Transaksi
const fetchData = async (page = 1): Promise<void> => {
  isLoading.value = true
  currentPage.value = page
  try {
    // COA hanya di-fetch sekali untuk mengisi dropdown select
    const fetchCoasPromise = coas.value.length === 0 
      ? $fetch<ApiResponse<ChartOfAccount[]>>(`${apiBase}/chart-of-accounts`)
      : Promise.resolve(null)

    const [resTxs, resCoas] = await Promise.all([
      $fetch<ApiResponse<Transaction[]>>(`${apiBase}/transactions`, {
        query: { page: currentPage.value, per_page: perPage.value },
      }),
      fetchCoasPromise,
    ])

    transactions.value = resTxs.data || []
    meta.value = resTxs.meta || null

    if (resCoas?.data) {
      coas.value = resCoas.data
    }
  } catch (err: any) {
    showNotification('error', 'Gagal memuat data transaksi atau COA. Periksa koneksi API Anda.')
  } finally {
    isLoading.value = false
  }
}

const saveTransaction = async (): Promise<void> => {
  isSubmitting.value = true
  try {
    if (isEditing.value && selectedId.value !== null) {
      await $fetch(`${apiBase}/transactions/${selectedId.value}`, {
        method: 'PUT',
        body: form.value,
      })
      showNotification('success', 'Transaksi berhasil diperbarui!')
    } else {
      await $fetch(`${apiBase}/transactions`, {
        method: 'POST',
        body: form.value,
      })
      showNotification('success', 'Transaksi baru berhasil ditambahkan!')
    }
    
    resetForm(false)
    await fetchData(currentPage.value)
  } catch (err: any) {
    showNotification('error', err.data?.message || 'Gagal menyimpan transaksi.')
  } finally {
    isSubmitting.value = false
  }
}

const editTransaction = (t: Transaction): void => {
  isEditing.value = true
  selectedId.value = t.id
  form.value = {
    tanggal: t.tanggal,
    kode_coa: t.chart_of_account?.kode || t.kode_coa || '',
    desc: t.desc || '',
    debit: Number(t.debit) || 0,
    credit: Number(t.credit) || 0,
  }
}

const deleteTransaction = async (id: number): Promise<void> => {
  if (!confirm('Yakin ingin menghapus transaksi ini?')) return

  try {
    await $fetch(`${apiBase}/transactions/${id}`, { method: 'DELETE' })
    showNotification('success', 'Transaksi berhasil dihapus!')
    await fetchData(currentPage.value)
  } catch (err: any) {
    showNotification('error', err.data?.message || 'Gagal menghapus transaksi.')
  }
}

const resetForm = (clearNotif = true): void => {
  isEditing.value = false
  selectedId.value = null
  form.value = { tanggal: '', kode_coa: '', desc: '', debit: 0, credit: 0 }
  if (clearNotif) clearNotification()
}

const formatRupiah = (val: number): string => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(val || 0)
}

onMounted(() => fetchData())
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Manajemen Transaksi</h1>

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
          {{ isEditing ? 'Edit Transaksi' : 'Tambah Transaksi Baru' }}
        </h2>
        <form @submit.prevent="saveTransaction" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
            <input 
              v-model="form.tanggal" 
              type="date" 
              required 
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500" 
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Chart of Account (COA)</label>
            <select 
              v-model="form.kode_coa" 
              required 
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
            >
              <option value="" disabled>Pilih COA</option>
              <option v-for="c in coas" :key="c.kode" :value="c.kode">
                {{ c.kode }} - {{ c.nama }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <input 
              v-model="form.desc" 
              type="text" 
              placeholder="Keterangan transaksi"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500" 
            />
          </div>
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block text-sm font-medium text-gray-700">Debit (Rp)</label>
              <input 
                v-model.number="form.debit" 
                type="number" 
                min="0" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500" 
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Credit (Rp)</label>
              <input 
                v-model.number="form.credit" 
                type="number" 
                min="0" 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm border p-2 text-sm focus:ring-indigo-500 focus:border-indigo-500" 
              />
            </div>
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
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">COA</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Debit</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Credit</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 text-sm">
            <!-- Loading State -->
            <tr v-if="isLoading">
              <td colspan="6" class="px-4 py-8 text-center text-gray-500 animate-pulse">
                Memuat data transaksi...
              </td>
            </tr>

            <!-- Data Rows -->
            <template v-else>
              <tr v-for="t in transactions" :key="t.id" class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ t.tanggal }}</td>
                <td class="px-4 py-3 font-medium text-gray-900">
                  {{ t.chart_of_account?.kode || t.kode_coa }} - {{ t.chart_of_account?.nama || '-' }}
                </td>
                <td class="px-4 py-3 text-gray-500">{{ t.desc || '-' }}</td>
                <td class="px-4 py-3 text-right font-medium text-gray-800 whitespace-nowrap">
                  {{ formatRupiah(t.debit) }}
                </td>
                <td class="px-4 py-3 text-right font-medium text-gray-800 whitespace-nowrap">
                  {{ formatRupiah(t.credit) }}
                </td>
                <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                  <button @click="editTransaction(t)" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</button>
                  <button @click="deleteTransaction(t.id)" class="text-rose-600 hover:text-rose-900 font-medium">Hapus</button>
                </td>
              </tr>

              <!-- Empty State -->
              <tr v-if="transactions.length === 0">
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada data transaksi.</td>
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