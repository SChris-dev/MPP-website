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
        <a href="./pegawai.php" class="block px-5 py-3 hover:bg-customText">Data Pegawai</a>
      </li>
      <li>
        <a href="./files.php" class="block px-5 py-3 hover:bg-customText">Data File</a>
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
