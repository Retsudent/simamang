<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default routes
$routes->get('/', 'Home::index');

// Auth routes
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::loginProcess');
$routes->get('logout', 'Auth::logout');
// Register routes
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::registerProcess');

// Siswa routes
$routes->group('siswa', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Siswa::dashboard');
    $routes->get('input-log', 'Siswa::inputLog');
    $routes->post('save-log', 'Siswa::saveLog');
    $routes->get('riwayat', 'Siswa::riwayat');
    $routes->get('detail-log/(:num)', 'Siswa::detailLog/$1');
    $routes->get('laporan', 'Siswa::laporan');
    $routes->post('generate-laporan', 'Siswa::generateLaporan');
});

// Pembimbing routes
$routes->group('pembimbing', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Pembimbing::dashboard');
    $routes->get('aktivitas-siswa', 'Pembimbing::aktivitasSiswa');
    $routes->get('log-siswa/(:num)', 'Pembimbing::logSiswa/$1');
    $routes->get('detail-log/(:num)', 'Pembimbing::detailLog/$1');
    $routes->post('beri-komentar', 'Pembimbing::beriKomentar');
    $routes->post('validasi-log', 'Pembimbing::validasiLog');
});

// Admin routes
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    
    // Kelola Siswa
    $routes->get('kelola-siswa', 'Admin::kelolaSiswa');
    $routes->get('tambah-siswa', 'Admin::tambahSiswa');
    $routes->post('simpan-siswa', 'Admin::simpanSiswa');
    $routes->get('edit-siswa/(:num)', 'Admin::editSiswa/$1');
    $routes->post('update-siswa/(:num)', 'Admin::updateSiswa/$1');
    $routes->get('hapus-siswa/(:num)', 'Admin::hapusSiswa/$1');
    
    // Kelola Pembimbing
    $routes->get('kelola-pembimbing', 'Admin::kelolaPembimbing');
    $routes->get('tambah-pembimbing', 'Admin::tambahPembimbing');
    $routes->post('simpan-pembimbing', 'Admin::simpanPembimbing');
    // Atur bimbingan siswa untuk pembimbing
    $routes->get('atur-bimbingan/(:num)', 'Admin::aturBimbingan/$1');
    $routes->post('simpan-atur-bimbingan/(:num)', 'Admin::simpanAturBimbingan/$1');
    
    // Laporan
    $routes->get('laporan-magang', 'Admin::laporanMagang');
    $routes->post('generate-laporan-admin', 'Admin::generateLaporanAdmin');
});

