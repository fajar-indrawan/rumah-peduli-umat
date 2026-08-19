<script setup lang="ts">
import { useApi } from './../composables/useApi'
const { data, pending, error, refresh } = await useApi('http://127.0.0.1:8000/api/categories')
</script>

<template>
  <div class="min-h-screen bg-gray-100 font-sans text-gray-800">
    <!-- Navbar Top -->
    <nav class="bg-indigo-600 text-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <div class="flex items-center space-x-3">
            <span class="font-extrabold text-xl tracking-wide">Accounting System</span>
          </div>
          <div class="flex space-x-2">
            <NuxtLink to="/" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700" active-class="bg-indigo-800">Dashboard</NuxtLink>
            <NuxtLink to="/categories" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700" active-class="bg-indigo-800">Kategori</NuxtLink>
            <NuxtLink to="/chart-of-accounts" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700" active-class="bg-indigo-800">Chart Of Account</NuxtLink>
            <NuxtLink to="/transactions" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700" active-class="bg-indigo-800">Transaksi</NuxtLink>
            <NuxtLink to="/reports/profit-loss" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700" active-class="bg-indigo-800">Laporan Laba Rugi</NuxtLink>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
      <NuxtPage />
    </main>
  </div>
</template>