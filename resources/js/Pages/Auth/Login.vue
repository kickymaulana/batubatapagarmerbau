<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

defineProps<{
  errors: {
    email?: string
    password?: string
  }
}>()

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const onSubmit = () => {
  form.post(route('login'), {
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

    <!-- Dekorasi Background Modern (Gradasi Oranye) -->
    <div class="absolute top-0 left-0 -translate-x-12 -translate-y-12 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 translate-x-12 translate-y-12 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md space-y-6 z-10">

      <!-- Logo & Branding Instansi -->
      <div class="text-center space-y-2">
        <!-- Badge Logo diubah ke Gradasi Oranye Semangka/Amber -->
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-orange-600 to-amber-500 shadow-lg shadow-orange-500/20 text-white font-black text-2xl tracking-wider">
          BB
        </div>
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">
            BATU BATA
          </h1>
          <!-- Text Sub-Header Oranye -->
          <p class="text-xs font-bold uppercase tracking-widest text-orange-600 mt-1">
            Kecamatan Pagar Merbau
          </p>
        </div>

        <!-- Slogan -->
        <p class="text-xs text-slate-500 max-w-xs mx-auto pt-1 italic border-t border-slate-200/60 leading-relaxed">
          "Bersama Aparatur Tertib Urusan Berkas Aman, Transparan dan Akurat"
        </p>
      </div>

      <!-- Kartu Form Utama -->
      <div class="bg-white/80 backdrop-blur-md p-8 rounded-3xl shadow-xl shadow-slate-200/40 border border-white">
        <div class="mb-6">
          <h3 class="text-lg font-bold text-slate-800">Login Masyarakat / Petugas</h3>
          <p class="text-xs text-slate-400">Urus KTP, Kartu Keluarga, & berkas lainnya langsung dari rumah.</p>
        </div>

        <van-form @submit="onSubmit" class="space-y-5">

          <!-- Input Email -->
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

          <!-- Input Password -->
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

          <!-- Remember Me -->
          <div class="flex items-center justify-between pt-1 px-1">
            <van-checkbox v-model="form.remember" shape="square" icon-size="16px" checked-color="#ea580c" class="text-xs text-slate-600 font-medium">
              Ingat sesi masuk saya
            </van-checkbox>
          </div>

          <!-- Tombol Submit Premium Tema Oranye -->
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

      <!-- Footer Info -->
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
/* Menyelaraskan warna ikon fokus internal Vant ke warna oranye */
:deep(.van-field:focus-within .van-icon) {
  color: #ea580c !important; /* orange-600 */
}
</style>
