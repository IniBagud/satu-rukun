<?php
require '../koneksi.php';

// Proteksi Akses Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../halaman/login.html");
    exit;
}

$pesan_sukses = "";
$pesan_error  = "";

// PROSES HAPUS KEGIATAN
if (isset($_GET['hapus'])) {
    $id_hapus = intval($_GET['hapus']);
    if (mysqli_query($conn, "DELETE FROM kegiatan WHERE id = $id_hapus")) {
        $pesan_sukses = "Kegiatan berhasil dihapus!";
    }
}

// PROSES SIMPAN / UPDATE KEGIATAN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_kegiatan'])) {
    $id        = intval($_POST['id'] ?? 0);
    $judul     = mysqli_real_escape_string($conn, trim($_POST['judul']));
    $tanggal   = mysqli_real_escape_string($conn, trim($_POST['tanggal']));
    $tempat    = mysqli_real_escape_string($conn, trim($_POST['tempat']));
    $deskripsi = mysqli_real_escape_string($conn, trim($_POST['deskripsi']));
    $status    = mysqli_real_escape_string($conn, $_POST['status']);

    $nama_gambar = $_POST['gambar_lama'] ?? 'default-kegiatan.jpg';

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, ['jpg', 'jpeg', 'png'])) {
            $nama_gambar = 'kegiatan_' . time() . '.' . $file_ext;
            move_uploaded_file($file_tmp, '../assets/gambar/' . $nama_gambar);
        }
    }

    if ($id > 0) {
        $q = "UPDATE kegiatan SET judul='$judul', tanggal='$tanggal', tempat='$tempat', deskripsi='$deskripsi', gambar='$nama_gambar', status='$status' WHERE id=$id";
    } else {
        $q = "INSERT INTO kegiatan (judul, tanggal, tempat, deskripsi, gambar, status) VALUES ('$judul', '$tanggal', '$tempat', '$deskripsi', '$nama_gambar', '$status')";
    }

    if (mysqli_query($conn, $q)) {
        $pesan_sukses = "Data kegiatan berhasil disimpan!";
    } else {
        $pesan_error = "Gagal menyimpan data: " . mysqli_error($conn);
    }
}

