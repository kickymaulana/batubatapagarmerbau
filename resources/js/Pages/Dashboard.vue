<script setup lang="ts">
import { ref, inject } from 'vue'
import { useForm, router } from '@inertiajs/vue3'

const route = inject('route') as any

// Properti dari Backend Laravel
const props = defineProps<{
  auth: {
    user: { name: string; email: string }
    isAdmin: boolean
  }
  permohonans: Array<{
    id: number
    jenis_berkas: string
    nomor_kk: string
    nik_pemohon: string
    foto_bukti: string
    foto_hasil: string | null
    status: string
    user?: { name: string }
  }>
}>()

// --- STATE FORM MASYARAKAT ---
const showFormPermohonan = ref(false)
const formMasyarakat = useForm({
  jenis_berkas: 'Kartu Keluarga (KK)',
  nomor_kk: '',
  nik_pemohon: '',
  foto_bukti: null as File | null,
})

// --- STATE FORM ADMIN ---
const showModalHasil = ref(false)
const idPermohonanDipilih = ref<number | null>(null)
const formAdminHasil = useForm({
  foto_hasil: null as File | null,
  catatan: ''
})

// Fungsi Handle Upload File Dokumen
const onFileChangeMasyarakat = (event: any) => {
  const file = event.target.files[0]
  if (file) formMasyarakat.foto_bukti = file
}

const onFileChangeAdmin = (event: any) => {
  const file = event.target.files[0]
  if (file) formAdminHasil.foto_hasil = file
}

// Aksi Kirim Berkas Pengajuan (Masyarakat)
const kirimPermohonan = () => {
  formMasyarakat.post(route('permohonan.store'), {
    onSuccess: () => {
      showFormPermohonan.value = false
      formMasyarakat.reset()
      showToast({ type: 'success', message: 'Permohonan berhasil dikirim!' })
    }
  })
}

// Aksi Kecamatan Teruskan ke Aplikasi Pusat
const teruskanKePusat = (id: number) => {
  router.post(route('permohonan.proses-pusat', { id }), {}, {
    onSuccess: () => showToast({ type: 'success', message: 'Berkas berhasil diteruskan ke aplikasi pusat!' })
  })
}

// Buka Dialog Upload Hasil (Admin)
const bukaModalSelesai = (id: number) => {
  idPermohonanDipilih.value = id
  showModalHasil.value = true
}

// Aksi Kecamatan Upload Hasil Akhir Berkas
const kirimHasilSelesai = () => {
  if (!idPermohonanDipilih.value) return
  formAdminHasil.post(route('permohonan.selesai', { id: idPermohonanDipilih.value }), {
    onSuccess: () => {
      showModalHasil.value = false
      formAdminHasil.reset()
      showToast({ type: 'success', message: 'Hasil Berkas Berhasil Di-upload!' })
    }
  })
}

// Fungsi Cetak Berkas Mandiri (Masyarakat)
const cetakDokumen = (urlGambar: string) => {
  const tetingkapCetak = window.open('', '_blank')
  if (tetingkapCetak) {
    tetingkapCetak.document.write(`
      <html>
        <head><title>Cetak Dokumen BATU BATA</title></head>
        <body style="margin:0; display:flex; justify-content:center; align-items:center;">
          <img src="${urlGambar}" style="max-width:100%; height:auto;" onload="window.print(); window.close();"/>
        </body>
      </html>
    `)
    tetingkapCetak.document.close()
  }
}

const logout = () => router.post(route('logout'))
</script>

