<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\homeController;
use App\Models\Loan;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route untuk halaman homepage dan menangani tampilan
Route::get('/beranda', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk menangani form peminjaman
Route::post('/reservasi', [HomeController::class, 'store'])->name('reservasi.store');

// Pengambilan data agar AJAX bisa mengakses mengambil data dari database melalui link /loan/{id peminjaman}
Route::get('/loan/{id}', function ($id) {
    $loan = Loan::with('room')->find($id); // Load data Loan + Room dengan relasi

    if (!$loan) {
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }
    return response()->json([
        'id' => $loan->id,
        'acara' => $loan->acara,
        'nama_organisasi' => $loan->nama_organisasi,
        'ruangan' => $loan->room ? $loan->room->name : 'Tidak diketahui',
        'tanggal_pinjam' => $loan->tanggal_pinjam,
        'tanggal_kembali' => $loan->tanggal_kembali,
        'keterangan' => $loan->acara ?? 'Tidak ada keterangan',
        'gambar' => $loan->room && $loan->room->image ? asset("storage/" . $loan->room->image) : null, // Perbaikan path gambar
    ]);
});