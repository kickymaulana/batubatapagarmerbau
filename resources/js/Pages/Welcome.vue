<script setup lang="ts">
import { ref, inject } from 'vue'

const nama = ref<string>('Kicky')

// Ambil inject route jika ingin memanggil route() di dalam blok <script>
const route = inject('route') as any

const testKlikTombol = () => {
  // Menghasilkan URL: /tes-parameter/99
  const urlHasilZiggy = route('testing.route', { id: 99 })

  alert('URL yang dihasilkan Ziggy: ' + urlHasilZiggy)
  window.location.href = urlHasilZiggy
}
</script>

<template>
  <div class="flex min-h-screen flex-col items-center justify-center gap-6 bg-gray-100">

    <h1 class="text-3xl font-bold text-blue-600">
      Halo, {{ nama }}! Inertia v3 + TypeScript Berhasil!
    </h1>

    <div class="flex gap-4">
      <!-- Tes 1: Menggunakan fungsi route() langsung di template template -->
      <a
        :href="route('testing.route', { id: 123 })"
        class="rounded bg-green-600 px-4 py-2 font-medium text-white hover:bg-green-700 transition"
      >
        Tes Link Tag (:href) - ID 123
      </a>

      <!-- Tes 2: Menggunakan fungsi route() di dalam Script lewat tombol klik -->
      <button
        @click="testKlikTombol"
        class="rounded bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700 transition"
      >
        Tes Klik Tombol Script - ID 99
      </button>
    </div>

  </div>
</template>
