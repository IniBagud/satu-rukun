<?php
require '../koneksi.php';

// Proteksi Akses Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../halaman/login.html");
    exit;
}

$pesan_sukses = "";

// Proses Update Form Data Kependudukan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_kependudukan'])) {
    $jumlah_rumah = intval($_POST['jumlah_rumah']);
    $jumlah_warga = intval($_POST['jumlah_warga']);

    $q_update = "UPDATE statistik SET jumlah_rumah = '$jumlah_rumah', jumlah_warga = '$jumlah_warga' WHERE id = 1";
    if (mysqli_query($conn, $q_update)) {
        mysqli_query($conn, "INSERT INTO log_aktivitas (tipe, aksi, detail) VALUES ('data_desa', 'Data Desa Diperbarui', 'Total warga $jumlah_warga, rumah $jumlah_rumah')");
        $pesan_sukses = "Data kependudukan berhasil diperbarui!";
    }
}

// Proses Update Form Ketua RW
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_ketua_rw'])) {
    $nama_ketua_rw = mysqli_real_escape_string($conn, trim($_POST['nama_ketua_rw']));

    $q_update = "UPDATE statistik SET nama_ketua_rw = '$nama_ketua_rw' WHERE id = 1";
    if (mysqli_query($conn, $q_update)) {
        mysqli_query($conn, "INSERT INTO log_aktivitas (tipe, aksi, detail) VALUES ('data_desa', 'Nama Ketua RW Diperbarui', 'Ketua RW: $nama_ketua_rw')");
        $pesan_sukses = "Data Ketua RW berhasil diperbarui!";
    }
}

// Ambil Data Statistik Saat Ini
$q_data = mysqli_query($conn, "SELECT * FROM statistik WHERE id = 1");
$data   = mysqli_fetch_assoc($q_data);

