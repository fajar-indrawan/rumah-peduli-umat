<script setup lang="ts">
// Interface & Types
interface ReportItem {
  category: string
  tipe: 'income' | 'expense'
  [key: string]: any
}

interface ProcessedReport {
  income: ReportItem[]
  expense: ReportItem[]
  total_income: Record<string, number>
  total_expense: Record<string, number>
  net_income: Record<string, number>
}

interface ApiResponse<T> {
  success: boolean
  message?: string
  data: T
}

const config = useRuntimeConfig()
const apiBase = config.public.apiBase as string

// Filter State (Dinamis per Bulan)
const startMonth = ref<string>('2022-01')
const endMonth = ref<string>('2022-03')

// State Management
const report = ref<ProcessedReport | null>(null)
const loading = ref<boolean>(true)
const errorMessage = ref<string | null>(null)

// Computed untuk Menghasilkan Array Bulan Dinamis
const months = computed<string[]>(() => {
  if (!startMonth.value || !endMonth.value) return []
  
  const list: string[] = []
  let current = new Date(`${startMonth.value}-01`)
  const end = new Date(`${endMonth.value}-01`)

  while (current <= end) {
    const year = current.getFullYear()
    const month = String(current.getMonth() + 1).padStart(2, '0')
    list.push(`${year}-${month}`)
    current.setMonth(current.getMonth() + 1)
  }

  return list
})

// Fetch & Process Data
const fetchReport = async (): Promise<void> => {
  if (months.value.length === 0) return

  loading.value = true
  errorMessage.value = null
  try {
    const res = await $fetch<ApiResponse<ReportItem[]>>(`${apiBase}/reports/profit-loss`, {
      query: { months: months.value },
    })

    const rawData = res.data || []

    const income = rawData.filter((item) => item.tipe === 'income')
    const expense = rawData.filter((item) => item.tipe === 'expense')

    const total_income: Record<string, number> = {}
    const total_expense: Record<string, number> = {}
    const net_income: Record<string, number> = {}

    months.value.forEach((data) => {
      total_income[data] = income.reduce((sum, item) => sum + (parseFloat(item[data]) || 0), 0)
      total_expense[data] = expense.reduce((sum, item) => sum + (parseFloat(item[data]) || 0), 0)
      net_income[data] = total_income[data] - total_expense[data]
    })

    report.value = {
      income,
      expense,
      total_income,
      total_expense,
      net_income,
    }
  } catch (err: any) {
    errorMessage.value = err?.data?.message || err?.message || 'Gagal terhubung ke server Laravel.'
  } finally {
    loading.value = false
  }
}

// Download Excel
const downloadExcel = (): void => {
  const params = new URLSearchParams()
  months.value.forEach((m) => params.append('months[]', m))
  window.open(`${apiBase}/reports/profit-loss/export?${params.toString()}`, '_blank')
}

// Formatting Utility
const formatRupiah = (val: number | string | undefined): string => {
  const num = typeof val === 'string' ? parseFloat(val) : val
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0,
  }).format(num || 0)
}

onMounted(() => fetchReport())
</script>

<template>
  <div class="p-6">
    <!-- Header & Filter -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Laporan Profit & Loss</h1>

      <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
        <!-- Date Picker Filter -->
        <div class="flex items-center space-x-2 bg-white border border-gray-200 rounded-md p-1.5 shadow-sm text-sm">
          <input 
            v-model="startMonth" 
            type="month" 
            @change="fetchReport"
            class="border-none p-1 focus:ring-0 text-gray-700 font-medium" 
          />
          <span class="text-gray-400">s/d</span>
          <input 
            v-model="endMonth" 
            type="month" 
            @change="fetchReport"
            class="border-none p-1 focus:ring-0 text-gray-700 font-medium" 
          />
        </div>

        <button 
          @click="downloadExcel" 
          :disabled="loading || !!errorMessage || !report"
          class="bg-emerald-600 text-white px-4 py-2 rounded-md font-medium text-sm hover:bg-emerald-700 flex items-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed transition"
        >
          <span>Export Excel</span>
        </button>
      </div>
    </div>

    <!-- State Loading -->
    <div v-if="loading" class="bg-white p-12 rounded-xl shadow-sm border border-gray-100 text-center text-gray-500">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-indigo-500 border-t-transparent mb-3"></div>
      <p class="text-sm font-medium">Memuat data laporan...</p>
    </div>

    <!-- State Error -->
    <div v-else-if="errorMessage" class="bg-red-50 border border-red-200 text-red-700 p-6 rounded-xl shadow-sm">
      <p class="font-bold text-base">Gagal memuat data laporan</p>
      <p class="text-sm mt-1 text-red-600">{{ errorMessage }}</p>
      <button @click="fetchReport" class="mt-4 bg-red-600 text-white px-4 py-2 rounded-md text-xs font-semibold hover:bg-red-700 transition">
        Coba Lagi
      </button>
    </div>

    <!-- State Tabel Data -->
    <div v-else-if="report" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Kategori</th>
            <th v-for="item in months" :key="item" class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
              {{ item }}
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-sm">
          <!-- INCOME HEADER -->
          <tr class="bg-emerald-50/70">
            <td :colspan="months.length + 1" class="px-6 py-2.5 font-bold text-emerald-800">INCOME</td>
          </tr>
          <tr v-for="(cat, idx) in report.income" :key="'inc-' + idx" class="hover:bg-gray-50 transition">
            <td class="px-8 py-3 font-medium text-gray-700">{{ cat.category }}</td>
            <td v-for="item in months" :key="item" class="px-6 py-3 text-right text-gray-900 font-mono">
              {{ formatRupiah(cat[item]) }}
            </td>
          </tr>
          <tr class="bg-emerald-100/80 font-bold border-t border-emerald-200">
            <td class="px-6 py-3 text-emerald-900">Total Income</td>
            <td v-for="item in months" :key="item" class="px-6 py-3 text-right text-emerald-900 font-mono">
              {{ formatRupiah(report.total_income[item]) }}
            </td>
          </tr>

          <!-- EXPENSE HEADER -->
          <tr class="bg-rose-50/70">
            <td :colspan="months.length + 1" class="px-6 py-2.5 font-bold text-rose-800">EXPENSE</td>
          </tr>
          <tr v-for="(cat, idx) in report.expense" :key="'exp-' + idx" class="hover:bg-gray-50 transition">
            <td class="px-8 py-3 font-medium text-gray-700">{{ cat.category }}</td>
            <td v-for="item in months" :key="item" class="px-6 py-3 text-right text-gray-900 font-mono">
              {{ formatRupiah(cat[item]) }}
            </td>
          </tr>
          <tr class="bg-rose-100/80 font-bold border-t border-rose-200">
            <td class="px-6 py-3 text-rose-900">Total Expense</td>
            <td v-for="item in months" :key="item" class="px-6 py-3 text-right text-rose-900 font-mono">
              {{ formatRupiah(report.total_expense[item]) }}
            </td>
          </tr>

          <!-- NET INCOME HEADER -->
          <tr class="bg-indigo-600 text-white font-bold text-base">
            <td class="px-6 py-4">Net Income</td>
            <td v-for="item in months" :key="item" class="px-6 py-4 text-right font-mono">
              {{ formatRupiah(report.net_income[item]) }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- State Kosong -->
    <div v-else class="bg-white p-12 rounded-xl shadow-sm border border-gray-100 text-center text-gray-400">
      Data laporan tidak ditemukan untuk periode ini.
    </div>
  </div>
</template>