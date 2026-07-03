<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Permohonan;

// ==========================================
// RUTE AUTENTIKASI (LOGIN & LOGOUT)
// ==========================================
Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors(['email' => 'Email atau password salah.']);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


// ==========================================
// RUTE DASHBOARD & PERMOHONAN (HARUS LOGIN)
// ==========================================
Route::middleware(['auth'])->group(function () {

    // Halaman Utama setelah Login
    Route::get('/dashboard', function () {
        $user = Auth::user();

       // Skenario Sederhana: jika email mengandung 'admin', dianggap Admin Kecamatan
        $isAdmin = str_contains($user->email, 'admin');

        $permohonans = $isAdmin
            ? Permohonan::with('user')->latest()->get() // Admin melihat semua
            : Permohonan::where('user_id', $user->id)->latest()->get(); // Masyarakat melihat miliknya sendiri

        return Inertia::render('Dashboard', [
            'auth' => ['user' => $user, 'isAdmin' => $isAdmin],
            'permohonans' => $permohonans
        ]);
    })->name('dashboard');

    // MASYARAKAT: Kirim Permohonan Baru + Upload Foto Bukti
    Route::post('/permohonan', function (Request $request) {
        $request->validate([
            'jenis_berkas' => 'required|string',
            'foto_bukti' => 'required|image|max:2048', // Maksimal 2MB
        ]);

        $path = $request->file('foto_bukti')->store('bukti-permohonan', 'public');

        Permohonan::create([
            'user_id' => Auth::id(),
            'jenis_berkas' => $request->jenis_berkas,
            'nomor_kk' => $request->nomor_kk,
            'nik_pemohon' => $request->nik_pemohon,
            'foto_bukti' => '/storage/' . $path,
            'status' => 'pending',
        ]);

        return redirect()->back();
    })->name('permohonan.store');

    // ADMIN KECAMATAN: Teruskan ke Aplikasi Pusat
    Route::post('/permohonan/{id}/proses-pusat', function ($id) {
        $permohonan = Permohonan::findOrFail($id);
        $permohonan->update(['status' => 'diproses_pusat']);
        return redirect()->back();
    })->name('permohonan.proses-pusat');

    // ADMIN KECAMATAN: Upload Hasil Akhir (KK/KTP Selesai)
    Route::post('/permohonan/{id}/selesai', function (Request $request, $id) {
        $request->validate([
            'foto_hasil' => 'required|image|max:2048',
        ]);

        $permohonan = Permohonan::findOrFail($id);
        $path = $request->file('foto_hasil')->store('hasil-permohonan', 'public');

        $permohonan->update([
            'status' => 'selesai',
            'foto_hasil' => '/storage/' . $path,
            'catatan' => $request->catatan
        ]);

        return redirect()->back();
    })->name('permohonan.selesai');
});
