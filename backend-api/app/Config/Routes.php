<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// ====================================================================
// RUTE API UNTUK TUGAS UAS WEB 2 (DECOUPLED ARCHITECTURE)
// ====================================================================

// 1. Rute Otentikasi / Login & Logout (Bisa diakses tanpa token)
$routes->post('auth/login', 'AuthController::login');
$routes->post('auth/logout', 'AuthController::logout');

// 2. Rute RESTful CRUD Barang (Resource Controller)
// GET: Bisa diakses publik oleh pengunjung (Tanpa Login)
$routes->get('barang', 'BarangController::index');
$routes->post('barang/beli', 'BarangController::beli'); // <--- RUTE BARU: Pembelian Publik (Tanpa Filter Auth)

// RUTE BARU: Mengambil riwayat transaksi (Wajib login dan membawa Authorization Bearer Token)
$routes->get('transaksi', 'BarangController::getTransaksi', ['filter' => 'auth']);

// POST, PUT, DELETE: Wajib login dan membawa Authorization Bearer Token Valid
$routes->post('barang', 'BarangController::create', ['filter' => 'auth']);
$routes->put('barang/(:num)', 'BarangController::update/$1', ['filter' => 'auth']);
$routes->delete('barang/(:num)', 'BarangController::delete/$1', ['filter' => 'auth']);

// ====================================================================
// JALUR AMAN DUMMY UNTUK MEREDAM PREFLIGHT CORS OPTIONS BROWSER
// ====================================================================
$routes->options('auth/login', function() {
    return response()->setStatusCode(200);
});
$routes->options('auth/logout', function() {
    return response()->setStatusCode(200);
});
$routes->options('barang', function() {
    return response()->setStatusCode(200);
});
$routes->options('barang/(:num)', function() {
    return response()->setStatusCode(200);
});
$routes->options('barang/beli', function() {
    return response()->setStatusCode(200); // <--- OPSI AMAN CORS: Untuk rute pembelian baru
});
$routes->options('transaksi', function() {
    return response()->setStatusCode(200); // <--- OPSI AMAN CORS: Untuk rute riwayat transaksi admin
});