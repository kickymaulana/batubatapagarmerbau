<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Rute utama (Halaman Welcome)
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home'); // Kita beri nama 'home'

// Rute baru untuk uji coba Ziggy
Route::get('/tes-parameter/{id}', function ($id) {
    return "Berhasil masuk ke rute testing dengan ID: " . $id;
})->name('testing.route'); // Kita beri nama 'testing.route'
