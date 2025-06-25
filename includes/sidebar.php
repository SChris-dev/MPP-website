<?php
require_once __DIR__ . '/db.php';

?>
<!-- sidebar.php -->
<aside id="sidebar"
       class="fixed top-0 left-0 w-64 bg-customBg text-white min-h-screen shadow-lg transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50">
  
  <!-- Close button on mobile -->
  <div class="md:hidden flex justify-end px-4 py-2">
    <button id="sidebar-close" class="text-white focus:outline-none">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <div class="p-5 text-2l font-bold border-b border-red-700">
      DINAS PENANAMAN MODAL<br />
      PELAYANAN TERPADU SATU PINTU
  </div>

  <nav class="mt-5">
    <ul class="space-y-1">
      <li>
        <a href="#" class="block px-5 py-3 hover:bg-customText">Dashboard</a>
      </li>

      <!-- Kategori Kepegawaian -->
      <li>
        <div class="px-5 py-3 font-semibold text-sm uppercase text-gray-400">Kepegawaian</div>
        <ul class="space-y-1 ml-4 text-sm">
          <li class="relative group">
            <div class="flex justify-between items-center px-5 py-2 hover:bg-customText rounded cursor-pointer">
              Cuti <i class="fa-solid fa-chevron-right ml-2 text-xs"></i>
            </div>

            <!-- Dropdown menu -->
            <ul class="absolute left-full top-0 w-60 bg-white text-black dark:bg-[#1f2937] dark:text-white shadow-lg rounded hidden group-hover:block z-10">
              <li>
                <a href="./../public/downloads/Form Cuti Sakit Unduh.docx" download class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                  Cuti Sakit <i class="fa-solid fa-download"></i>
                </a>
              </li>
              <li>
                <a href="./../public/downloads/Form Cuti Alasan Penting Pelaksana Unduh.docx" download class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                  Cuti Alasan Penting <i class="fa-solid fa-download"></i>
                </a>
              </li>
              <li>
                <a href="./../public/downloads/Form Cuti Besar Unduh.docx" download class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                  Cuti Besar <i class="fa-solid fa-download"></i>
                </a>
              </li>
              <li>
                <a href="./../public/downloads/Form Cuti Tahunan Unduh.docx" download class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                  Cuti Tahunan <i class="fa-solid fa-download"></i>
                </a>
              </li>
              <li>
                <a href="./../public/downloads/Form Cuti Melahirkan unduh.docx" download class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                  Cuti Melahirkan <i class="fa-solid fa-download"></i>
                </a>
              </li>
            </ul>
          </li>

          <li>
            <div class="max-h-64 overflow-y-auto pr-1">
              <?php
                try {
                  $stmt = $pdo->query("SELECT filename, path FROM files ORDER BY uploaded_at ASC LIMIT 20");
                  $files = $stmt->fetchAll();

                  foreach ($files as $file): ?>
                    <li>
                      <a href="./../public/<?= htmlspecialchars($file['path']) ?>" download
                        class="flex justify-between items-center px-5 py-2 hover:bg-customText rounded">
                        <?= htmlspecialchars($file['filename']) ?>
                        <i class="fa-solid fa-download"></i>
                      </a>
                    </li>
                  <?php endforeach;
                } catch (PDOException $e) {
                  echo '<li class="px-5 py-2 text-red-500">Error loading files</li>';
                }
              ?>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </nav>

  <footer class="border-customText border-t text-white text-center py-3 w-full fixed bottom-0 left-0 z-10">
    <h4 class="text-base font-medium text-xs">
      &copy; <?php echo date('Y'); ?> DINAS PENANAMAN MODAL PELAYANAN TERPADU SATU PINTU
    </h4>
  </footer>
</aside>

<!-- Overlay for mobile -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 md:hidden"></div>
