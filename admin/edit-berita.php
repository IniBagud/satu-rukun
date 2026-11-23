<?php
require '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../halaman/login.html");
    exit;
}

$id = intval($_GET['id'] ?? 0);
$berita = [
    'judul' => '',
    'ringkasan' => '',
    'detail' => '',
    'tanggal' => date('Y-m-d'),
    'lokasi' => 'RW 001 Garut',
    'gambar' => 'default-berita.jpg',
    'status' => 'Draft'
];

if ($id > 0) {
    $q = mysqli_query($conn, "SELECT * FROM berita WHERE id = $id");
    if ($row = mysqli_fetch_assoc($q)) {
        $berita = $row;
    }
}

$pesan_sukses = "";
$pesan_error = "";

// PROSES SIMPAN BERITA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = mysqli_real_escape_string($conn, trim($_POST['judul']));
    $ringkasan = mysqli_real_escape_string($conn, trim($_POST['ringkasan']));
    $detail    = mysqli_real_escape_string($conn, trim($_POST['detail']));
    $tanggal   = mysqli_real_escape_string($conn, trim($_POST['tanggal']));
    $lokasi    = mysqli_real_escape_string($conn, trim($_POST['lokasi']));
    $status    = mysqli_real_escape_string($conn, $_POST['status']);
    
    $nama_gambar = $berita['gambar'];

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, ['jpg', 'jpeg', 'png'])) {
            $nama_gambar = 'berita_' . time() . '.' . $file_ext;
            move_uploaded_file($file_tmp, '../assets/gambar/' . $nama_gambar);
        }
    }

    if ($id > 0) {
        $sql = "UPDATE berita SET judul='$judul', ringkasan='$ringkasan', detail='$detail', tanggal='$tanggal', lokasi='$lokasi', gambar='$nama_gambar', status='$status' WHERE id=$id";
    } else {
        $sql = "INSERT INTO berita (judul, ringkasan, detail, tanggal, lokasi, gambar, status) VALUES ('$judul', '$ringkasan', '$detail', '$tanggal', '$lokasi', '$nama_gambar', '$status')";
    }

    if (mysqli_query($conn, $sql)) {
        $aksi_log = ($status === 'Dipublikasikan') ? 'Berita Dipublikasikan' : 'Draft Berita Disimpan';
        mysqli_query($conn, "INSERT INTO log_aktivitas (tipe, aksi, detail) VALUES ('berita', '$aksi_log', '$judul')");
        header("Location: berita.php");
        exit;
    } else {
        $pesan_error = "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $id > 0 ? 'Edit Berita' : 'Tambah Berita' ?> - Admin Satu Rukun</title>
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

          <a href="profil-rw.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Profil Ketua RW</span>
          </a>

          <a href="kegiatan.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Kegiatan</span>
          </a>

          <a href="berita.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl bg-emerald-50 text-[#186a5a] font-semibold text-sm">
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
      
      <!-- TOPBAR HEADER -->
      <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
        <div class="text-xs text-gray-400 font-medium flex items-center space-x-2">
          <span>Admin</span>
          <span>&rsaquo;</span>
          <span class="text-gray-700 font-semibold">Berita</span>
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
              <span class="block text-xs font-bold text-gray-800"><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin RW 001') ?></span>
              <span class="block text-[11px] text-gray-400">Pengelola website</span>
            </div>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </div>
        </div>
      </header>

      <main class="p-8 space-y-6 overflow-y-auto max-w-7xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight"><?= $id > 0 ? 'Edit Berita' : 'Tambah Berita' ?></h1>
            <p class="text-xs text-gray-400 mt-1">Perbarui konten untuk ditampilkan pada website publik.</p>
          </div>
          <a href="berita.php" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow-sm">
            Kembali ke Daftar
          </a>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" id="formBerita">
          <input type="hidden" name="status" id="inputStatus" value="<?= htmlspecialchars($berita['status']) ?>" />

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <!-- KOLOM KIRI (2 COL): INFORMASI UTAMA & WAKTU -->
            <div class="lg:col-span-2 space-y-6">
              
              <!-- Card Informasi Utama -->
              <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-5">
                <div>
                  <h3 class="font-bold text-gray-900 text-base">Informasi utama</h3>
                  <p class="text-xs text-gray-400 mt-0.5">Lengkapi informasi konten dengan jelas.</p>
                </div>

                <div>
                  <label class="block text-xs font-bold text-gray-800 mb-2">Judul Berita</label>
                  <input type="text" name="judul" value="<?= htmlspecialchars($berita['judul']) ?>" required placeholder="Masukkan judul berita..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#186a5a] focus:bg-white transition" />
                </div>

                <div>
                  <label class="block text-xs font-bold text-gray-800 mb-2">Ringkasan Berita</label>
                  <textarea name="ringkasan" rows="2" required placeholder="Ringkasan singkat yang tampil di kartu berita..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-800 focus:outline-none focus:border-[#186a5a] focus:bg-white transition leading-relaxed resize-none"><?= htmlspecialchars($berita['ringkasan']) ?></textarea>
                  <span class="text-[11px] text-gray-400 block mt-1">Ringkasan ditampilkan pada kartu berita.</span>
                </div>

                <div>
                  <label class="block text-xs font-bold text-gray-800 mb-2">Detail Berita</label>
                  <textarea name="detail" rows="7" required placeholder="Tulis isi artikel berita secara lengkap..." class="w-full bg-gray-50 border border-gray-200 rounded-xl p-4 text-sm text-gray-800 focus:outline-none focus:border-[#186a5a] focus:bg-white transition leading-relaxed resize-y"><?= htmlspecialchars($berita['detail']) ?></textarea>
                </div>
              </div>

              <!-- Card Waktu dan Lokasi -->
              <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-4">
                <div>
                  <h3 class="font-bold text-gray-900 text-base">Waktu dan lokasi</h3>
                  <p class="text-xs text-gray-400 mt-0.5">Informasi pelaksanaan atau tanggal publikasi.</p>
                </div>

                <!-- GRID 2 KOLOM: TANGGAL DAN LOKASI BERITA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">Tanggal Berita</label>
                    <input type="date" name="tanggal" value="<?= htmlspecialchars($berita['tanggal']) ?>" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#186a5a] focus:bg-white transition" />
                  </div>

                  <div>
                    <label class="block text-xs font-bold text-gray-800 mb-2">Lokasi Berita</label>
                    <input type="text" name="lokasi" value="<?= htmlspecialchars($berita['lokasi'] ?? 'RW 001 Garut') ?>" placeholder="Contoh: Kantor RW 001 Garut" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:outline-none focus:border-[#186a5a] focus:bg-white transition" />
                  </div>
                </div>
              </div>

            </div>

            <!-- KOLOM KANAN (1 COL): FOTO & PUBLIKASI -->
            <div class="space-y-6">
              
              <!-- Card Foto Utama -->
              <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-4">
                <div>
                  <h3 class="font-bold text-gray-900 text-base">Foto utama</h3>
                  <p class="text-xs text-gray-400 mt-0.5">Rasio gambar disarankan 16:9.</p>
                </div>

                <div class="w-full h-44 bg-gray-50 rounded-2xl border border-dashed border-gray-200 overflow-hidden relative">
                  <img id="previewGambar" src="../assets/gambar/<?= htmlspecialchars($berita['gambar']) ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/400x225'" />
                </div>

                <input type="file" id="gambarInput" name="gambar" accept=".jpg,.jpeg,.png" class="hidden" />
                <button type="button" onclick="document.getElementById('gambarInput').click()" class="w-full bg-white border border-gray-300 hover:border-[#186a5a] hover:text-[#186a5a] text-gray-700 font-semibold py-2.5 px-4 rounded-xl text-xs flex items-center justify-center space-x-2 transition cursor-pointer">
                  <span>Ganti Foto</span>
                </button>
              </div>

              <!-- Card Publikasi -->
              <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm space-y-4">
                <h3 class="font-bold text-gray-900 text-base">Publikasi</h3>

                <div class="flex items-center justify-between text-xs py-1 border-b border-gray-50">
                  <span class="text-gray-400">Status saat ini</span>
                  <span class="font-bold text-[#186a5a] bg-emerald-50 px-3 py-1 rounded-full"><?= htmlspecialchars($berita['status']) ?></span>
                </div>

                <div class="space-y-2 pt-2">
                  <button type="button" onclick="submitForm('Draft')" class="w-full bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2.5 px-4 rounded-xl text-xs transition cursor-pointer">
                    Simpan Draft
                  </button>
                  <button type="button" onclick="submitForm('Dipublikasikan')" class="w-full bg-[#186a5a] hover:bg-[#135548] text-white font-bold py-2.5 px-4 rounded-xl text-xs transition cursor-pointer flex items-center justify-center space-x-2">
                    <span>&check;</span> <span>Publikasikan</span>
                  </button>
                </div>
              </div>

            </div>

          </div>
        </form>
      </main>
    </div>

    <script>
      // Live Preview Gambar
      document.getElementById('gambarInput').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            document.getElementById('previewGambar').src = e.target.result;
          }
          reader.readAsDataURL(file);
        }
      });

      // Submit Form berdasarkan Pilihan Status
      function submitForm(status) {
        document.getElementById('inputStatus').value = status;
        document.getElementById('formBerita').submit();
      }
    </script>
  </body>
</html>