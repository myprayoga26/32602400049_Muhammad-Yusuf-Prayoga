<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Web::index');
$routes->get('/web/search', 'Web::search');
$routes->get('/web/search-api', 'Web::searchApi');
$routes->get('/web/migrate_kategori', 'Web::migrateKategori');
$routes->get('/web/migrate_v2', 'Web::migrateV2');
$routes->get('/web/buku/(:num)', 'Web::bookDetail/$1');
$routes->get('/web/read/(:num)', 'Web::readBook/$1');
$routes->get('/web/read-pages/(:num)', 'Web::readPages/$1');
$routes->get('/web/shelf', 'Web::myShelf');
$routes->post('/web/shelf/add', 'Web::addToShelf');
$routes->post('/web/shelf/update/(:num)', 'Web::updateShelf/$1');
$routes->post('/web/submit-review', 'Web::submitReview');
$routes->get('/web/migrate_v3', 'Web::migrateV3');
$routes->get('/web/migrate_v4', 'Web::migrateV4');
$routes->get('/web/seed_gutenberg', 'Web::seed_gutenberg');
$routes->get('/dashboard', 'Home::index');
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/process', 'Auth::process');
$routes->get('/auth/logout', 'Auth::logout');
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/processRegister', 'Auth::processRegister');
$routes->get('/auth/bypass', 'Auth::bypass');
$routes->get('/user/dashboard', 'UserDashboard::index');
$routes->post('/user/update-goal', 'UserDashboard::updateReadingGoal');
$routes->post('/api/bookmark/save', 'UserDashboard::saveBookmark');
$routes->post('/api/bookmark/delete', 'UserDashboard::deleteBookmark');
$routes->get('/api/bookmark/list/(:num)', 'UserDashboard::listBookmarks/$1');

$routes->post('/api/annotation/save', 'UserDashboard::saveAnnotation');
$routes->get('/api/annotation/list/(:num)', 'UserDashboard::listAnnotations/$1');

$routes->post('/api/reading-session', 'UserDashboard::logReadingSession');

// Routes Buku
$routes->get('/buku', 'Buku::index');
$routes->get('/buku/tambah', 'Buku::tambah');
$routes->post('/buku/simpan', 'Buku::simpan');
$routes->get('/buku/edit/(:num)', 'Buku::edit/$1');
$routes->post('/buku/update/(:num)', 'Buku::update/$1');
$routes->get('/buku/hapus/(:num)', 'Buku::hapus/$1');
$routes->get('/buku/migrate_kategori', 'Buku::migrateKategori');

// Routes Anggota
$routes->get('/anggota', 'Anggota::index');
$routes->get('/anggota/tambah', 'Anggota::tambah');
$routes->post('/anggota/simpan', 'Anggota::simpan');
$routes->get('/anggota/edit/(:num)', 'Anggota::edit/$1');
$routes->post('/anggota/update/(:num)', 'Anggota::update/$1');
$routes->get('/anggota/hapus/(:num)', 'Anggota::hapus/$1');

// Routes Peminjaman
$routes->get('/peminjaman', 'Peminjaman::index');
$routes->get('/peminjaman/tambah', 'Peminjaman::tambah');
$routes->post('/peminjaman/simpan', 'Peminjaman::simpan');
$routes->get('/peminjaman/kembali/(:num)', 'Peminjaman::kembali/$1');

// Routes LITERIA Smart Tools
$routes->post('/web/ai_explain', 'Web::ai_explain');
$routes->get('/web/get_popular_annotations/(:num)', 'Web::get_popular_annotations/$1');
