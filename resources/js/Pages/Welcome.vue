<script setup lang="ts">
import { ref } from 'vue'

const nama = ref<string>('Kicky')
</script>

<template>
  <div class="flex min-h-screen items-center justify-center bg-gray-100">
    <h1 class="text-3xl font-bold text-blue-600">Halo, {{ nama }}! Inertia v3 + TypeScript Berhasil!</h1>
  </div>
</template>
