<?php
require './services/koneksi.php';
$q_stat = mysqli_query($conn, "SELECT * FROM statistik WHERE id = 1");
$stat   = mysqli_fetch_assoc($q_stat);
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./src/output.css" />
    <title>Satu Rukun - RW 001 Garut</title>
    <style>
      .no-scrollbar::-webkit-scrollbar {
        display: none;
      }
      .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
      }
    </style>
  </head>
  <body class="bg-gray-200 font-sans">
    <!-- Navbar Header -->
    <header
      class="bg-white border-b border-gray-100 py-4 px-6 md:px-16 flex items-center justify-between"
    >
      <!-- Brand / Logo Name -->
      <div class="flex items-center space-x-1">
        <a
          href="index.html"
          class="flex items-center space-x-3 group cursor-pointer"
        >
        <img
          src="assets/gambar/logo_main_web.png"
          alt="Logo Satu Rukun"
          class="h-10 w-auto"
        />
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
          href="./halaman/layananlapor.php"
          class="hover:text-emerald-700 transition"
          >Layanan Lapor</a
        >
        <a
          href="./halaman/profil-rw.php"
          class="hover:text-emerald-700 transition"
          >Profil Ketua RW</a
        >
        <a
          href="./halaman/kegiatan.php"
          class="hover:text-emerald-700 transition"
          >Kegiatan</a
        >
        <a
          href="./halaman/lokasi.html"
          class="hover:text-emerald-700 transition"
          >Lokasi RW</a
        >
        <a
          href="./halaman/berita.php"
          class="hover:text-emerald-700 transition"
          >Berita</a
        >
        <a
          href="./halaman/keuangan.php"
          class="hover:text-emerald-700 transition"
          >Laporan Keuangan</a
        >
      </nav>

      <!-- Tombol Login / Profil User -->
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

    <!-- Hero Section -->
    <main
      class="max-w-7xl mx-auto px-6 md:px-16 py-12 lg:py-20 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center"
    >
      <!-- Text Column -->
      <div class="space-y-6">
        <span
          class="inline-block px-3 py-1 bg-emerald-200/25 text-emerald-900 text-xs font-semibold tracking-wider rounded-md uppercase"
        >
          PORTAL WARGA RW 001 GARUT
        </span>

        <!-- Heading -->
        <h1
          class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight"
        >
          Selamat datang <br />
          di <span class="text-emerald-800">Satu Rukun</span> <br />
          RW 001 Garut
        </h1>

        <!-- Description -->
        <p class="text-gray-500 text-base md:text-lg max-w-md leading-relaxed">
          Portal informasi dan layanan warga RW 001 Garut. Temukan informasi
          kegiatan, pengumuman, dan layanan administrasi di sini.
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 pt-2">
          <a
            href="./halaman/layananlapor.php"
            class="px-6 py-3 bg-emerald-800 hover:bg-emerald-900 text-white font-medium rounded-xl text-sm shadow-sm transition"
          >
            Layanan Lapor
          </a>
          <a
            href="./halaman/berita.php"
            class="px-6 py-3 bg-white border border-emerald-800 text-emerald-800 hover:bg-emerald-50 font-medium rounded-xl text-sm transition"
          >
            Lihat Berita
          </a>
        </div>
      </div>

      <!-- Right Column: Dynamic Photo Slider -->
      <div class="relative w-full max-w-xl mx-auto">
        <!-- Slider Window Container -->
        <div
          id="slider"
          class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar rounded-3xl shadow-md border border-gray-100 h-80 md:h-96"
        >
          <!-- Gambar dimuat via JS -->
        </div>

        <!-- Indikator Bulat-Bulat (Dots) -->
        <div
          id="dots-container"
          class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center space-x-2 z-20 bg-black/20 backdrop-blur-sm px-3 py-1.5 rounded-full"
        >
          <!-- Bulat-bulat dimuat via JS -->
        </div>

        <!-- Badge Overlay Kanan Bawah -->
        <div
          class="absolute -bottom-6 -right-2 bg-amber-400 text-white font-bold px-6 py-4 rounded-xl shadow-lg text-center z-10 pointer-events-none"
        >
          <div class="text-2xl tracking-wider">001</div>
          <div
            class="text-[10px] font-semibold tracking-wider uppercase opacity-90"
          >
            Garut
          </div>
        </div>
      </div>
    </main>

    <!-- Section 2: Statistik Warga -->
    <section class="bg-[#f7f6f1] py-12 md:py-16">
      <div class="max-w-7xl mx-auto px-6 md:px-16">
        <h2
          class="text-2xl md:text-3xl font-bold text-center text-gray-900 mb-8 md:mb-10"
        >
          Statistik Warga
        </h2>

        <!-- Grid Kartu Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Kartu 1: Rumah -->
          <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center space-x-5"
          >
            <div
              class="p-3 bg-emerald-50 text-emerald-800 rounded-xl flex items-center justify-center"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6"
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
            <div>
              <span class="block text-3xl font-extrabold text-emerald-900">
                <?= number_format($stat['jumlah_rumah'] ?? 128) ?>
              </span>
              <span class="block text-sm font-semibold text-gray-800"
                >Rumah</span
              >
              <span class="block text-xs text-gray-400 mt-0.5"
                >Unit hunian tercatat</span
              >
            </div>
          </div>

          <!-- Kartu 2: Warga -->
          <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center space-x-5"
          >
            <div
              class="p-3 bg-emerald-50 text-emerald-800 rounded-xl flex items-center justify-center"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                />
              </svg>
            </div>
            <div>
              <span class="block text-3xl font-extrabold text-emerald-900">
                <?= number_format($stat['jumlah_warga'] ?? 436) ?>
              </span>
              <span class="block text-sm font-semibold text-gray-800"
                >Warga</span
              >
              <span class="block text-xs text-gray-400 mt-0.5"
                >Jiwa terdaftar</span
              >
            </div>
          </div>

          <!-- Kartu 3: Ketua RW -->
          <div
            class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center space-x-5"
          >
            <div
              class="p-3 bg-emerald-50 text-emerald-800 rounded-xl flex items-center justify-center"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                />
              </svg>
            </div>
            <div>
              <span class="block text-3xl font-extrabold text-emerald-900"
                >1</span
              >
              <span class="block text-sm font-semibold text-gray-800"
                >Ketua RW</span
              >
              <span class="block text-xs text-gray-400 mt-0.5">
                <?= htmlspecialchars($stat['nama_ketua_rw'] ?? 'dr. Hj. Farih') ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer
      class="bg-[#f7f6f1] py-6 px-6 md:px-16 border-t border-gray-100/50 mt-20"
    >
      <div
        class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4"
      >
        <p class="text-xs text-gray-400 font-normal">
          © 2026 Satu Rukun RW 001 Garut. Hak cipta dilindungi.
        </p>

        <div class="flex items-center space-x-2">
          <div
            class="w-7 h-7 bg-emerald-800 rounded-lg flex items-center justify-center text-white"
          >
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

    <!-- 1. Panggil Data Slide -->
    <script src="./data/gambarslide.js"></script>

    <!-- 2. Logika Render & Auto Slide -->
    <script>
      const slider = document.getElementById("slider");
      const dotsContainer = document.getElementById("dots-container");

      let slideIndex = 0;
      let slideInterval;

      function initSlider() {
        if (
          typeof daftarHeroSlide !== "undefined" &&
          daftarHeroSlide.length > 0
        ) {
          let slidesHTML = "";
          dotsContainer.innerHTML = "";

          daftarHeroSlide.forEach((item, index) => {
            // Rangkai string HTML slide
            slidesHTML += `
              <div class="w-full h-full flex-shrink-0 snap-center">
                <img 
                  src="${item.gambar}" 
                  alt="${item.judul}" 
                  class="w-full h-full object-cover select-none" 
                />
              </div>
            `;

            // Render Indikator Bulat (Dot)
            const dot = document.createElement("button");
            dot.className = `w-2.5 h-2.5 rounded-full transition-all duration-300 ${
              index === 0 ? "bg-white w-6" : "bg-white/50 hover:bg-white/80"
            }`;
            dot.addEventListener("click", () => goToSlide(index));
            dotsContainer.appendChild(dot);
          });

          // Masukkan HTML slide secara utuh sekaligus
          slider.innerHTML = slidesHTML;
        }
      }

      function goToSlide(index) {
        slideIndex = index;
        slider.scrollTo({
          left: slideIndex * slider.clientWidth,
          behavior: "smooth",
        });
        updateDots();
        resetTimer();
      }

      function updateDots() {
        const dots = dotsContainer.children;
        Array.from(dots).forEach((dot, index) => {
          if (index === slideIndex) {
            dot.className =
              "w-6 h-2.5 bg-white rounded-full transition-all duration-300";
          } else {
            dot.className =
              "w-2.5 h-2.5 bg-white/50 hover:bg-white/80 rounded-full transition-all duration-300";
          }
        });
      }

      function nextSlide() {
        if (slider.children.length === 0) return;
        slideIndex = (slideIndex + 1) % slider.children.length;
        slider.scrollTo({
          left: slideIndex * slider.clientWidth,
          behavior: "smooth",
        });
        updateDots();
      }

      function startTimer() {
        slideInterval = setInterval(nextSlide, 5000);
      }

      function resetTimer() {
        clearInterval(slideInterval);
        startTimer();
      }

      slider.addEventListener("scroll", () => {
        const newIndex = Math.round(slider.scrollLeft / slider.clientWidth);
        if (newIndex !== slideIndex) {
          slideIndex = newIndex;
          updateDots();
        }
      });

      // Inisialisasi awal
      initSlider();
      startTimer();
    </script>

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
      href="./services/logout.php"
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
</html>