// AMBIL DAFTAR KEGIATAN
$search = mysqli_real_escape_string($conn, $_GET['search'] ?? '');
$query_where = !empty($search) ? "WHERE judul LIKE '%$search%' OR tempat LIKE '%$search%'" : "";
$q_kegiatan = mysqli_query($conn, "SELECT * FROM kegiatan $query_where ORDER BY tanggal DESC");
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Kegiatan - Admin Satu Rukun</title>
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

          <a href="kegiatan.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl bg-emerald-50 text-[#186a5a] font-semibold text-sm">
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
      <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
        <div class="text-xs text-gray-400 font-medium flex items-center space-x-2">
          <span>Admin</span>
          <span>&rsaquo;</span>
          <span class="text-gray-700 font-semibold">Kegiatan</span>
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
          </div>
        </div>
      </header>

      <main class="p-8 space-y-6 overflow-y-auto max-w-7xl">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Kegiatan</h1>
            <p class="text-xs text-gray-400 mt-1">Kelola agenda dan kegiatan warga yang tampil di website.</p>
          </div>
          <button onclick="openFormModal()" class="bg-[#186a5a] hover:bg-[#135548] text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition flex items-center space-x-2 cursor-pointer shadow-sm">
            <span>+ Tambah Kegiatan</span>
          </button>
        </div>

        <?php if ($pesan_sukses): ?>
          <div class="p-4 bg-emerald-100 border border-emerald-200 text-[#186a5a] rounded-2xl text-xs font-semibold"><?= $pesan_sukses ?></div>
        <?php endif; ?>

        <!-- TABLE CONTAINER -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-5 border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
              <h3 class="font-bold text-gray-900 text-sm">Daftar Kegiatan</h3>
              <p class="text-xs text-gray-400"><?= mysqli_num_rows($q_kegiatan) ?> konten tersimpan</p>
            </div>
            <form method="GET" class="w-full sm:w-64">
              <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari konten..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs text-gray-800 focus:outline-none focus:border-[#186a5a]" />
            </form>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-gray-50 text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100">
                <tr>
                  <th class="p-4">Kegiatan</th>
                  <th class="p-4">Tanggal</th>
                  <th class="p-4">Tempat</th>
                  <th class="p-4">Status</th>
                  <th class="p-4 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <?php while ($row = mysqli_fetch_assoc($q_kegiatan)): ?>
                  <tr class="hover:bg-gray-50/50 transition">
                    <td class="p-4 flex items-center space-x-3">
                      <img src="../assets/gambar/<?= htmlspecialchars($row['gambar']) ?>" class="w-10 h-10 rounded-lg object-cover" onerror="this.src='https://via.placeholder.com/100'" />
                      <span class="font-bold text-gray-800"><?= htmlspecialchars($row['judul']) ?></span>
                    </td>
                    <td class="p-4 text-gray-500"><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                    <td class="p-4 text-gray-500"><?= htmlspecialchars($row['tempat']) ?></td>
                    <td class="p-4">
                      <?php if ($row['status'] === 'Dipublikasikan'): ?>
                        <span class="bg-emerald-50 text-[#186a5a] text-[10px] font-bold px-2.5 py-1 rounded-full">Dipublikasikan</span>
                      <?php else: ?>
                        <span class="bg-gray-100 text-gray-500 text-[10px] font-bold px-2.5 py-1 rounded-full">Draft</span>
                      <?php endif; ?>
                    </td>
                    <td class="p-4">
                      <div class="flex items-center justify-center space-x-2">
                        <button onclick='openPreviewModal(<?= json_encode($row) ?>)' class="p-2 border border-gray-200 rounded-lg text-gray-500 hover:text-[#186a5a] hover:border-[#186a5a] transition cursor-pointer">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                        <button onclick='openFormModal(<?= json_encode($row) ?>)' class="p-2 border border-gray-200 rounded-lg text-gray-500 hover:text-blue-600 hover:border-blue-600 transition cursor-pointer">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                        <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus kegiatan ini?')" class="p-2 border border-gray-200 rounded-lg text-gray-500 hover:text-red-600 hover:border-red-600 transition">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>

    <!-- MODAL FORM (TAMBAH / EDIT) -->
    <div id="formModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-xl">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
          <h3 id="formModalTitle" class="font-bold text-gray-900 text-base">Tambah Kegiatan</h3>
          <button onclick="closeFormModal()" class="text-gray-400 hover:text-gray-600 text-lg cursor-pointer">&times;</button>
        </div>

        <form action="" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
          <input type="hidden" name="simpan_kegiatan" value="1" />
          <input type="hidden" id="formId" name="id" value="0" />
          <input type="hidden" id="formGambarLama" name="gambar_lama" value="default-kegiatan.jpg" />

          <div>
            <label class="block font-bold text-gray-700 mb-1">Judul Kegiatan</label>
            <input type="text" id="formJudul" name="judul" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#186a5a]" />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-gray-700 mb-1">Tanggal</label>
              <input type="date" id="formTanggal" name="tanggal" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#186a5a]" />
            </div>
            <div>
              <label class="block font-bold text-gray-700 mb-1">Tempat</label>
              <input type="text" id="formTempat" name="tempat" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#186a5a]" />
            </div>
          </div>

          <div>
            <label class="block font-bold text-gray-700 mb-1">Deskripsi Kegiatan</label>
            <textarea id="formDeskripsi" name="deskripsi" rows="3" required class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:border-[#186a5a]"></textarea>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-gray-700 mb-1">Gambar Cover</label>
              <input type="file" name="gambar" accept=".jpg,.jpeg,.png" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-2 text-[11px]" />
            </div>
            <div>
              <label class="block font-bold text-gray-700 mb-1">Status Publikasi</label>
              <select id="formStatus" name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#186a5a]">
                <option value="Dipublikasikan">Dipublikasikan</option>
                <option value="Draft">Draft</option>
              </select>
            </div>
          </div>

          <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100">
            <button type="button" onclick="closeFormModal()" class="px-4 py-2 border border-gray-300 rounded-xl text-gray-600 font-semibold cursor-pointer">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#186a5a] text-white rounded-xl font-semibold hover:bg-[#135548] cursor-pointer">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL PRATINJAU -->
    <div id="previewModal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
      <div class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl relative">
        <button onclick="closePreviewModal()" class="absolute top-3 right-3 w-8 h-8 bg-white/80 hover:bg-white rounded-full flex items-center justify-center text-gray-700 font-bold z-10 shadow-md cursor-pointer">&times;</button>
        
        <div class="relative h-48 bg-gray-100">
          <span class="absolute top-3 left-3 bg-[#186a5a] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Pratinjau Website</span>
          <img id="previewGambar" src="" class="w-full h-full object-cover" />
        </div>

        <div class="p-6 space-y-3">
          <span id="previewStatus" class="inline-block text-[10px] font-bold px-2.5 py-0.5 rounded-full"></span>
          <h2 id="previewJudul" class="text-xl font-extrabold text-gray-900 leading-tight"></h2>
          
          <div class="flex items-center space-x-4 text-xs text-gray-400">
            <span id="previewTanggal"></span>
            <span>&bull;</span>
            <span id="previewTempat"></span>
          </div>

          <p id="previewDeskripsi" class="text-xs text-gray-500 leading-relaxed pt-2 border-t border-gray-100"></p>
        </div>
      </div>
    </div>

    <!-- SCRIPT POPUP MODAL -->
    <script>
      function openFormModal(data = null) {
        const modal = document.getElementById('formModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        if (data) {
          document.getElementById('formModalTitle').textContent = 'Edit Kegiatan';
          document.getElementById('formId').value = data.id;
          document.getElementById('formJudul').value = data.judul;
          document.getElementById('formTanggal').value = data.tanggal;
          document.getElementById('formTempat').value = data.tempat;
          document.getElementById('formDeskripsi').value = data.deskripsi;
          document.getElementById('formStatus').value = data.status;
          document.getElementById('formGambarLama').value = data.gambar;
        } else {
          document.getElementById('formModalTitle').textContent = 'Tambah Kegiatan';
          document.getElementById('formId').value = 0;
          document.getElementById('formJudul').value = '';
          document.getElementById('formTanggal').value = '';
          document.getElementById('formTempat').value = '';
          document.getElementById('formDeskripsi').value = '';
          document.getElementById('formStatus').value = 'Dipublikasikan';
          document.getElementById('formGambarLama').value = 'default-kegiatan.jpg';
        }
      }

      function closeFormModal() {
        const modal = document.getElementById('formModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }

      function openPreviewModal(data) {
        const modal = document.getElementById('previewModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('previewGambar').src = '../assets/gambar/' + data.gambar;
        document.getElementById('previewJudul').textContent = data.judul;
        document.getElementById('previewTanggal').textContent = data.tanggal;
        document.getElementById('previewTempat').textContent = data.tempat;
        document.getElementById('previewDeskripsi').textContent = data.deskripsi;

        const statusEl = document.getElementById('previewStatus');
        statusEl.textContent = data.status;
        if (data.status === 'Dipublikasikan') {
          statusEl.className = 'inline-block bg-emerald-50 text-[#186a5a] text-[10px] font-bold px-2.5 py-0.5 rounded-full';
        } else {
          statusEl.className = 'inline-block bg-gray-100 text-gray-500 text-[10px] font-bold px-2.5 py-0.5 rounded-full';
        }
      }

      function closePreviewModal() {
        const modal = document.getElementById('previewModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    </script>
  </body>
</html>