<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 font-sans">

    <!-- Navbar / Header Atas -->
    <header class="bg-gradient-to-r from-orange-600 to-amber-500 text-white shadow-md">
      <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <div>
          <h1 class="text-xl font-black tracking-wide">BATU BATA DASHBOARD</h1>
          <p class="text-xs opacity-90">Kecamatan Pagar Merbau</p>
        </div>
        <div class="flex items-center gap-4">
          <span class="text-sm font-semibold hidden sm:inline">Halo, {{ auth.user.name }} ({{ auth.isAdmin ? 'Admin' : 'Warga' }})</span>
          <van-button size="small" plain class="!text-white !border-white !bg-transparent rounded-lg" @click="logout">Keluar</van-button>
        </div>
      </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8 space-y-6">

      <!-- ======================================================== -->
      <!-- TAMPILAN KHUSUS WARGA (MASYARAKAT)                       -->
      <!-- ======================================================== -->
      <div v-if="!auth.isAdmin" class="space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h2 class="text-lg font-bold">Layanan Mandiri Pengurusan Berkas</h2>
            <p class="text-xs text-slate-500">Ajukan berkas dokumen kependudukan Anda tanpa perlu keluar rumah.</p>
          </div>
          <van-button type="primary" class="!bg-orange-600 !border-orange-600 rounded-xl font-bold" icon="plus" @click="showFormPermohonan = true">
            Buat Permohonan Baru
          </van-button>
        </div>

        <!-- Pop up / Drawer Form Pengajuan Baru -->
        <van-popup v-model:show="showFormPermohonan" position="bottom" round class="p-6 !max-h-[85%] space-y-4">
          <h3 class="text-lg font-bold text-slate-800 border-b pb-2">Formulir Permohonan Dokumen</h3>

          <div class="space-y-4">
            <div>
              <label class="text-xs font-bold text-slate-600">Jenis Dokumen</label>
              <select v-model="formMasyarakat.jenis_berkas" class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-orange-500 outline-none">
                <option>Kartu Keluarga (KK)</option>
                <option>Kartu Tanda Penduduk (KTP)</option>
                <option>Surat Keterangan Pindah</option>
              </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="text-xs font-bold text-slate-600">Nomor KK (Opsional)</label>
                <input v-model="formMasyarakat.nomor_kk" type="number" placeholder="Masukkan 16 digit No. KK" class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-orange-500"/>
              </div>
              <div>
                <label class="text-xs font-bold text-slate-600">NIK Pemohon (Opsional)</label>
                <input v-model="formMasyarakat.nik_pemohon" type="number" placeholder="Masukkan 16 digit NIK" class="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-orange-500"/>
              </div>
            </div>

            <div>
              <label class="text-xs font-bold text-slate-600 block mb-1">Upload Berkas Syarat / Foto Bukti Lama</label>
              <input type="file" accept="image/*" @change="onFileChangeMasyarakat" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 w-full"/>
            </div>

            <van-button block type="primary" class="!bg-orange-600 !border-orange-600 rounded-xl h-12 font-bold mt-4" :loading="formMasyarakat.processing" @click="kirimPermohonan">
              KIRIM BERKAS SEKARANG
            </van-button>
          </div>
        </van-popup>
      </div>

      <!-- ======================================================== -->
      <!-- TABEL RIWAYAT / DAFTAR KELOLA BERKAS (ADMIN & WARGA)    -->
      <!-- ======================================================== -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="p-5 border-b border-slate-100">
          <h3 class="font-bold text-slate-800">{{ auth.isAdmin ? 'Seluruh Antrean Permohonan Masuk' : 'Status Permohonan Berkas Anda' }}</h3>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100">
                <th class="p-4" v-if="auth.isAdmin">Pemohon</th>
                <th class="p-4">Jenis Berkas</th>
                <th class="p-4">Berkas Warga</th>
                <th class="p-4">Status Proses</th>
                <th class="p-4">Aksi / Hasil Akhir</th>
              </tr>
            </thead>
            <tbody class="text-sm divide-y divide-slate-100">
              <tr v-for="item in permohonans" :key="item.id" class="hover:bg-slate-50/60 transition">
                <td class="p-4 font-semibold" v-if="auth.isAdmin">{{ item.user?.name }}</td>
                <td class="p-4">
                  <div class="font-semibold text-slate-800">{{ item.jenis_berkas }}</div>
                  <div class="text-xs text-slate-400">ID: #{{ item.id }}</div>
                </td>
                <td class="p-4">
                  <a :href="item.foto_bukti" target="_blank" class="text-orange-600 hover:underline font-medium text-xs inline-flex items-center gap-1">
                    👁️ Lihat Berkas Warga
                  </a>
                </td>
                <td class="p-4">
                  <span v-if="item.status === 'pending'" class="px-2.5 py-1 text-xs font-bold bg-amber-50 text-amber-700 rounded-full border border-amber-200">Menunggu Verifikasi</span>
                  <span v-else-if="item.status === 'diproses_pusat'" class="px-2.5 py-1 text-xs font-bold bg-blue-50 text-blue-700 rounded-full border border-blue-200">Sedang di Aplikasi Pusat</span>
                  <span v-else-if="item.status === 'selesai'" class="px-2.5 py-1 text-xs font-bold bg-green-50 text-green-700 rounded-full border border-green-200">Selesai / Disetujui</span>
                </td>
                <td class="p-4">
                  <!-- AKSI UTK ADMIN KECAMATAN -->
                  <div v-if="auth.isAdmin" class="flex gap-2">
                    <van-button v-if="item.status === 'pending'" size="small" class="!bg-blue-600 !text-white rounded-lg" @click="teruskanKePusat(item.id)">
                      Teruskan ke Pusat
                    </van-button>
                    <van-button v-if="item.status === 'diproses_pusat'" size="small" class="!bg-green-600 !text-white rounded-lg" @click="bukaModalSelesai(item.id)">
                      Upload Hasil Akhir
                    </van-button>
                    <span v-if="item.status === 'selesai'" class="text-xs font-medium text-slate-400">Sudah Selesai dikerjakan</span>
                  </div>

                  <!-- AKSI UTK MASYARAKAT (CETAK MANDIRI) -->
                  <div v-else>
                    <van-button v-if="item.status === 'selesai' && item.foto_hasil" size="small" type="success" class="!bg-green-600 rounded-lg font-bold shadow-sm" icon="printer" @click="cetakDokumen(item.foto_hasil)">
                      Cetak Dokumen Mandiri
                    </van-button>
                    <span v-else class="text-xs text-slate-400">Berkas sedang diproses petugas</span>
                  </div>
                </td>
              </tr>
              <tr v-if="permohonans.length === 0">
                <td colspan="5" class="p-8 text-center text-slate-400">Belum ada antrean permohonan berkas saat ini.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Popup Modal Upload Hasil Berkas untuk Admin -->
      <van-popup v-model:show="showModalHasil" position="center" round class="p-6 w-[90%] max-w-sm space-y-4">
        <h3 class="text-md font-bold text-slate-800">Upload Hasil Berkas Selesai</h3>
        <p class="text-xs text-slate-500">Unggah foto KK/KTP hasil akhir dari aplikasi pusat agar bisa dicetak langsung oleh warga.</p>

        <div class="space-y-4 pt-2">
          <input type="file" accept="image/*" @change="onFileChangeAdmin" class="text-xs text-slate-500 file:py-1.5 file:px-3 file:rounded-full file:bg-green-50 file:text-green-700 file:border-0 w-full"/>

          <van-button block type="success" class="!bg-green-600 !border-green-600 rounded-xl font-bold" :loading="formAdminHasil.processing" @click="kirimHasilSelesai">
            Simpan & Terbitkan Hasil
          </van-button>
        </div>
      </van-popup>

    </main>
  </div>
</template>
