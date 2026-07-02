<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia; // 1. Pastikan import class Inertia di sini

Route::get('/', function () {
    // 2. Render halaman Welcome.vue yang ada di folder resources/js/Pages/
    return Inertia::render('Welcome');
});
