<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Halaman utama (Welcome)
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Rute Tampilan Login
Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

// Rute Aksi Post Login
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        // Arahkan ke halaman utama atau dashboard setelah sukses login
        return redirect()->intended('/');
    }

    // Jika gagal, kembalikan dengan error khusus field email
    return back()->withErrors([
        'email' => 'Email atau password yang Anda masukkan salah.',
    ]);
});
