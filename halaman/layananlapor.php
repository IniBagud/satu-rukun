<?php
require '../services/koneksi.php';

$q_stat = mysqli_query($conn, "SELECT * FROM statistik WHERE id = 1");
$stat   = mysqli_fetch_assoc($q_stat);

$sudah_login = isset($_SESSION['user_id']);
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../src/output.css" />

    <title>Layanan-Lapor</title>
  </head>
  <body class="bg-gray-200 flex flex-col min-h-screen">
    <header
      class="bg-white border-b border-gray-100 py-4 px-6 md:px-16 flex items-center justify-between"
    >
      <!-- Brand / Logo Name -->
      <div class="flex items-center space-x-1">
        <a
          href="../index.php"
          class="flex items-center space-x-3 group cursor-pointer"
        >
          <div class="font-bold text-xl leading-none text-emerald-800">
            SatuRukun <br />
            <span class="text-sm font-normal text-gray-400 tracking-wider">
              RW 001 Garut
            </span>
          </div>
        </a>
      </div>

      <!-- Navigation Links -->
      <nav
        class="hidden lg:flex items-center space-x-8 text-sm font-medium text-gray-600"
      >
        <a
          href="layananlapor.php"
          class="bg-emerald-800 text-white px-4 py-2 rounded-xl font-medium shadow-sm"
          >Layanan Lapor</a
        >
        <a href="profil-rw.php" class="hover:text-emerald-700 transition"
          >Profil Ketua RW</a
        >
        <a href="kegiatan.php" class="hover:text-emerald-700 transition"
          >Kegiatan</a
        >
        <a href="lokasi.html" class="hover:text-emerald-700 transition"
          >Lokasi RW</a
        >
        <a href="berita.php" class="hover:text-emerald-700 transition"
          >Berita</a
        >
        <a href="keuangan.php" class="hover:text-emerald-700 transition"
          >Laporan Keuangan</a
        >
      </nav>

      <div>
  <?php if (isset($_SESSION['user_id'])): ?>
    <!-- Tombol Foto/Ikon Orang saat Sudah Login -->
    <button
      id="userMenuBtn"
      class="flex items-center space-x-2 bg-emerald-800 hover:bg-emerald-900 text-white p-2.5 sm:px-4 sm:py-2 rounded-full transition duration-200 shadow-sm focus:outline-none"
    >
      <!-- Icon Orang -->
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="w-5 h-5"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
        />
      </svg>
      <span class="text-xs sm:text-sm font-medium hidden sm:inline">
        <?= htmlspecialchars($_SESSION['nama']) ?>
      </span>
    </button>
  <?php else: ?>
    <!-- Tombol Login biasa jika Belum Login -->
    <a
      href="./halaman/login.html"
      class="px-5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white rounded-full text-sm font-medium transition"
    >
      Login
    </a>
  <?php endif; ?>
</div>

    </header>
    <!-- Main Content Area - Layanan Lapor -->
    <main
      class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 flex flex-col items-center"
    >
      <!-- Title & Subtitle Section -->
      <div class="text-center max-w-xl mx-auto mb-10">
        <!-- Main Title -->
        <h1
          class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight mb-3"
        >
          Layanan Lapor
        </h1>

        <!-- Teal Divider Line -->
        <div class="w-12 h-1 bg-emerald-700 mx-auto rounded-full mb-4"></div>

        <!-- Description -->
        <p
          class="text-gray-600 text-sm md:text-base font-normal leading-relaxed"
        >
          Sampaikan keluhan, saran, atau laporan kepada pengurus RW 001 Garut.
          Kami akan segera menindaklanjuti.
        </p>
      </div>

      <!-- Form Container Card -->
      <div
        class="w-full max-w-lg bg-white rounded-3xl border border-gray-100 shadow-sm p-8 sm:p-10"
      >
        <form action="#" method="POST" class="space-y-6">
          <!-- Input Nama -->
          <div>
            <label
              for="nama"
              class="block text-sm font-semibold text-gray-700 mb-2"
            >
              Nama
            </label>
            <input
              type="text"
              id="nama"
              name="nama"
              placeholder="Masukkan nama lengkap"
              required
              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:border-transparent transition duration-200"
            />
          </div>

          <!-- Input No. Telepon -->
          <div>
            <label
              for="telepon"
              class="block text-sm font-semibold text-gray-700 mb-2"
            >
              No. Telepon
            </label>
            <input
              type="tel"
              id="telepon"
              name="telepon"
              placeholder="Contoh: 08xx-xxxx-xxxx"
              required
              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:border-transparent transition duration-200"
            />
          </div>

          <!-- Input Pesan / Keluhan -->
          <div>
            <label
              for="pesan"
              class="block text-sm font-semibold text-gray-700 mb-2"
            >
              Pesan / Keluhan
            </label>
            <textarea
              id="pesan"
              name="pesan"
              rows="5"
              placeholder="Masukan pesan / keluhan yang ingin disampaikan"
              required
              class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:border-transparent transition duration-200 resize-none"
            ></textarea>
          </div>

          <!-- Submit Button -->
          <div class="pt-2">
            <button
              type="submit"
              class="w-full bg-[#186a5a] hover:bg-[#135548] text-white font-medium py-3 px-6 rounded-xl transition duration-200 shadow-sm text-sm"
            >
              Kirim
            </button>
          </div>
        </form>
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#f7f6f1] py-6 px-6 md:px-16 border-t border-gray-100/50">
      <div
        class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4"
      >
        <!-- Teks Hak Cipta -->
        <p class="text-xs text-gray-400 font-normal">
          © 2026 Satu Rukun RW 001 Garut. Hak cipta dilindungi.
        </p>

        <!-- Branding Logo Footer -->
        <div class="flex items-center space-x-2">
          <div
            class="w-7 h-7 bg-emerald-800 rounded-lg flex items-center justify-center text-white"
          >
            <!-- Icon Rumah -->
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="w-4 h-4"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              stroke-width="2"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
              />
            </svg>
          </div>
          <span class="text-xs font-semibold text-gray-700">
            satu rukun <span class="text-emerald-900 font-bold">001</span>
          </span>
        </div>
      </div>
    </footer>

    <!-- Overlay Gelap Background -->
