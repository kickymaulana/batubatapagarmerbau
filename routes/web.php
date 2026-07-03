<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Layanan;
use App\Models\Syarat;
use App\Models\Permohonan;
use App\Models\DetailPermohonan;

// Rute Autentikasi (Tetap sama seperti kode lamamu)
Route::get('/login', function () { return Inertia::render('Auth/Login'); })->name('login');
Route::post('/login', function (Request $request) {
    $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }
    return back()->withErrors(['email' => 'Email atau password salah.']);
});
Route::post('/logout', function (Request $request) {
    Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


// ==========================================
// RUTE DASHBOARD & PROSES BERKAS
// ==========================================
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();
        $isAdmin = str_contains($user->email, 'admin');

        // Ambil semua daftar layanan beserta syaratnya untuk dipilih warga / dikelola admin
        $layanans = Layanan::with('syarats')->get();

        // Ambil data antrean permohonan
        $permohonans = $isAdmin
            ? Permohonan::with(['user', 'layanan', 'details.syarat'])->latest()->get()
            : Permohonan::where('user_id', $user->id)->with(['layanan', 'details.syarat'])->latest()->get();

        return Inertia::render('Dashboard', [
            'auth' => ['user' => $user, 'isAdmin' => $isAdmin],
            'layanans' => $layanans,
            'permohonans' => $permohonans
        ]);
    })->name('dashboard');

    // MASYARAKAT: Kirim Pengajuan Berkas beserta Banyak Foto Syarat sekaligus
    Route::post('/permohonan', function (Request $request) {
        $request->validate([
            'layanan_id' => 'required|exists:layanans,id',
            'syarat_files' => 'required|array',
            'syarat_files.*' => 'required|image|max:2048', // Setiap berkas max 2MB foto
        ]);

        // 1. Buat Induk Permohonan
        $permohonan = Permohonan::create([
            'user_id' => Auth::id(),
            'layanan_id' => $request->layanan_id,
            'status' => 'pending'
        ]);

        // 2. Simpan setiap file syarat yang dikirim dinamis
        foreach ($request->file('syarat_files') as $syaratId => $file) {
            $path = $file->store('warga-syarat', 'public');
            DetailPermohonan::create([
                'permohonan_id' => $permohonan->id,
                'syarat_id' => $syaratId,
                'file_foto_syarat' => '/storage/' . $path
            ]);
        }

        return redirect()->back();
    })->name('permohonan.store');

    // ADMIN: Update Status ke "Diproses di SIAK Pusat"
    Route::post('/permohonan/{id}/proses-siak', function ($id) {
        Permohonan::findOrFail($id)->update(['status' => 'diproses_siak']);
        return redirect()->back();
    })->name('permohonan.siak');

    // ADMIN: Upload File PDF hasil download dari SIAK ke Aplikasi
    Route::post('/permohonan/{id}/selesai-siak', function (Request $request, $id) {
        $request->validate([
            'file_hasil_pdf' => 'required|mimes:pdf|max:5120', // Wajib PDF, Maksimal 5MB
        ]);

        $permohonan = Permohonan::findOrFail($id);
        $path = $request->file('file_hasil_pdf')->store('hasil-siak-pdf', 'public');

        $permohonan->update([
            'status' => 'selesai',
            'file_hasil_pdf' => '/storage/' . $path,
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->back();
    })->name('permohonan.selesai');

    // ADMIN CRUD LAYANAN BARU (Dinamis Tambah/Hapus Layanan & Syarat)
    Route::post('/layanan/store', function (Request $request) {
        $request->validate([
            'nama_layanan' => 'required|string',
            'syarats' => 'required|array'
        ]);

        $layanan = Layanan::create(['nama_layanan' => $request->nama_layanan, 'deskripsi' => $request->deskripsi]);
        foreach ($request->syarats as $namaSyarat) {
            if(!empty($namaSyarat)) {
                Syarat::create(['layanan_id' => $layanan->id, 'nama_syarat' => $namaSyarat]);
            }
        }
        return redirect()->back();
    })->name('layanan.store');
});
