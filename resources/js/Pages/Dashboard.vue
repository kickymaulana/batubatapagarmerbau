<!-- resources/js/Pages/Dashboard.vue -->
<script setup lang="ts">
import { ref, computed, inject } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { showToast } from 'vant'
import 'vant/es/toast/style'

const route = (window as any).route

const props = defineProps<{
  auth: { user: { name: string; email: string }; isAdmin: boolean }
  layanans: Array<{ id: number; nama_layanan: string; deskripsi: string; syarats: Array<{ id: number; nama_syarat: string }> }>
  permohonans: Array<{
    id: number; status: string; file_hasil_pdf: string | null; catatan_admin: string | null;
    user: { name: string }
    layanan: { nama_layanan: string }
    details: Array<{ id: number; file_foto_syarat: string; syarat: { nama_syarat: string } }>
  }>
}>()

// --- FORMS & STATE WARGA ---
const showFormWarga = ref(false)
const idLayananDipilih = ref<number | null>(null)
const fileInputs = ref<Record<number, File>>({})

// Mencari syarat dari layanan yang dipilih secara realtime
const syaratDinamis = computed(() => {
  const layanan = props.layanans.find(l => l.id === idLayananDipilih.value)
  return layanan ? layanan.syarats : []
})

const handleFileChange = (event: any, syaratId: number) => {
  const file = event.target.files[0]
  if (file) fileInputs.value[syaratId] = file
}

const kirimPermohonanWarga = () => {
  if (!idLayananDipilih.value) return

  // Menggunakan FormData karena mengirim multiple file dinamis
  const formData = new FormData()
  formData.append('layanan_id', idLayananDipilih.value.toString())

  Object.keys(fileInputs.value).forEach((syaratId) => {
    formData.append(`syarat_files[${syaratId}]`, fileInputs.value[Number(syaratId)])
  })

  router.post(route('permohonan.store'), formData as any, {
    onSuccess: () => {
      showFormWarga.value = false
      idLayananDipilih.value = null
      fileInputs.value = {}
      showToast({ type: 'success', message: 'Permohonan Berhasil Diajukan!' })
    }
  })
}

// --- FORMS & STATE ADMIN ---
const showModalSelesai = ref(false)
const idPermohonanAktif = ref<number | null>(null)
const formSelesaiAdmin = useForm({
  file_hasil_pdf: null as File | null,
  catatan_admin: ''
})

const handlePDFChange = (event: any) => {
  formSelesaiAdmin.file_hasil_pdf = event.target.files[0]
}

const teruskanKeSIAK = (id: number) => {
  router.post(route('permohonan.siak', { id }), {}, {
    onSuccess: () => showToast('Status diperbarui: Sedang diproses di SIAK Pusat')
  })
}

const bukaModalUploadSelesai = (id: number) => {
  idPermohonanAktif.value = id
  showModalSelesai.value = true
}

const simpanHasilSIAK = () => {
  if (!idPermohonanAktif.value) return
  formSelesaiAdmin.post(route('permohonan.selesai', { id: idPermohonanAktif.value }), {
    onSuccess: () => {
      showModalSelesai.value = false
      formSelesaiAdmin.reset()
      showToast({ type: 'success', message: 'File PDF SIAK Berhasil Diterbitkan!' })
    }
  })
}

// --- ADMIN TAMBAH LAYANAN BARU ---
const showFormLayananBaru = ref(false)
const formLayananBaru = useForm({
  nama_layanan: '',
  deskripsi: '',
  syarats: ['']
})
const tambahFieldSyarat = () => formLayananBaru.syarats.push('')
const simpanLayananBaru = () => {
  formLayananBaru.post(route('layanan.store'), {
    onSuccess: () => {
      showFormLayananBaru.value = false
      formLayananBaru.reset()
      showToast('Layanan & Syarat baru berhasil ditambahkan!')
    }
  })
}

const getStorageUrl = (path: string | null) => {
  if (!path) return ''
  if (path.startsWith('http://') || path.startsWith('https://')) return path
  const cleanPath = path.startsWith('/') ? path.substring(1) : path
  const baseUrl = (window as any).Ziggy?.url || ''
  const prefix = baseUrl ? (baseUrl.endsWith('/') ? baseUrl : baseUrl + '/') : '/'
  return prefix + cleanPath
}

const logout = () => router.post(route('logout'))
</script>

