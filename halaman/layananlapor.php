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
    <link rel="stylesheet" href="../src/output.css?v=2" />

    <title>Layanan-Publik</title>
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
          <img
            src="../assets/gambar/logo_main_web.png"
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
          href="layananlapor.php"
          class="bg-emerald-800 text-white px-4 py-2 rounded-xl font-medium shadow-sm"
          >Layanan Publik</a
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

      <!-- Section 2: Alur Pengajuan Surat Pengantar -->
      <section class="w-full max-w-6xl mx-auto mt-16 mb-8">
        <!-- Header Section -->
        <div class="mb-8">
          <p class="text-xs sm:text-sm text-gray-500 mb-1">
            Proses mudah, transparan, dan dapat dipantau secara daring.
          </p>
          <span
            class="block text-xs font-bold tracking-widest text-emerald-700 uppercase mb-2"
          >
            LAYANAN PENGAJUAN SURAT PENGANTAR
          </span>
          <h2
            class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight"
          >
            Pilih Layanan yang Dibutuhkan
          </h2>
        </div>

        <!-- Card Alur Pengajuan -->
        <div
          class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 sm:p-12"
        >
          <!-- Judul Card dengan Garis Kiri-Kanan -->
          <div class="flex items-center justify-center gap-4 mb-10">
            <div class="h-px bg-gray-200 w-16 sm:w-28"></div>
            <h3
              class="text-xs font-extrabold tracking-widest text-gray-800 uppercase text-center"
            >
              ALUR PENGAJUAN SURAT PENGANTAR
            </h3>
            <div class="h-px bg-gray-200 w-16 sm:w-28"></div>
          </div>

          <!-- Grid 5 Langkah -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
            <!-- Langkah 01 -->
            <div class="space-y-3">
              <div
                class="w-11 h-11 rounded-full border border-emerald-500 text-emerald-700 font-bold text-xs flex items-center justify-center ring-4 ring-emerald-50"
              >
                01
              </div>
              <h4 class="font-bold text-gray-900 text-sm pt-2">Login</h4>
              <p class="text-xs text-gray-500 leading-relaxed">
                Masuk dengan akun warga untuk melakukan verifikasi identitas. Jika
                belum memiliki akun, silakan mendaftar melalui admin RT.
              </p>
            </div>

            <!-- Langkah 02 -->
            <div class="space-y-3">
              <div
                class="w-11 h-11 rounded-full border border-emerald-500 text-emerald-700 font-bold text-xs flex items-center justify-center ring-4 ring-emerald-50"
              >
                02
              </div>
              <h4 class="font-bold text-gray-900 text-sm pt-2">Pilih Surat</h4>
              <p class="text-xs text-gray-500 leading-relaxed">
                Pilih jenis surat pengantar yang dibutuhkan beserta persyaratan yang
                harus dipenuhi.
              </p>
            </div>

            <!-- Langkah 03 -->
            <div class="space-y-3">
              <div
                class="w-11 h-11 rounded-full border border-emerald-500 text-emerald-700 font-bold text-xs flex items-center justify-center ring-4 ring-emerald-50"
              >
                03
              </div>
              <h4 class="font-bold text-gray-900 text-sm pt-2">Isi Keperluan</h4>
              <p class="text-xs text-gray-500 leading-relaxed">
                Jelaskan keperluan pembuatan surat pengantar secara lengkap dan jelas.
              </p>
            </div>

            <!-- Langkah 04 -->
            <div class="space-y-3">
              <div
                class="w-11 h-11 rounded-full border border-emerald-500 text-emerald-700 font-bold text-xs flex items-center justify-center ring-4 ring-emerald-50"
              >
                04
              </div>
              <h4 class="font-bold text-gray-900 text-sm pt-2">Kirim</h4>
              <p class="text-xs text-gray-500 leading-relaxed">
                Permohonan akan diverifikasi oleh Ketua RT setempat, kemudian
                diteruskan kepada Ketua RW.
              </p>
            </div>

            <!-- Langkah 05 -->
            <div class="space-y-3">
              <div
                class="w-11 h-11 rounded-full border border-emerald-500 text-emerald-700 font-bold text-xs flex items-center justify-center ring-4 ring-emerald-50"
              >
                05
              </div>
              <h4 class="font-bold text-gray-900 text-sm pt-2">Selesai</h4>
              <p class="text-xs text-gray-500 leading-relaxed">
                Setelah disetujui, surat akan ditandatangani oleh Ketua RT/RW dan siap
                untuk diambil.
              </p>
            </div>
          </div>
        </div>
      </section>

      <div id="Pilih-surat">
        <!-- Section 3: Pilihan Bentuk Pengajuan Surat -->
        <section class="w-full max-w-6xl mx-auto mt-8 mb-16">
          <?php
          $daftar_surat = [
            "Surat Keterangan Domisili",
            "Surat Keterangan Tidak Mampu",
            "Surat Keterangan Usaha",
            "Surat Pengantar Nikah",
            "Surat Keterangan Pindah Domisili",
            "Surat Keterangan Lahir dan Kematian",
            "Surat Keterangan Kehilangan",
            "Surat Pengantar SKCK",
            "Surat Keterangan Tinggal Sementara/KTP Sementara",
            "Surat Keterangan Ahli Waris",
            "Surat Keterangan Janda/Duda Cerai",
            "Surat Keterangan Jual Beli"
          ];
          ?>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <?php foreach ($daftar_surat as $surat): ?>
              <button
                type="button"
                onclick="bukaModalSurat('<?= htmlspecialchars($surat, ENT_QUOTES) ?>')"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center justify-between gap-3 text-left hover:border-emerald-500 hover:shadow-md transition duration-200 group cursor-pointer"
              >
                <div class="flex items-center space-x-4">
                  <!-- Lingkaran Ikon Dokumen -->
                  <div
                    class="w-11 h-11 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition duration-200"
                  >
                    <svg
                      class="w-5 h-5"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                      />
                    </svg>
                  </div>

                  <!-- Judul Surat -->
                  <span class="font-bold text-gray-800 text-xs sm:text-sm leading-snug">
                    <?= htmlspecialchars($surat) ?>
                  </span>
                </div>

                <!-- Panah Kanan -->
                <svg
                  class="w-4 h-4 text-gray-400 group-hover:text-emerald-700 group-hover:translate-x-0.5 transition duration-200 shrink-0"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"
                  />
                </svg>
              </button>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Modal Popup Pengajuan Surat -->
        <div
          id="modalSuratOverlay"
          class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 opacity-0 transition-opacity duration-300 flex items-center justify-center p-4"
        >
          <div
            id="modalSuratBox"
            class="bg-white rounded-3xl w-full max-w-xl p-7 sm:p-10 shadow-2xl relative transform scale-95 transition-transform duration-300"
          >
            <!-- Tombol Close (X) -->
            <button
              type="button"
              id="closeModalSuratBtn"
              class="absolute top-6 right-6 w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 flex items-center justify-center transition cursor-pointer"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>

            <!-- Header Modal -->
            <div class="mb-6 pr-8">
              <span
                class="block text-[11px] font-extrabold tracking-widest text-emerald-600 uppercase mb-1.5"
              >
                FORMULIR PENGAJUAN
              </span>
              <h3
                id="modalJudulSurat"
                class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight mb-2"
              >
                Surat Keterangan Domisili
              </h3>
              <p class="text-xs sm:text-sm text-gray-500">
                Isi data berikut dengan lengkap. Admin RT akan memverifikasi permohonan Anda.
              </p>
            </div>

            <!-- Form Input Pengajuan -->
            <form id="formPengajuanSurat" action="#" method="POST" enctype="multipart/form-data" class="space-y-5">
              <input type="hidden" id="inputJenisSurat" name="jenis_surat" value="" />

              <!-- Baris 1: Nama Lengkap & RT -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                  <label
                    for="nama_lengkap"
                    class="block text-xs font-bold text-gray-800 mb-2"
                  >
                    Nama Lengkap
                  </label>
                  <input
                    type="text"
                    id="nama_lengkap"
                    name="nama_lengkap"
                    value="<?= htmlspecialchars($_SESSION['nama'] ?? '') ?>"
                    placeholder="Sesuai KTP"
                    required
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:border-transparent transition"
                  />
                </div>

                <div>
                  <label
                    for="rt"
                    class="block text-xs font-bold text-gray-800 mb-2"
                  >
                    RT
                  </label>
                  <select
                    id="rt"
                    name="rt"
                    required
                    class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:border-transparent transition"
                  >
                    <option value="" disabled selected>Pilih RT</option>
                    <option value="RT 001">RT 001</option>
                    <option value="RT 002">RT 002</option>
                    <option value="RT 003">RT 003</option>
                    <option value="RT 004">RT 004</option>
                    <option value="RT 005">RT 005</option>
                    <option value="RT 006">RT 006</option>

                  </select>
                </div>
              </div>

              <!-- Baris 2: Keperluan Pengajuan -->
              <div>
                <label
                  for="keperluan"
                  class="block text-xs font-bold text-gray-800 mb-2"
                >
                  Keperluan Pengajuan
                </label>
                <textarea
                  id="keperluan"
                  name="keperluan"
                  rows="4"
                  placeholder="Jelaskan keperluan surat secara lengkap"
                  required
                  class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-700 focus:border-transparent transition resize-none"
                ></textarea>
              </div>

              <!-- Baris 3: Dokumen Pendukung -->
              <div>
                <label
                  for="dokumen"
                  class="block text-xs font-bold text-gray-800 mb-2"
                >
                  Dokumen Pendukung
                </label>
                <input
                  type="file"
                  id="dokumen"
                  name="dokumen"
                  accept=".pdf,.jpg,.jpeg,.png"
                  required
                  class="w-full px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-700 transition cursor-pointer"
                />
                <span class="block text-[11px] text-gray-400 mt-1.5">
                  PDF, JPG, atau PNG · Maks. 5 MB
                </span>
              </div>

              <!-- Tombol Submit -->
              <div class="pt-2">
                <button
                  type="submit"
                  class="w-full bg-[#186a5a] hover:bg-[#135548] text-white font-semibold py-3.5 px-6 rounded-xl transition duration-200 shadow-sm text-sm flex items-center justify-center space-x-2 cursor-pointer"
                >
                  <span>Kirim Pengajuan</span>
                  <span>&rarr;</span>
                </button>
              </div>
            </form>
          </div>
        </div>

        <script>
          const modalSuratOverlay = document.getElementById("modalSuratOverlay");
          const modalSuratBox = document.getElementById("modalSuratBox");
          const closeModalSuratBtn = document.getElementById("closeModalSuratBtn");
          const modalJudulSurat = document.getElementById("modalJudulSurat");
          const inputJenisSurat = document.getElementById("inputJenisSurat");

          function bukaModalSurat(namaSurat) {
            modalJudulSurat.textContent = namaSurat;
            inputJenisSurat.value = namaSurat;

            modalSuratOverlay.classList.remove("hidden");
            setTimeout(() => {
              modalSuratOverlay.classList.remove("opacity-0");
              modalSuratBox.classList.remove("scale-95");
              modalSuratBox.classList.add("scale-100");
            }, 10);
          }

          function tutupModalSurat() {
            modalSuratOverlay.classList.add("opacity-0");
            modalSuratBox.classList.remove("scale-100");
            modalSuratBox.classList.add("scale-95");
            setTimeout(() => {
              modalSuratOverlay.classList.add("hidden");
            }, 300);
          }

          closeModalSuratBtn.addEventListener("click", tutupModalSurat);

          // Tutup modal jika klik area gelap di luar kotak putih
          modalSuratOverlay.addEventListener("click", (e) => {
            if (e.target === modalSuratOverlay) {
              tutupModalSurat();
            }
          });

          // Tutup modal dengan tombol Esc
          document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && !modalSuratOverlay.classList.contains("hidden")) {
              tutupModalSurat();
            }
          });
        </script>
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

    <div class= mengatur popup user menu di pojok kanan atas>
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
    </div>

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
