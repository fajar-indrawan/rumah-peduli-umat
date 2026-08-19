<script setup lang="ts">
// Interface
interface NavCard {
  title: string
  description: string
  to: string
  borderColor: string
  textColor: string
}

// Data Card Navigasi
const navCards: NavCard[] = [
  {
    title: 'Kelola Kategori',
    description: 'Atur pengelompokan Income & Expense.',
    to: '/categories',
    borderColor: 'border-indigo-500',
    textColor: 'text-indigo-600 hover:text-indigo-800',
  },
  {
    title: 'Chart of Accounts (COA)',
    description: 'Kelola daftar Chart Of Account.',
    to: '/chart-of-accounts',
    borderColor: 'border-blue-500',
    textColor: 'text-blue-600 hover:text-blue-800',
  },
  {
    title: 'Manajemen Transaksi',
    description: 'Catat dan kelola transaksi finansial.',
    to: '/transactions',
    borderColor: 'border-amber-500',
    textColor: 'text-amber-600 hover:text-amber-800',
  },
  {
    title: 'Laporan Profit & Loss',
    description: 'Lihat ringkasan laba rugi dan unduh Excel.',
    to: '/reports/profit-loss',
    borderColor: 'border-emerald-500',
    textColor: 'text-emerald-600 hover:text-emerald-800',
  },
]
</script>

<template>
  <div>
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Dashboard</h1>

    <!-- Grid Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div
        v-for="card in navCards"
        :key="card.to"
        :class="[
          'bg-white p-6 rounded-xl shadow-sm hover:shadow-md border-l-4 transition-all duration-200 flex flex-col justify-between',
          card.borderColor
        ]"
      >
        <div>
          <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
            {{ card.title }}
          </h2>
          <p class="text-gray-600 mt-2 text-sm leading-relaxed">
            {{ card.description }}
          </p>
        </div>

        <NuxtLink
          :to="card.to"
          :class="['inline-flex items-center mt-4 font-medium text-sm group transition-colors', card.textColor]"
        >
          <span>Buka {{ card.title }}</span>
          <span class="ml-1 group-hover:translate-x-1 transition-transform">&rarr;</span>
        </NuxtLink>
      </div>
    </div>
  </div>
</template>