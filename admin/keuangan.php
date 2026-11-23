<?php
require '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../halaman/login.html");
    exit;
}

$pesan_sukses = "";
$pesan_error = "";

// PROSES SIMPAN TRANSAKSI BARU
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_transaksi'])) {
    $jenis      = mysqli_real_escape_string($conn, $_POST['jenis']);
    $tanggal    = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $keterangan = mysqli_real_escape_string($conn, trim($_POST['keterangan']));
    $jumlah     = intval($_POST['jumlah']);

    $q_insert = "INSERT INTO keuangan (jenis, tanggal, keterangan, jumlah) VALUES ('$jenis', '$tanggal', '$keterangan', $jumlah)";
    if (mysqli_query($conn, $q_insert)) {
        $detail_log = "$keterangan (Rp " . number_format($jumlah, 0, ',', '.') . ")";
        mysqli_query($conn, "INSERT INTO log_aktivitas (tipe, aksi, detail) VALUES ('keuangan', 'Transaksi $jenis', '$detail_log')");
        $pesan_sukses = "Transaksi berhasil dicatat!";
    } else {
        $pesan_error = "Gagal mencatat transaksi: " . mysqli_error($conn);
    }
}

// PROSES HAPUS TRANSAKSI
if (isset($_GET['hapus'])) {
    $id_hapus = intval($_GET['hapus']);
    if (mysqli_query($conn, "DELETE FROM keuangan WHERE id = $id_hapus")) {
        $pesan_sukses = "Transaksi berhasil dihapus!";
    }
}

// HITUNG REKAPITULASI KEUANGAN
$q_in  = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM keuangan WHERE jenis = 'Pemasukan'");
$pemasukan = mysqli_fetch_assoc($q_in)['total'] ?? 0;

$q_out = mysqli_query($conn, "SELECT SUM(jumlah) AS total FROM keuangan WHERE jenis = 'Pengeluaran'");
$pengeluaran = mysqli_fetch_assoc($q_out)['total'] ?? 0;

$saldo_akhir = $pemasukan - $pengeluaran;

