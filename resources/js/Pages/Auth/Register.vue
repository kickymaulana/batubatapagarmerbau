
<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { showToast } from 'vant'
import 'vant/es/toast/style'
import logoUrl from '../../../img/logo.jpeg'

defineProps<{
  errors: {
    name?: string
    email?: string
    password?: string
  }
}>()

const route = (window as any).route

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const onSubmit = () => {
  const urlTujuan = route ? route('register') : '/batubatapagarmerbau/public/register'

  form.post(urlTujuan, {
    onFinish: () => {
      form.password = ''
      form.password_confirmation = ''
    },
    onError: () => {
      showToast({
        type: 'fail',
        message: 'Gagal mendaftar, periksa kembali data Anda.',
      })
    }
  })
}
</script>
<template>
  <div class="relative flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-50 via-orange-50/40 to-slate-100 px-4 py-12 sm:px-6 lg:px-8">

    <div class="absolute top-0 left-0 -translate-x-12 -translate-y-12 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 translate-x-12 translate-y-12 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md space-y-6 z-10">

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
        <p class="text-xs text-slate-500 max-w-xs mx-auto pt-1 italic border-t border-slate-200/60 leading-relaxed">
          "Bersama Aparatur Tertib Urusan Berkas Aman, Transparan dan Akurat"
        </p>
      </div>

      <div class="bg-white/80 backdrop-blur-md p-8 rounded-3xl shadow-xl shadow-slate-200/40 border border-white">
        <div class="mb-6">
          <h3 class="text-lg font-bold text-slate-800">Daftar Akun Baru</h3>
          <p class="text-xs text-slate-400">Buat akun untuk mengurus berkas kependudukan secara online.</p>
        </div>

        <van-form @submit="onSubmit" class="space-y-5">

          <div class="space-y-1">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider pl-1">Nama Lengkap</label>
            <van-field
              v-model="form.name"
              name="name"
              placeholder="Masukkan nama lengkap"
              :error-message="errors.name"
              :rules="[{ required: true, message: 'Nama tidak boleh kosong' }]"
              type="text"
              left-icon="contact"
              class="!bg-slate-50 border border-slate-200 focus-within:border-orange-500 rounded-xl !p-3 transition-colors duration-200"
            />
          </div>

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

          <div class="space-y-1">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider pl-1">Kata Sandi</label>
            <van-field
              v-model="form.password"
              name="password"
              type="password"
              placeholder="Minimal 8 karakter"
              :error-message="errors.password"
              :rules="[{ required: true, message: 'Password tidak boleh kosong' }]"
              left-icon="lock"
              class="!bg-slate-50 border border-slate-200 focus-within:border-orange-500 rounded-xl !p-3 transition-colors duration-200"
            />
          </div>

          <div class="space-y-1">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider pl-1">Konfirmasi Kata Sandi</label>
            <van-field
              v-model="form.password_confirmation"
              name="password_confirmation"
              type="password"
              placeholder="Ulangi kata sandi"
              :rules="[{ required: true, message: 'Konfirmasi password tidak boleh kosong' }]"
              left-icon="lock"
              class="!bg-slate-50 border border-slate-200 focus-within:border-orange-500 rounded-xl !p-3 transition-colors duration-200"
            />
          </div>

          <div class="pt-2">
            <van-button
              round
              block
              type="primary"
              native-type="submit"
              :loading="form.processing"
              loading-text="Mendaftarkan..."
              class="!bg-gradient-to-r !from-orange-600 !to-amber-500 !border-0 h-12 text-sm font-bold shadow-md shadow-orange-500/20 active:opacity-90 transition-opacity"
            >
              DAFTAR AKUN
            </van-button>
          </div>

        </van-form>

        <div class="mt-6 text-center">
          <p class="text-xs text-slate-500">
            Sudah punya akun?
            <a :href="route('login')" class="text-orange-600 font-semibold hover:text-orange-700 transition-colors">Masuk disini</a>
          </p>
        </div>
      </div>

      <div class="text-center text-xs text-slate-400">
        &copy; 2026 Pagar Merbau Digital. All rights reserved.
      </div>

    </div>
  </div>
</template>

<style scoped>
:deep(.van-field) {
  box-shadow: none !important;
}
:deep(.van-field__error-message) {
  font-size: 11px;
  margin-top: 4px;
  padding-left: 4px;
}
:deep(.van-field:focus-within .van-icon) {
  color: #ea580c !important;
}
</style>