<div
  id="userMenuOverlay"
  class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300"
></div>

<!-- Drawer Panel Popup (Bergeser dari Kanan ke Kiri) -->
<div
  id="userMenuDrawer"
  class="fixed top-0 right-0 h-full w-72 bg-white shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out p-6 flex flex-col justify-between"
>
  <div>
    <!-- Header Popup -->
    <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-6">
      <h3 class="text-sm font-bold text-gray-800">Akun Saya</h3>
      <button id="closeMenuBtn" class="text-gray-400 hover:text-gray-600 focus:outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Informasi Profil User -->
    <div class="flex items-center space-x-3 bg-emerald-50/60 p-3.5 rounded-2xl border border-emerald-100/50 mb-6">
      <div class="w-10 h-10 bg-emerald-800 text-white rounded-xl flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
      </div>
      <div class="overflow-hidden">
        <p class="text-sm font-bold text-gray-800 truncate">
          <?= htmlspecialchars($_SESSION['nama'] ?? 'Warga') ?>
        </p>
        <span class="inline-block text-[11px] font-medium text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-md uppercase tracking-wider">
          <?= htmlspecialchars($_SESSION['role'] ?? 'warga') ?>
        </span>
      </div>
    </div>
  </div>

  <!-- Tombol Logout -->
  <div>
    <a
      href="logout.php"
      class="w-full bg-red-500 hover:bg-red-600 text-white py-3 px-4 rounded-xl text-sm font-semibold flex items-center justify-center space-x-2 transition duration-200 shadow-sm"
    >
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
      </svg>
      <span>Keluar / Logout</span>
    </a>
  </div>
</div>

<!-- Script Logika Buka/Tutup Popup -->
<script>
  const userMenuBtn = document.getElementById("userMenuBtn");
  const userMenuOverlay = document.getElementById("userMenuOverlay");
  const userMenuDrawer = document.getElementById("userMenuDrawer");
  const closeMenuBtn = document.getElementById("closeMenuBtn");

  function openUserMenu() {
    userMenuOverlay.classList.remove("hidden");
    setTimeout(() => {
      userMenuOverlay.classList.remove("opacity-0");
      userMenuDrawer.classList.remove("translate-x-full");
    }, 10);
  }

  function closeUserMenu() {
    userMenuDrawer.classList.add("translate-x-full");
    userMenuOverlay.classList.add("opacity-0");
    setTimeout(() => {
      userMenuOverlay.classList.add("hidden");
    }, 300);
  }

  if (userMenuBtn) userMenuBtn.addEventListener("click", openUserMenu);
  if (closeMenuBtn) closeMenuBtn.addEventListener("click", closeUserMenu);
  if (userMenuOverlay) userMenuOverlay.addEventListener("click", closeUserMenu);
</script>

  </body>
  <?php if (!$sudah_login): ?>
<div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center shadow-xl space-y-4">
        <h3 class="text-xl font-bold text-gray-900">Akses Terbatas</h3>
        <p class="text-sm text-gray-500">Kamu harus login terlebih dahulu untuk mengakses dan mengirim Layanan Lapor.</p>
        <div class="pt-2 flex flex-col gap-2">
            <a href="login.html" class="w-full bg-[#186a5a] text-white py-2.5 rounded-xl text-sm font-semibold block">Login Sekarang</a>
            <a href="../index.php" class="text-xs text-gray-400 font-medium hover:underline">Kembali ke Beranda</a>
        </div>
    </div>
</div>
<?php endif; ?>
</html>
