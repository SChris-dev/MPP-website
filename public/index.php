<?php include_once __DIR__ . '/../includes/header.php'; ?>
<?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

  <!-- Main content -->
  <main class="pt-24 md:ml-64 p-6 space-y-12">

    <!-- Example edits in index.php -->

    <!-- Hero Info Section -->
    <section class="text-center max-w-4xl mx-auto">
      <h1 class="text-3xl md:text-4xl font-bold dark:text-white">Selamat Datang di MPP Kabupaten Tuban</h1>
      <p class="mt-4 text-gray-700 dark:text-gray-300 leading-relaxed">
        MPP dirancang oleh <strong>KEMENPAN RB</strong> sebagai bagian dari perbaikan menyeluruh dan transformasi tata kelola pelayanan publik. Menggabungkan berbagai jenis pelayanan pada satu tempat, penyederhanaan dan prosedur serta integrasi pelayanan pada Mal Pelayanan Publik akan memudahkan akses masyarakat dalam mendapat berbagai jenis pelayanan, serta meningkatkan kepercayaan masyarakat kepada penyelenggara pelayanan publik.
      </p>
    </section>

    <!-- Info Cards -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center border-t-4 border-customText">
        <i class="fa fa-check-circle text-customText text-4xl mb-4"></i>
        <h2 class="text-xl font-semibold mb-2 text-customText dark:text-customText">Transparansi Layanan</h2>
        <p class="text-gray-600 dark:text-gray-300 text-sm">Layanan yang transparan dan terbuka</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center border-t-4 border-customText">
        <i class="fa fa-clock text-customText text-4xl mb-4"></i>
        <h2 class="text-xl font-semibold mb-2 text-customText dark:text-customText">Efisiensi Pelayanan</h2>
        <p class="text-gray-600 dark:text-gray-300 text-sm">Pelayanan yang lebih cepat</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center border-t-4 border-customText">
        <i class="fa-regular fa-thumbs-up text-customText text-4xl mb-4"></i>
        <h2 class="text-xl font-semibold mb-2 text-customText dark:text-customText">Kenyamanan Pelayanan</h2>
        <p class="text-gray-600 dark:text-gray-300 text-sm">Layanan yang lebih nyaman</p>
      </div>
    </section>


  </main>

  <div class="fixed bottom-6 right-6 z-50">
    <button id="accessBtn" class="w-14 h-14 rounded-full bg-red-600 text-white shadow-lg flex items-center justify-center text-xl">
      ⚙️
    </button>
    <div id="accessPopup" class="hidden mt-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-red-500 rounded-lg shadow-lg p-4 space-y-2">
      <button id="btnDark" class="block w-full text-left px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">Mode Gelap</button>
      <button id="btnLight" class="block w-full text-left px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">Mode Terang</button>
    </div>
  </div>




  <!-- <main class="pt-24 md:ml-64 p-6">
    <h1 class="text-2xl font-bold">Dashboard</h1>
    <p class="mt-4">Selamat datang di DPMPTSP!</p>
  </main> -->

  <!-- JS untuk toggle -->
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebar-toggle');
    const closeBtn = document.getElementById('sidebar-close');
    const overlay = document.getElementById('sidebar-overlay');

    toggleBtn?.addEventListener('click', () => {
      sidebar.classList.remove('-translate-x-full');
      overlay.classList.remove('hidden');
    });

    closeBtn?.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
    });

    overlay?.addEventListener('click', () => {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
    });
  </script>

  <script>
    // Restore theme on page load
    if (localStorage.getItem("theme") === "dark") {
      document.documentElement.classList.add("dark");
    } else {
      document.documentElement.classList.remove("dark");
    }

    const accessBtn = document.getElementById("accessBtn");
    const accessPopup = document.getElementById("accessPopup");
    const btnDark = document.getElementById("btnDark");
    const btnLight = document.getElementById("btnLight");

    accessBtn?.addEventListener("click", () => {
      accessPopup.classList.toggle("hidden");
    });

    btnDark?.addEventListener("click", () => {
      document.documentElement.classList.add("dark");
      localStorage.setItem("theme", "dark");
    });

    btnLight?.addEventListener("click", () => {
      document.documentElement.classList.remove("dark");
      localStorage.setItem("theme", "light");
    });
  </script>



<?php include_once __DIR__ . '/../includes/footer.php'; ?>