// AMBIL DAFTAR RIWAYAT TRANSAKSI
$q_transaksi = mysqli_query($conn, "SELECT * FROM keuangan ORDER BY tanggal DESC, id DESC");
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Keuangan - Admin Satu Rukun</title>
    <link rel="stylesheet" href="../src/output.css" />
  </head>
  <body class="bg-[#f7f6f1] text-gray-800 font-sans flex h-screen overflow-hidden">
    
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

          <a href="berita.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <span>Berita</span>
          </a>

          <a href="keuangan.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl bg-emerald-50 text-[#186a5a] font-semibold text-sm">
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
      
      <!-- TOPBAR HEADER -->
      <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between shrink-0">
        <div class="text-xs text-gray-400 font-medium flex items-center space-x-2">
          <span>Admin</span>
          <span>&rsaquo;</span>
          <span class="text-gray-700 font-semibold">Keuangan</span>
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

      <!-- MAIN BODY SCROLLABLE -->
      <main class="p-8 space-y-6 overflow-y-auto grow">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Keuangan</h1>
            <p class="text-xs text-gray-400 mt-1">Catat dan pantau arus kas warga secara transparan.</p>
          </div>
          <button onclick="openModal()" class="bg-[#186a5a] hover:bg-[#135548] text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition flex items-center space-x-2 cursor-pointer shadow-sm">
            <span>+ Catat Transaksi</span>
          </button>
        </div>

        <?php if ($pesan_sukses): ?>
          <div class="p-4 bg-emerald-100 border border-emerald-200 text-[#186a5a] rounded-2xl text-xs font-semibold"><?= $pesan_sukses ?></div>
        <?php endif; ?>

        <!-- GRID 3 KARTU STATISTIK KAS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          
          <!-- Card Pemasukan -->
          <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-emerald-50 text-[#186a5a] rounded-xl shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
            </div>
            <div>
              <span class="text-xs text-gray-400 block font-medium">Total Pemasukan</span>
              <span class="text-xl font-extrabold text-gray-900 block mt-0.5">Rp <?= number_format($pemasukan, 0, ',', '.') ?></span>
              <span class="text-[10px] text-gray-400 block mt-0.5">Periode <?= date('F Y') ?></span>
            </div>
          </div>

          <!-- Card Pengeluaran -->
          <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-emerald-50 text-[#186a5a] rounded-xl shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
            </div>
            <div>
              <span class="text-xs text-gray-400 block font-medium">Total Pengeluaran</span>
              <span class="text-xl font-extrabold text-gray-900 block mt-0.5">Rp <?= number_format($pengeluaran, 0, ',', '.') ?></span>
              <span class="text-[10px] text-gray-400 block mt-0.5">Periode <?= date('F Y') ?></span>
            </div>
          </div>

          <!-- Card Saldo Akhir -->
          <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center space-x-4">
            <div class="p-3 bg-emerald-50 text-[#186a5a] rounded-xl shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
              <span class="text-xs text-gray-400 block font-medium">Saldo Akhir</span>
              <span class="text-xl font-extrabold text-gray-900 block mt-0.5">Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></span>
              <span class="text-[10px] text-gray-400 block mt-0.5">Total akumulasi kas</span>
            </div>
          </div>

        </div>

        <!-- RIWAYAT TRANSAKSI TABLE -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
          <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-900 text-sm">Riwayat Transaksi</h3>
            <p class="text-xs text-gray-400">Seluruh pemasukan dan pengeluaran tercatat.</p>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-gray-50 text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100">
                <tr>
                  <th class="p-4">Tanggal</th>
                  <th class="p-4">Keterangan</th>
                  <th class="p-4">Jenis</th>
                  <th class="p-4 text-right">Jumlah</th>
                  <th class="p-4 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <?php if (mysqli_num_rows($q_transaksi) > 0): ?>
                  <?php while ($row = mysqli_fetch_assoc($q_transaksi)): ?>
                    <tr class="hover:bg-gray-50/50 transition">
                      <td class="p-4 text-gray-500 whitespace-nowrap"><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                      <td class="p-4 font-bold text-gray-800"><?= htmlspecialchars($row['keterangan']) ?></td>
                      <td class="p-4 whitespace-nowrap">
                        <?php if ($row['jenis'] === 'Pemasukan'): ?>
                          <span class="bg-emerald-50 text-[#186a5a] text-[10px] font-bold px-2.5 py-1 rounded-full">Pemasukan</span>
                        <?php else: ?>
                          <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2.5 py-1 rounded-full">Pengeluaran</span>
                        <?php endif; ?>
                      </td>
                      <td class="p-4 text-right font-extrabold whitespace-nowrap <?= $row['jenis'] === 'Pemasukan' ? 'text-emerald-700' : 'text-red-600' ?>">
                        <?= $row['jenis'] === 'Pemasukan' ? '+ Rp ' : '- Rp ' ?><?= number_format($row['jumlah'], 0, ',', '.') ?>
                      </td>
                      <td class="p-4 text-center whitespace-nowrap">
                        <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus catatan transaksi ini?')" class="p-2 border border-gray-200 rounded-lg text-gray-400 hover:text-red-600 hover:border-red-600 transition inline-block">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </a>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="5" class="p-8 text-center text-gray-400">Belum ada transaksi recorded.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </main>
    </div>

    <!-- MODAL POPUP CATAT TRANSAKSI BARU -->
    <div id="transaksiModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
      <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
          <div>
            <h3 class="font-bold text-gray-900 text-base">Catat Transaksi Baru</h3>
            <p class="text-[11px] text-gray-400">Saldo akhir akan dihitung secara otomatis.</p>
          </div>
          <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-lg cursor-pointer">&times;</button>
        </div>

        <form action="" method="POST" class="space-y-4 text-xs">
          <input type="hidden" name="simpan_transaksi" value="1" />

          <div>
            <label class="block font-bold text-gray-700 mb-1">Jenis Transaksi</label>
            <select name="jenis" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#186a5a]">
              <option value="Pemasukan">Pemasukan</option>
              <option value="Pengeluaran">Pengeluaran</option>
            </select>
          </div>

          <div>
            <label class="block font-bold text-gray-700 mb-1">Tanggal</label>
            <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#186a5a]" />
          </div>

          <div>
            <label class="block font-bold text-gray-700 mb-1">Keterangan</label>
            <input type="text" name="keterangan" placeholder="Contoh: Iuran Warga Bulan Ini" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#186a5a]" />
          </div>

          <div>
            <label class="block font-bold text-gray-700 mb-1">Jumlah (Rp)</label>
            <input type="number" name="jumlah" placeholder="0" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#186a5a]" />
          </div>

          <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100">
            <button type="button" onclick="closeModal()" class="px-4 py-2 border border-gray-300 rounded-xl text-gray-600 font-semibold cursor-pointer">Batal</button>
            <button type="submit" class="px-4 py-2 bg-[#186a5a] text-white rounded-xl font-semibold hover:bg-[#135548] cursor-pointer">Simpan Transaksi</button>
          </div>
        </form>
      </div>
    </div>

    <script>
      function openModal() {
        const modal = document.getElementById('transaksiModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
      }

      function closeModal() {
        const modal = document.getElementById('transaksiModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
      }
    </script>
  </body>
</html>