<template>
  <div class="min-h-screen bg-slate-50 text-slate-800">
    <!-- Navbar / Header -->
    <header class="bg-gradient-to-r from-orange-600 to-amber-500 text-white p-4 shadow-md">
      <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div>
          <h1 class="text-xl font-black">BATU BATA DASHBOARD</h1>
          <p class="text-xs opacity-90">Kecamatan Pagar Merbau &bull; Sistem Hub SIAK</p>
        </div>
        <div class="flex items-center gap-4">
          <span class="text-sm font-semibold hidden sm:inline">Halo, {{ auth.user.name }} ({{ auth.isAdmin ? 'Admin Kecamatan' : 'Warga' }})</span>
          <van-button size="small" plain class="!text-white !border-white !bg-transparent rounded-lg" @click="logout">Keluar</van-button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8 space-y-6">

      <!-- AKSI DARI SISI WARGA -->
      <div v-if="!auth.isAdmin" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex justify-between items-center">
        <div>
          <h2 class="text-md font-bold">Ajukan Permohonan Layanan Baru</h2>
          <p class="text-xs text-slate-500">Pilih jenis berkas dan penuhi persyaratan digitalnya.</p>
        </div>
        <van-button type="primary" class="!bg-orange-600 !border-orange-600 rounded-xl" icon="plus" @click="showFormWarga = true">Buat Pengajuan</van-button>
      </div>

      <!-- AKSI DARI SISI ADMIN (Bisa nambah master layanan mandiri) -->
      <div v-if="auth.isAdmin" class="bg-white p-4 rounded-xl border border-dashed border-slate-300 flex justify-between items-center">
        <span class="text-xs text-slate-500 font-medium">Pengaturan Layanan Dinas Kecamatan:</span>
        <van-button size="small" type="warning" class="rounded-lg" icon="setting-o" @click="showFormLayananBaru = true">Tambah Master Layanan & Syarat</van-button>
      </div>

      <!-- TABEL MONITORING UTAMA (WARGA & ADMIN) -->
      <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="p-4 bg-slate-50 font-bold text-sm border-b">
          {{ auth.isAdmin ? 'Daftar Pengajuan Warga Masuk (Hub SIAK)' : 'Status Pengajuan Berkas Saya' }}
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 text-xs text-slate-500 uppercase">
              <tr>
                <th class="p-4" v-if="auth.isAdmin">Nama Warga</th>
                <th class="p-4">Jenis Layanan</th>
                <th class="p-4">Dokumen Syarat Warga</th>
                <th class="p-4">Status Alur</th>
                <th class="p-4">Aksi / File SIAK</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <tr v-for="item in permohonans" :key="item.id" class="hover:bg-slate-50/50">
                <td class="p-4 font-bold" v-if="auth.isAdmin">{{ item.user.name }}</td>
                <td class="p-4 font-semibold text-slate-700">{{ item.layanan.nama_layanan }}</td>
                <td class="p-4">
                  <div class="flex flex-col gap-1">
                    <div v-for="detail in item.details" :key="detail.id" class="text-xs">
                      <a :href="getStorageUrl(detail.file_foto_syarat)" target="_blank" class="text-orange-600 hover:underline">
                        📷 {{ detail.syarat.nama_syarat }}
                      </a>
                    </div>
                  </div>
                </td>
                <td class="p-4">
                  <span v-if="item.status === 'pending'" class="px-2 py-0.5 text-xs bg-amber-100 text-amber-800 rounded-full">Verifikasi Kecamatan</span>
                  <span v-else-if="item.status === 'diproses_siak'" class="px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">Proses Input Aplikasi SIAK</span>
                  <span v-else-if="item.status === 'selesai'" class="px-2 py-0.5 text-xs bg-green-100 text-green-800 rounded-full">Selesai (PDF Terbit)</span>
                </td>
                <td class="p-4">
                  <!-- Aksi Admin -->
                  <div v-if="auth.isAdmin" class="flex gap-2">
                    <van-button v-if="item.status === 'pending'" size="small" type="primary" class="!bg-blue-600" @click="teruskanKeSIAK(item.id)">Pindahkan ke SIAK</van-button>
                    <van-button v-if="item.status === 'diproses_siak'" size="small" type="success" class="!bg-green-600" @click="bukaModalUploadSelesai(item.id)">Upload PDF SIAK</van-button>
                    <span v-if="item.status === 'selesai'" class="text-xs text-slate-400">Arsip Berhasil Selesai</span>
                  </div>
                  <!-- Aksi Warga (Download PDF Resmi) -->
                  <div v-else>
                    <a v-if="item.status === 'selesai' && item.file_hasil_pdf" :href="getStorageUrl(item.file_hasil_pdf)" target="_blank" class="inline-flex items-center gap-1 text-xs bg-green-600 text-white font-bold py-1.5 px-3 rounded-lg shadow-sm hover:bg-green-700">
                      📥 Download PDF Dokumen Resmi
                    </a>
                    <span v-else class="text-xs text-slate-400 italic">Menunggu verifikasi & proses SIAK petugas</span>
                  </div>
                </td>
              </tr>
              <tr v-if="permohonans.length === 0">
                <td colspan="5" class="p-6 text-center text-slate-400">Belum ada data pengajuan berkas.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- POPUP WARGA: PILIH LAYANAN & UPLOAD SYARAT SECARA DINAMIS -->
      <van-popup v-model:show="showFormWarga" position="bottom" round class="p-6 !max-h-[85%] space-y-4">
        <h3 class="text-md font-bold text-slate-800 border-b pb-2">Formulir Pengajuan Dokumen Mandiri</h3>
        <div>
          <label class="text-xs font-bold text-slate-600">Pilih Jenis Layanan Administrasi</label>
          <select v-model="idLayananDipilih" class="w-full mt-1 p-2.5 bg-slate-50 border rounded-xl text-sm outline-none">
            <option :value="null">-- Pilih Layanan --</option>
            <option v-for="l in layanans" :key="l.id" :value="l.id">{{ l.nama_layanan }}</option>
          </select>
        </div>

        <!-- Bagian ini akan render dinamis sesuai dengan syarat yang ada di database -->
        <div v-if="syaratDinamis.length > 0" class="space-y-3 pt-2">
          <p class="text-xs font-bold text-orange-600 uppercase tracking-wide">Lengkapi Dokumen Persyaratan (Bentuk Gambar/Foto):</p>
          <div v-for="s in syaratDinamis" :key="s.id" class="p-3 bg-slate-50 border rounded-xl">
            <label class="text-xs font-semibold text-slate-700 block mb-1">{{ s.nama_syarat }} *</label>
            <input type="file" accept="image/*" @change="handleFileChange($event, s.id)" class="text-xs text-slate-500 w-full" required/>
          </div>

          <van-button block type="primary" class="!bg-orange-600 !border-orange-600 rounded-xl h-11 font-bold mt-4" @click="kirimPermohonanWarga">
            KIRIM BERKAS KE KECAMATAN
          </van-button>
        </div>
      </van-popup>

      <!-- POPUP ADMIN: UPLOAD PDF DOWNLOAD-AN DARI SIAK -->
      <van-popup v-model:show="showModalSelesai" position="center" round class="p-5 w-[90%] max-w-sm space-y-4">
        <h3 class="text-sm font-bold">Integrasi Dokumen Output SIAK</h3>
        <p class="text-xs text-slate-500">Unggah file hasil resmi (.pdf) yang diperoleh dari aplikasi SIAK pusat.</p>
        <div class="space-y-3 pt-1">
          <input type="file" accept="application/pdf" @change="handlePDFChange" class="text-xs text-slate-600 w-full" />
          <textarea v-model="formSelesaiAdmin.catatan_admin" placeholder="Catatan opsional (misal: Silakan cetak pada kertas HVS 80gr)" class="w-full text-xs p-2 border rounded-lg h-16 outline-none focus:border-green-600"></textarea>
          <van-button block type="success" class="!bg-green-600 rounded-xl font-bold" :loading="formSelesaiAdmin.processing" @click="simpanHasilSIAK">
            Terbitkan & Kirim Ke Warga
          </van-button>
        </div>
      </van-popup>

      <!-- POPUP ADMIN: TAMBAH MASTER LAYANAN BARU -->
      <van-popup v-model:show="showFormLayananBaru" position="center" round class="p-5 w-[90%] max-w-md space-y-3">
        <h3 class="text-sm font-bold">Buat Master Layanan Baru</h3>
        <input v-model="formLayananBaru.nama_layanan" type="text" placeholder="Nama Layanan (Contoh: Akta Kelahiran)" class="w-full text-xs p-2 border rounded-lg outline-none"/>
        <input v-model="formLayananBaru.deskripsi" type="text" placeholder="Deskripsi Singkat" class="w-full text-xs p-2 border rounded-lg outline-none"/>

        <div class="space-y-1">
          <label class="text-xs font-bold text-slate-500">Syarat-Syarat Berkas:</label>
          <div v-for="(syarat, index) in formLayananBaru.syarats" :key="index" class="flex gap-2 mb-1">
            <input v-model="formLayananBaru.syarats[index]" type="text" placeholder="Nama Syarat (Contoh: KK Lama)" class="w-full text-xs p-2 border rounded-lg outline-none"/>
          </div>
          <button type="button" @click="tambahFieldSyarat" class="text-xs text-blue-600 font-semibold">+ Tambah Baris Syarat</button>
        </div>
        <van-button block type="warning" class="rounded-xl font-bold mt-2" @click="simpanLayananBaru">Simpan Konfigurasi Layanan</van-button>
      </van-popup>

    </main>
  </div>
</template>
