<script setup lang="ts">
import { ref } from 'vue'

const nama = ref<string>('Kicky')

const picuToast = () => {
  // Fungsi showToast otomatis di-import oleh plugin di belakang layar
  showToast('Halo dari Vant UI!')
}
</script>

<template>
  <div class="flex min-h-screen flex-col items-center justify-center gap-6 bg-gray-100">

    <h1 class="text-3xl font-bold text-blue-600">
      Halo, {{ nama }}! Inertia v3 + TypeScript + Vant!
    </h1>

    <!-- Test Komponen Vant: Otomatis ke-load beserta style CSS-nya -->
    <div class="flex gap-4">
      <van-button type="primary" @click="picuToast">
        Klik Saya (Tes Vant Toast)
      </van-button>

      <van-button type="success" icon="plus">
        Tombol Sukses Vant
      </van-button>
    </div>

  </div>
</template>
