
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { showToast } from 'vant'
import 'vant/es/toast/style'
import logoUrl from '../../../img/logo.jpeg'

defineProps<{
  errors: {
    email?: string
    password?: string
  }
}>()

// Ganti inject dengan akses global window.route
const route = (window as any).route

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const onSubmit = () => {
  // Pastikan route tersedia, jika tidak gunakan path absolut sebagai fallback
  const urlTujuan = route ? route('login') : '/batubatapagarmerbau/public/login'

  form.post(urlTujuan, {
    onFinish: () => {
      form.password = ''
    },
    onError: () => {
      showToast({
        type: 'fail',
        message: 'Gagal masuk, periksa kembali data Anda.',
      })
    }
  })
}
</script>
<template>
  <div class="relative flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-50 via-orange-50/40 to-slate-100 px-4 py-12 sm:px-6 lg:px-8">

    <!-- Dekorasi Lingkaran Abstrak Modern di Latar Belakang -->
    <div class="absolute top-0 left-0 -translate-x-12 -translate-y-12 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 translate-x-12 translate-y-12 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md space-y-6 z-10">

      <!-- Logo & Branding Instansi Kecamatan -->
      <div class="text-center space-y-2">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full overflow-hidden bg-white shadow-xl shadow-orange-500/10 border-2 border-orange-500/20 p-1 mx-auto transition-transform duration-500 hover:scale-105">
          <img :src="logoUrl" alt="Logo Kecamatan Pagar Merbau" class="w-full h-full object-contain rounded-full" />
        </div>
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
            BATU BATA
          </h1>
          <p class="text-xs font-bold uppercase tracking-widest text-orange-600 mt-1">
            Kecamatan Pagar Merbau
          </p>
        </div>

        <!-- Slogan Kepanjangan Aplikasi -->
        <p class="text-xs text-slate-500 max-w-xs mx-auto pt-1 italic border-t border-slate-200/60 leading-relaxed">
          "Bersama Aparatur Tertib Urusan Berkas Aman, Transparan dan Akurat"
        </p>
      </div>

      <!-- Kartu Form Login Utama -->
      <div class="bg-white/80 backdrop-blur-md p-8 rounded-3xl shadow-xl shadow-slate-200/40 border border-white">
        <div class="mb-6">
          <h3 class="text-lg font-bold text-slate-800">Login Masyarakat / Petugas</h3>
          <p class="text-xs text-slate-400">Urus KTP, Kartu Keluarga, & berkas lainnya langsung dari rumah.</p>
        </div>

        <van-form @submit="onSubmit" class="space-y-5">

          <!-- Input Alamat Email -->
          <div class="space-y-1">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider pl-1">Alamat Email</label>
            <van-field
              v-model="form.email"
              name="email"
              placeholder="contoh@email.com"
              :error-message="errors.email"
              :rules="[{ required: true, message: 'Email tidak boleh kosong' }]"
              type="email"
              left-icon="envelope-o"
              class="!bg-slate-50 border border-slate-200 focus-within:border-orange-500 rounded-xl !p-3 transition-colors duration-200"
            />
          </div>

          <!-- Input Kata Sandi -->
          <div class="space-y-1">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider pl-1">Kata Sandi</label>
            <van-field
              v-model="form.password"
              name="password"
              type="password"
              placeholder="••••••••"
              :error-message="errors.password"
              :rules="[{ required: true, message: 'Password tidak boleh kosong' }]"
              left-icon="lock"
              class="!bg-slate-50 border border-slate-200 focus-within:border-orange-500 rounded-xl !p-3 transition-colors duration-200"
            />
          </div>

          <!-- Fitur Ingat Sesi (Remember Me) -->
          <div class="flex items-center justify-between pt-1 px-1">
            <van-checkbox v-model="form.remember" shape="square" icon-size="16px" checked-color="#ea580c" class="text-xs text-slate-600 font-medium">
              Ingat sesi masuk saya
            </van-checkbox>
          </div>

          <!-- Tombol Aksi Submit Form -->
          <div class="pt-2">
            <van-button
              round
              block
              type="primary"
              native-type="submit"
              :loading="form.processing"
              loading-text="Memvalidasi Data..."
              class="!bg-gradient-to-r !from-orange-600 !to-amber-500 !border-0 h-12 text-sm font-bold shadow-md shadow-orange-500/20 active:opacity-90 transition-opacity"
            >
              MASUK KE LAYANAN
            </van-button>
          </div>

        </van-form>
      </div>

      <!-- Footer Hak Cipta -->
      <div class="text-center text-xs text-slate-400">
        &copy; 2026 Pagar Merbau Digital. All rights reserved.
      </div>

    </div>
  </div>
</template>

<style scoped>
/* Reset bayangan default komponen Vant agar rapi menyatu dengan border Tailwind */
:deep(.van-field) {
  box-shadow: none !important;
}
:deep(.van-field__error-message) {
  font-size: 11px;
  margin-top: 4px;
  padding-left: 4px;
}
/* Mengubah rona warna ikon bawaan Vant menjadi oranye ketika form sedang aktif fokus */
:deep(.van-field:focus-within .van-icon) {
  color: #ea580c !important;
}
</style>
