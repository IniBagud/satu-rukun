<?php
require '../koneksi.php';

// Proteksi Akses Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../halaman/login.html");
    exit;
}

// FUNGSI FORMAT WAKTU REALTIME (TIME AGO)
function waktu_realtime($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return 'Baru saja';
    if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
    if ($diff < 172800) return 'Kemarin';
    return date('d M Y', $time);
}

// 1. DATA STATISTIK UTAMA
$q_rumah    = mysqli_query($conn, "SELECT jumlah_rumah FROM statistik WHERE id = 1");
$d_rumah    = mysqli_fetch_assoc($q_rumah)['jumlah_rumah'] ?? 128;

$q_warga    = mysqli_query($conn, "SELECT jumlah_warga FROM statistik WHERE id = 1");
$d_warga    = mysqli_fetch_assoc($q_warga)['jumlah_warga'] ?? 436;

$q_kegiatan = mysqli_query($conn, "SELECT COUNT(*) as total FROM kegiatan");
$d_kegiatan = mysqli_fetch_assoc($q_kegiatan)['total'] ?? 0;

$q_berita   = mysqli_query($conn, "SELECT COUNT(*) as total FROM berita");
$d_berita   = mysqli_fetch_assoc($q_berita)['total'] ?? 0;

// 2. REKAPITULASI KEUANGAN
$q_pemasukan   = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM keuangan WHERE jenis = 'Pemasukan'");
$d_pemasukan   = mysqli_fetch_assoc($q_pemasukan)['total'] ?? 0;

$q_pengeluaran = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM keuangan WHERE jenis = 'Pengeluaran'");
$d_pengeluaran = mysqli_fetch_assoc($q_pengeluaran)['total'] ?? 0;

$saldo_akhir = $d_pemasukan - $d_pengeluaran;

// 3. AKTIVITAS TERBARU (Mengambil dari tabel log_aktivitas secara Realtime)
$q_aktivitas = mysqli_query($conn, "SELECT * FROM log_aktivitas ORDER BY created_at DESC, id DESC LIMIT 4");

