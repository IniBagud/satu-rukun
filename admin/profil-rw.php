<?php
require '../koneksi.php';

// Proteksi Akses Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../halaman/login.html");
    exit;
}

$pesan_sukses = "";
$pesan_error  = "";

// Ambil Data Profil RW Saat Ini
$q_data = mysqli_query($conn, "SELECT * FROM profil_rw WHERE id = 1");
$data   = mysqli_fetch_assoc($q_data);

// Simpan Perubahan Profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_profil'])) {
    $nama         = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $masa_jabatan = mysqli_real_escape_string($conn, trim($_POST['masa_jabatan']));
    $sambutan     = mysqli_real_escape_string($conn, trim($_POST['sambutan']));
    
    $nama_foto = $data['foto']; // Gunakan foto lama sebagai default

    // Proses Upload Foto Baru (jika ada file diunggah)
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['foto_profil']['tmp_name'];
        $file_name = $_FILES['foto_profil']['name'];
        $file_size = $_FILES['foto_profil']['size'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png'];

        if (!in_array($file_ext, $ekstensi_diperbolehkan)) {
            $pesan_error = "Format foto harus JPG, JPEG, atau PNG!";
        } elseif ($file_size > 2 * 1024 * 1024) { // Maksimal 2MB
            $pesan_error = "Ukuran foto maksimal 2 MB!";
        } else {
            // Beri nama unik file foto
            $nama_foto = 'ketua_rw_' . time() . '.' . $file_ext;
            $target_dir = '../assets/gambar/' . $nama_foto;

            if (!move_uploaded_file($file_tmp, $target_dir)) {
                $pesan_error = "Gagal mengunggah foto ke folder assets/gambar/";
            }
        }
    }

    if (empty($pesan_error)) {
        // Update data ke database & tabel statistik
        $q_update = "UPDATE profil_rw SET 
                        nama = '$nama', 
                        masa_jabatan = '$masa_jabatan', 
                        sambutan = '$sambutan', 
                        foto = '$nama_foto' 
                     WHERE id = 1";
                     
        // Sinkronkan nama ke tabel statistik
        mysqli_query($conn, "UPDATE statistik SET nama_ketua_rw = '$nama' WHERE id = 1");

        if (mysqli_query($conn, $q_update)) {
            $pesan_sukses = "Profil Ketua RW berhasil diperbarui!";
            // Refresh data terbaru
            $q_data = mysqli_query($conn, "SELECT * FROM profil_rw WHERE id = 1");
            $data   = mysqli_fetch_assoc($q_data);
        } else {
            $pesan_error = "Gagal memperbarui database: " . mysqli_error($conn);
        }
    }
}
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil Ketua RW - Admin Satu Rukun</title>
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

          <a href="data-desa.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <span>Data Desa</span>
          </a>

          <a href="profil-rw.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl bg-emerald-50 text-[#186a5a] font-semibold text-sm">
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
          <span class="text-gray-700 font-semibold">Profil Ketua RW</span>
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
        
        <!-- Header & Button Pratinjau -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Profil Ketua RW</h1>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">Kelola profil dan sambutan Ketua RW 001 untuk website publik.</p>
          </div>
          <a 
            href="../halaman/profil-rw.html" 
            target="_blank" 
            class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-sm flex items-center space-x-2 self-start sm:self-auto"
          >
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <span>Pratinjau</span>
          </a>
        </div>

        <!-- Alert Notifikasi -->
        <?php if (!empty($pesan_sukses)): ?>
          <div class="p-4 bg-emerald-100 border border-emerald-200 text-[#186a5a] rounded-2xl text-xs font-semibold">
            <?= $pesan_sukses ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($pesan_error)): ?>
          <div class="p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl text-xs font-semibold">
            <?= $pesan_error ?>
          </div>
        <?php endif; ?>

        <!-- FORM UTAMA -->
        <form action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="simpan_profil" value="1" />

          <!-- GRID 2 CARDS -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- KARTU KIRI: FOTO PROFIL (1 COL) -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-6 flex flex-col justify-between h-full">
              <div>
                <h3 class="font-bold text-gray-900 text-base">Foto profil</h3>
                <p class="text-xs text-gray-400 mt-1 leading-relaxed">Gunakan foto formal dengan pencahayaan baik.</p>
              </div>

              <!-- Container Preview Foto -->
              <div class="w-full h-72 bg-gray-50 rounded-2xl border border-dashed border-gray-200 overflow-hidden flex items-center justify-center relative group">
                <img 
                  id="previewFoto" 
                  src="../assets/gambar/<?= htmlspecialchars($data['foto'] ?? 'default-rw.jpg') ?>" 
                  alt="Foto Ketua RW"
                  class="w-full h-full object-cover object-top"
                  onerror="this.src='https://via.placeholder.com/300x400?text=Foto+Profil'"
                />
              </div>

              <!-- Input File Hidden + Button Triggger -->
              <div class="space-y-2">
                <input 
                  type="file" 
                  id="fotoInput" 
                  name="foto_profil" 
                  accept=".jpg,.jpeg,.png"
                  class="hidden" 
                />
                
                <button 
                  type="button" 
                  onclick="document.getElementById('fotoInput').click()" 
                  class="w-full bg-white border border-gray-300 hover:border-[#186a5a] hover:text-[#186a5a] text-gray-700 font-semibold py-3 px-4 rounded-xl text-xs flex items-center justify-center space-x-2 transition shadow-sm cursor-pointer"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                  <span>Ganti Foto</span>
                </button>

                <p class="text-[11px] text-gray-400 text-center">JPG atau PNG, maksimal 2 MB.</p>
              </div>
            </div>

            <!-- KARTU KANAN: INFORMASI PROFIL (2 COL) -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-6">
              <div>
                <h3 class="font-bold text-gray-900 text-base">Informasi profil</h3>
                <p class="text-xs text-gray-400 mt-1">Data ini akan ditampilkan pada halaman profil Ketua RW.</p>
              </div>

              <div class="space-y-5">
                <!-- Grid Nama & Masa Jabatan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">Nama Lengkap</label>
                    <input 
                      type="text" 
                      name="nama" 
                      value="<?= htmlspecialchars($data['nama'] ?? '') ?>" 
                      required 
                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#186a5a] focus:bg-white transition"
                    />
                  </div>

                  <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">Masa Jabatan</label>
                    <input 
                      type="text" 
                      name="masa_jabatan" 
                      value="<?= htmlspecialchars($data['masa_jabatan'] ?? '') ?>" 
                      placeholder="Contoh: 2023 – 2028"
                      required 
                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#186a5a] focus:bg-white transition"
                    />
                  </div>
                </div>

                <!-- Textarea Sambutan + Counter -->
                <div>
                  <label class="block text-xs font-bold text-gray-800 mb-2">Kata Sambutan atau Inspirasi</label>
                  <textarea 
                    id="textareaSambutan"
                    name="sambutan" 
                    rows="5" 
                    maxlength="300"
                    required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-800 focus:outline-none focus:border-[#186a5a] focus:bg-white transition leading-relaxed resize-none"
                  ><?= htmlspecialchars($data['sambutan'] ?? '') ?></textarea>
                  
                  <div class="flex justify-start mt-1.5">
                    <span id="charCounter" class="text-[11px] text-gray-400">0/300 karakter</span>
                  </div>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="pt-4 border-t border-gray-50">
                <button 
                  type="submit" 
                  class="bg-[#186a5a] hover:bg-[#135548] text-white text-xs font-semibold px-6 py-3 rounded-xl transition shadow-sm cursor-pointer"
                >
                  Simpan Profil
                </button>
              </div>

            </div>

          </div>
        </form>

      </main>
    </div>

    <!-- SCRIPT PREVIEW FOTO & COUNTER KARAKTER -->
    <script>
      // 1. Script Live Preview Foto saat di-select
      const fotoInput = document.getElementById('fotoInput');
      const previewFoto = document.getElementById('previewFoto');

      fotoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            previewFoto.src = e.target.result;
          }
          reader.readAsDataURL(file);
        }
      });

      // 2. Script Hitung Karakter Sambutan
      const textareaSambutan = document.getElementById('textareaSambutan');
      const charCounter = document.getElementById('charCounter');

      function updateCounter() {
        const currentLength = textareaSambutan.value.length;
        charCounter.textContent = `${currentLength}/300 karakter`;
      }

      textareaSambutan.addEventListener('input', updateCounter);
      // Jalankan hitung karakter awal saat halaman pertama dimuat
      updateCounter();
    </script>

  </body>
</html>