// Format Tanggal Terakhir Diperbarui
$tgl_update = isset($data['updated_at']) ? date('d F Y', strtotime($data['updated_at'])) : date('d F Y');
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Desa - Admin Satu Rukun</title>
    <link rel="stylesheet" href="../src/output.css" />
  </head>
  <body class="bg-[#f7f6f1] text-gray-800 font-sans flex min-h-screen">
    
    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between shrink-0">
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

          <a href="dashboard.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
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

          <a href="data-desa.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl bg-emerald-50 text-[#186a5a] font-semibold text-sm">
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

    <!-- MAIN CONTENT -->
    <div class="grow flex flex-col min-w-0">
      
      <!-- TOPBAR -->
      <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
        <div class="text-xs text-gray-400 font-medium flex items-center space-x-2">
          <span>Admin</span>
          <span>&rsaquo;</span>
          <span class="text-gray-700 font-semibold">Data Desa</span>
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
      <main class="p-8 space-y-6 overflow-y-auto max-w-7xl">
        
        <div>
          <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Data Desa</h1>
          <p class="text-xs sm:text-sm text-gray-400 mt-1">Perbarui data utama yang ditampilkan pada website publik.</p>
        </div>

        <?php if (!empty($pesan_sukses)): ?>
          <div class="p-4 bg-emerald-100 border border-emerald-200 text-[#186a5a] rounded-2xl text-xs font-semibold">
            <?= $pesan_sukses ?>
          </div>
        <?php endif; ?>

        <!-- GRID 2 CARDS -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
          
          <!-- KARTU 1: DATA KEPENDUDUKAN -->
          <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-start justify-between">
              <div>
                <h3 class="font-bold text-gray-900 text-base">Data kependudukan</h3>
                <p class="text-xs text-gray-400 mt-0.5">Ringkasan jumlah rumah dan warga RW 001.</p>
              </div>
              <button 
                type="button" 
                id="btnEditKependudukan"
                class="px-3.5 py-1.5 bg-emerald-50 text-[#186a5a] text-xs font-semibold rounded-xl flex items-center space-x-1.5 hover:bg-emerald-100 transition cursor-pointer"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>Edit</span>
              </button>
            </div>

            <form action="" method="POST" class="space-y-6">
              <input type="hidden" name="update_kependudukan" value="1" />
              
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs font-bold text-gray-800 mb-2">Jumlah Rumah</label>
                  <input 
                    type="number" 
                    id="inputRumah"
                    name="jumlah_rumah" 
                    value="<?= htmlspecialchars($data['jumlah_rumah'] ?? 128) ?>" 
                    disabled
                    required 
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#186a5a] disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed transition"
                  />
                </div>

                <div>
                  <label class="block text-xs font-bold text-gray-800 mb-2">Jumlah Warga</label>
                  <input 
                    type="number" 
                    id="inputWarga"
                    name="jumlah_warga" 
                    value="<?= htmlspecialchars($data['jumlah_warga'] ?? 436) ?>" 
                    disabled
                    required 
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#186a5a] disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed transition"
                  />
                </div>
              </div>

              <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                <span class="text-[11px] text-gray-400">Terakhir diperbarui <?= $tgl_update ?></span>
                <button 
                  type="submit" 
                  id="btnSubmitKependudukan"
                  disabled
                  class="bg-[#80b1a6] opacity-50 cursor-not-allowed text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition shadow-sm"
                >
                  Simpan Perubahan
                </button>
              </div>
            </form>
          </div>

          <!-- KARTU 2: KETUA RW -->
          <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-6">
            <div class="flex items-start justify-between">
              <div>
                <h3 class="font-bold text-gray-900 text-base">Ketua RW</h3>
                <p class="text-xs text-gray-400 mt-0.5">Nama ketua RW aktif yang tampil pada website.</p>
              </div>
              <button 
                type="button" 
                id="btnEditKetuaRW"
                class="px-3.5 py-1.5 bg-emerald-50 text-[#186a5a] text-xs font-semibold rounded-xl flex items-center space-x-1.5 hover:bg-emerald-100 transition cursor-pointer"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>Edit</span>
              </button>
            </div>

            <form action="" method="POST" class="space-y-6">
              <input type="hidden" name="update_ketua_rw" value="1" />

              <div>
                <label class="block text-xs font-bold text-gray-800 mb-2">Nama Lengkap Ketua RW</label>
                <input 
                  type="text" 
                  id="inputKetuaRW"
                  name="nama_ketua_rw" 
                  value="<?= htmlspecialchars($data['nama_ketua_rw'] ?? 'dr. Hj. Farih') ?>" 
                  disabled
                  required 
                  class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#186a5a] disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed transition"
                />
              </div>

              <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                <span class="text-[11px] text-gray-400">Pastikan penulisan nama dan gelar sudah benar.</span>
                <button 
                  type="submit" 
                  id="btnSubmitKetuaRW"
                  disabled
                  class="bg-[#80b1a6] opacity-50 cursor-not-allowed text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition shadow-sm"
                >
                  Simpan Perubahan
                </button>
              </div>
            </form>
          </div>

        </div>

      </main>
    </div>

    <!-- SCRIPT AKTIVASI TOMBOL EDIT -->
    <script>
      // Function Universal untuk Aktifkan Form
      function activateForm(inputIds, submitBtnId, editBtn) {
        inputIds.forEach(id => {
          const input = document.getElementById(id);
          if (input) {
            input.removeAttribute('disabled');
            input.classList.remove('disabled:bg-gray-100', 'disabled:text-gray-400', 'disabled:cursor-not-allowed');
            input.classList.add('bg-white');
          }
        });

        const submitBtn = document.getElementById(submitBtnId);
        if (submitBtn) {
          submitBtn.removeAttribute('disabled');
          submitBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-[#80b1a6]');
          submitBtn.classList.add('bg-[#186a5a]', 'hover:bg-[#135548]', 'cursor-pointer');
        }

        // Ubah tampilan tombol edit menjadi aktif / indikator
        if (editBtn) {
          editBtn.classList.remove('bg-emerald-50', 'text-[#186a5a]');
          editBtn.classList.add('bg-[#186a5a]', 'text-white');
        }

        // Focus ke input pertama
        const firstInput = document.getElementById(inputIds[0]);
        if (firstInput) firstInput.focus();
      }

      // Event Listener Kartu 1 (Kependudukan)
      document.getElementById('btnEditKependudukan').addEventListener('click', function() {
        activateForm(['inputRumah', 'inputWarga'], 'btnSubmitKependudukan', this);
      });

      // Event Listener Kartu 2 (Ketua RW)
      document.getElementById('btnEditKetuaRW').addEventListener('click', function() {
        activateForm(['inputKetuaRW'], 'btnSubmitKetuaRW', this);
      });
    </script>

  </body>
</html>