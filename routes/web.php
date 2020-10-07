<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\HalamanController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KontenController;
use App\Http\Controllers\Admin\PesanController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
})->name('/');

Route::post('slug', function (Request $request) {
    return Str::slug($request->input('nama'));
})->name('slug');

Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('login', [AuthController::class, 'login_proses'])->name('login_proses');
});

Route::prefix('admin')->group(function () {

    Route::get('/', [HomeController::class, 'dashboard'])->name('admin');

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.user');
        Route::get('info', [UserController::class, 'info'])->name('admin.user.info');
        Route::post('search', [UserController::class, 'search'])->name('admin.user.search');
        Route::post('save', [UserController::class, 'save'])->name('admin.user.save');
        Route::post('delete', [UserController::class, 'delete'])->name('admin.user.delete');
    });

    Route::prefix('halaman')->group(function () {
        Route::get('/', [HalamanController::class, 'index'])->name('admin.halaman');
        Route::get('info', [HalamanController::class, 'info'])->name('admin.halaman.info');
        Route::get('konten', [HalamanController::class, 'konten'])->name('admin.halaman.konten');
        Route::post('search', [HalamanController::class, 'search'])->name('admin.halaman.search');
        Route::post('save', [HalamanController::class, 'save'])->name('admin.halaman.save');
        Route::post('delete', [HalamanController::class, 'delete'])->name('admin.halaman.delete');
        Route::post('kode_otomatis', [HalamanController::class, 'kode_otomatis'])->name('admin.halaman.kode_otomatis');
        Route::post('reposisi', [HalamanController::class, 'reposisi'])->name('admin.halaman.reposisi');
    });

    Route::prefix('konten')->group(function () {
        Route::get('/', [KontenController::class, 'index'])->name('admin.konten');
        Route::get('info', [KontenController::class, 'info'])->name('admin.konten.info');
        Route::post('search', [KontenController::class, 'search'])->name('admin.konten.search');
        Route::post('save', [KontenController::class, 'save'])->name('admin.konten.save');
        Route::post('delete', [KontenController::class, 'delete'])->name('admin.konten.delete');
    });

    Route::prefix('kategori')->group(function () {
        Route::get('/', [KategoriController::class, 'index'])->name('admin.kategori');
        Route::get('info', [KategoriController::class, 'info'])->name('admin.kategori.info');
        Route::post('search', [KategoriController::class, 'search'])->name('admin.kategori.search');
        Route::post('save', [KategoriController::class, 'save'])->name('admin.kategori.save');
        Route::post('delete', [KategoriController::class, 'delete'])->name('admin.kategori.delete');
        Route::post('kode_otomatis', [KategoriController::class, 'kode_otomatis'])->name('admin.kategori.kode_otomatis');
        Route::post('reposisi', [KategoriController::class, 'reposisi'])->name('admin.kategori.reposisi');
    });

    Route::prefix('pesan')->group(function () {
        Route::get('/', [PesanController::class, 'index'])->name('admin.pesan');
        Route::post('search', [PesanController::class, 'search'])->name('admin.pesan.search');
        Route::post('delete', [PesanController::class, 'delete'])->name('admin.pesan.delete');
    });

    Route::prefix('berita')->group(function () {
        Route::get('/', [BeritaController::class, 'index'])->name('admin.berita');
        Route::get('info', [BeritaController::class, 'info'])->name('admin.berita.info');
        Route::post('search', [BeritaController::class, 'search'])->name('admin.berita.search');
        Route::post('save', [BeritaController::class, 'save'])->name('admin.berita.save');
        Route::post('delete', [BeritaController::class, 'delete'])->name('admin.berita.delete');
    });

});