// 4. KONTEN BARU DIPERBARUI (Menggabungkan Berita & Kegiatan berdasarkan ID/Waktu Input Terbaru)
$q_konten_baru = mysqli_query($conn, "
    (SELECT 'Berita' AS kategori, id, judul, tanggal, gambar, status FROM berita WHERE status = 'Dipublikasikan')
    UNION ALL
    (SELECT 'Kegiatan' AS kategori, id, judul, tanggal, gambar, status FROM kegiatan WHERE status = 'Dipublikasikan')
    ORDER BY id DESC LIMIT 3
");
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin - Satu Rukun</title>
    <link rel="stylesheet" href="../src/output.css" />
  </head>
  <body class="bg-[#f7f6f1] text-gray-800 font-sans flex h-screen overflow-hidden">
    
    <!-- SIDEBAR FIXED -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between shrink-0 h-screen sticky top-0">
      <div>
        <div class="p-6 flex items-center space-x-3 border-b border-gray-100">
          <div class="w-10 h-10 bg-[#186a5a] text-white rounded-xl flex items-center justify-center font-bold text-sm">
            SR
          </div>
          <div>
            <h2 class="font-bold text-gray-900 text-sm leading-tight">Satu Rukun</h2>
            <p class="text-xs text-gray-400">RW 001 · Garut</p>
          </div>
        </div>

        <div class="p-4 space-y-1">
          <span class="px-3 text-[11px] font-bold text-gray-400 tracking-wider uppercase">Menu Utama</span>

          <a href="dashboard.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl bg-emerald-50 text-[#186a5a] font-semibold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span>Dashboard</span>
          </a>

          <a href="#" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
              <span>Notifikasi</span>
            </div>
            <span class="bg-[#186a5a] text-white text-[10px] font-bold px-2 py-0.5 rounded-full">2</span>
          </a>

          <a href="data-desa.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Data Desa</span>
          </a>

          <a href="profil-rw.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Profil Ketua RW</span>
          </a>

          <a href="kegiatan.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Kegiatan</span>
          </a>

          <a href="berita.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <span>Berita</span>
          </a>

          <a href="keuangan.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>Keuangan</span>
          </a>

          <a href="#" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span>Pengaturan</span>
          </a>
        </div>
      </div>

      <div class="p-4 border-t border-gray-100">
        <a href="../logout.php" class="flex items-center space-x-3 px-3 py-2 text-gray-600 hover:text-red-600 text-sm font-medium transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          <span>Keluar</span>
        </a>
        <p class="text-[11px] text-gray-400 px-3 mt-3">Admin Panel v1.0</p>
      </div>
    </aside>

    <!-- MAIN CONTENT CONTAINER -->
    <div class="grow flex flex-col min-w-0 h-screen">
      
      <!-- TOPBAR -->
      <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between shrink-0">
        <div class="text-xs text-gray-400 font-medium flex items-center space-x-2">
          <span>Admin</span>
          <span>&rsaquo;</span>
          <span class="text-gray-700 font-semibold">Dashboard</span>
        </div>

        <div class="flex items-center space-x-4">
          <button class="relative p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
          </button>

          <div class="h-6 w-px bg-gray-200"></div>

          <div class="flex items-center space-x-3 cursor-pointer">
            <div class="w-9 h-9 bg-emerald-100 text-[#186a5a] rounded-full flex items-center justify-center font-bold text-xs">
              AD
            </div>
            <div class="text-left leading-tight hidden sm:block">
              <span class="block text-xs font-bold text-gray-800"><?= htmlspecialchars($_SESSION['nama'] ?? 'Administrator') ?></span>
              <span class="block text-[11px] text-gray-400">Pengelola website</span>
            </div>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </div>
        </div>
      </header>

      <!-- CONTENT BODY -->
      <main class="p-8 space-y-8 overflow-y-auto grow">
        
        <!-- Header Title + Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Dashboard Admin</h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">Kelola informasi dan konten website Satu Rukun RW 001 Garut.</p>
          </div>
          <div class="flex items-center space-x-3">
            <a href="kegiatan.php" class="bg-white border border-[#186a5a] text-[#186a5a] hover:bg-emerald-50 text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center space-x-2">
              <span>+</span> <span>Tambah Kegiatan</span>
            </a>
            <a href="edit-berita.php" class="bg-[#186a5a] hover:bg-[#135548] text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center space-x-2">
              <span>+</span> <span>Tambah Berita</span>
            </a>
          </div>
        </div>

        <!-- 4 STATISTIC CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 bg-emerald-50 text-[#186a5a] rounded-xl flex items-center justify-center shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <div>
              <span class="block text-xs font-medium text-gray-400">Total Rumah</span>
              <span class="block text-2xl font-extrabold text-gray-900 leading-tight"><?= number_format($d_rumah) ?></span>
              <span class="block text-[11px] text-gray-400 mt-0.5">Rumah terdata</span>
            </div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 bg-emerald-50 text-[#186a5a] rounded-xl flex items-center justify-center shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
              <span class="block text-xs font-medium text-gray-400">Total Warga</span>
              <span class="block text-2xl font-extrabold text-gray-900 leading-tight"><?= number_format($d_warga) ?></span>
              <span class="block text-[11px] text-gray-400 mt-0.5">Warga terdaftar</span>
            </div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 bg-emerald-50 text-[#186a5a] rounded-xl flex items-center justify-center shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
              <span class="block text-xs font-medium text-gray-400">Total Kegiatan</span>
              <span class="block text-2xl font-extrabold text-gray-900 leading-tight"><?= number_format($d_kegiatan) ?></span>
              <span class="block text-[11px] text-gray-400 mt-0.5">Kegiatan terdaftar</span>
            </div>
          </div>

          <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 bg-emerald-50 text-[#186a5a] rounded-xl flex items-center justify-center shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <div>
              <span class="block text-xs font-medium text-gray-400">Total Berita</span>
              <span class="block text-2xl font-extrabold text-gray-900 leading-tight"><?= number_format($d_berita) ?></span>
              <span class="block text-[11px] text-gray-400 mt-0.5">Berita tersimpan</span>
            </div>
          </div>
        </div>

        <!-- ROW 2: AKTIVITAS TERBARU & RINGKASAN KEUANGAN -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- AKTIVITAS TERBARU (REALTIME SELLER LOG) -->
          <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="font-bold text-gray-900 text-base">Aktivitas terbaru</h3>
                <p class="text-xs text-gray-400">Pembaruan terkini di panel admin</p>
              </div>
            </div>

            <div class="divide-y divide-gray-50 text-xs">
              <?php if (mysqli_num_rows($q_aktivitas) > 0): ?>
                <?php while ($act = mysqli_fetch_assoc($q_aktivitas)): ?>
                  <div class="py-3.5 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                      <div class="w-9 h-9 bg-emerald-50 text-[#186a5a] rounded-xl flex items-center justify-center shrink-0">
                        <?php if ($act['tipe'] === 'berita'): ?>
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <?php elseif ($act['tipe'] === 'kegiatan'): ?>
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <?php elseif ($act['tipe'] === 'data_desa' || $act['tipe'] === 'profil_rw'): ?>
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <?php else: ?>
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <?php endif; ?>
                      </div>
                      <div>
                        <h4 class="font-bold text-gray-800"><?= htmlspecialchars($act['aksi']) ?></h4>
                        <p class="text-gray-400 text-[11px] truncate max-w-xs"><?= htmlspecialchars($act['detail']) ?></p>
                      </div>
                    </div>
                    <span class="text-gray-400 text-[11px] shrink-0"><?= waktu_realtime($act['created_at']) ?></span>
                  </div>
                <?php endwhile; ?>
              <?php else: ?>
                <div class="py-4 text-center text-gray-400">Belum ada aktivitas tercatat.</div>
              <?php endif; ?>
            </div>
          </div>

          <!-- RINGKASAN KEUANGAN -->
          <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h3 class="font-bold text-gray-900 text-base">Ringkasan keuangan</h3>
                <p class="text-xs text-gray-400">Periode <?= date('F Y') ?></p>
              </div>
              <a href="keuangan.php" class="text-xs font-semibold text-[#186a5a] hover:underline flex items-center space-x-1">
                <span>Detail</span> <span>&rsaquo;</span>
              </a>
            </div>

            <div class="bg-[#186a5a] text-white p-5 rounded-2xl relative overflow-hidden shadow-sm">
              <span class="text-[11px] text-emerald-100/80 block">Saldo saat ini</span>
              <span class="text-2xl font-extrabold tracking-tight block mt-1">
                Rp <?= number_format($saldo_akhir, 0, ',', '.') ?>
              </span>
              <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-sm pointer-events-none"></div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-1">
              <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 bg-emerald-50 text-[#186a5a] rounded-xl flex items-center justify-center shrink-0">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                </div>
                <div>
                  <span class="block text-[10px] text-gray-400">Pemasukan</span>
                  <span class="block text-xs font-bold text-gray-800">Rp <?= number_format($d_pemasukan, 0, ',', '.') ?></span>
                </div>
              </div>

              <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 bg-red-50 text-red-500 rounded-xl flex items-center justify-center shrink-0">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
                <div>
                  <span class="block text-[10px] text-gray-400">Pengeluaran</span>
                  <span class="block text-xs font-bold text-gray-800">Rp <?= number_format($d_pengeluaran, 0, ',', '.') ?></span>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- ROW 3: KONTEN BARU DIPERBARUI & PINTASAN -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          
          <!-- KONTEN BARU DIPERBARUI (BERITA & KEGIATAN DIPUBLIKASIKAN) -->
          <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-4">
            <div>
              <h3 class="font-bold text-gray-900 text-base">Konten baru diperbarui</h3>
              <p class="text-xs text-gray-400">Berita dan Kegiatan yang telah dipublikasikan</p>
            </div>

            <div class="space-y-3">
              <?php if (mysqli_num_rows($q_konten_baru) > 0): ?>
                <?php while ($kntn = mysqli_fetch_assoc($q_konten_baru)): ?>
                  <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:bg-gray-50 transition">
                    <div class="flex items-center space-x-3.5">
                      <img 
                        src="../assets/gambar/<?= htmlspecialchars($kntn['gambar']) ?>" 
                        class="w-12 h-12 rounded-lg object-cover shrink-0" 
                        onerror="this.src='https://via.placeholder.com/100'" 
                      />
                      <div>
                        <h4 class="font-bold text-gray-800 text-xs"><?= htmlspecialchars($kntn['judul']) ?></h4>
                        <p class="text-[11px] text-gray-400"><?= $kntn['kategori'] ?> · <?= date('d M Y', strtotime($kntn['tanggal'])) ?></p>
                      </div>
                    </div>
                    <span class="bg-emerald-50 text-[#186a5a] text-[10px] font-bold px-3 py-1 rounded-full shrink-0">
                      Dipublikasikan
                    </span>
                  </div>
                <?php endwhile; ?>
              <?php else: ?>
                <div class="p-4 text-center text-gray-400 text-xs">Belum ada konten yang dipublikasikan.</div>
              <?php endif; ?>
            </div>
          </div>

          <!-- PINTASAN -->
          <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between space-y-4">
            <div>
              <span class="text-[10px] font-bold text-gray-400 tracking-wider uppercase block mb-1">Pintasan</span>
              <h3 class="font-bold text-gray-900 text-base leading-snug">
                Perbarui informasi warga dengan mudah
              </h3>
              <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                Gunakan menu pengelolaan untuk menjaga informasi website tetap relevan.
              </p>
            </div>

            <div class="space-y-2.5 pt-2">
              <a href="data-desa.php" class="p-3.5 bg-gray-50 rounded-xl flex items-center justify-between hover:bg-emerald-50 group transition">
                <div class="flex items-center space-x-3">
                  <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-gray-600 group-hover:text-[#186a5a] shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                  </div>
                  <div>
                    <h4 class="font-bold text-gray-800 text-xs">Perbarui Data Desa</h4>
                    <p class="text-[10px] text-gray-400">Rumah, warga, dan ketua RW</p>
                  </div>
                </div>
                <span class="text-gray-400 group-hover:text-[#186a5a] font-bold">&rsaquo;</span>
              </a>

              <a href="profil-rw.php" class="p-3.5 bg-gray-50 rounded-xl flex items-center justify-between hover:bg-emerald-50 group transition">
                <div class="flex items-center space-x-3">
                  <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-gray-600 group-hover:text-[#186a5a] shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  </div>
                  <div>
                    <h4 class="font-bold text-gray-800 text-xs">Profil Ketua RW</h4>
                    <p class="text-[10px] text-gray-400">Foto dan kata sambutan</p>
                  </div>
                </div>
                <span class="text-gray-400 group-hover:text-[#186a5a] font-bold">&rsaquo;</span>
              </a>
            </div>
          </div>

        </div>

      </main>
    </div>

  </body>
</html>