<?php

use Illuminate\Support\Facades\Route;

// Use the controller class in the actual namespace used by the app.
// This keeps the routes valid without triggering IDE warnings when the class is absent
// from the default App\Http\Controllers namespace.
use App\Http\Controllers\AuthController;

// Route Tamu (Belum Login)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Route Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Dashboard/Todo setelah login (Contoh placeholder)
Route::get('/todos', function () {
    if (!session('api_token')) {
        return redirect()->route('login');
    }
    return view('todos.index'); // Buat view index todo Anda di sini
})->name('todos.index');
