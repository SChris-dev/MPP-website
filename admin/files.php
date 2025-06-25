<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';

// $stmt = $pdo->query("SELECT * FROM files ORDER BY uploaded_at DESC");
// $fileList = $stmt->fetchAll(PDO::FETCH_ASSOC);

$limitOptions = [5, 10, 25, 50];
$limit = isset($_GET['limit']) && in_array((int)$_GET['limit'], $limitOptions) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sortBy = in_array($_GET['sortBy'] ?? '', ['filename', 'path', 'uploaded_at']) ? $_GET['sortBy'] : 'uploaded_at';
$sortDir = ($_GET['sortDir'] ?? '') === 'asc' ? 'asc' : 'desc';

$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM files WHERE filename LIKE :search OR path LIKE :search ORDER BY $sortBy $sortDir LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', "%$search%");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$fileList = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
  foreach ($fileList as $file): ?>
    <tr>
      <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($file['filename']) ?></td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($file['path']) ?></td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars(date('d M Y H:i', strtotime($file['uploaded_at']))) ?></td>
      <td class="whitespace-nowrap px-4 py-2 text-right space-x-2">
        <button onclick='openEditModal(<?= json_encode($file) ?>)' class="rounded bg-yellow-500 px-3 py-1 text-xs font-medium text-white hover:bg-yellow-600">
          <i class="fa fa-pencil"></i>
        </button>
        <button onclick="confirmDelete(<?= $file['id'] ?>)" class="rounded bg-red-500 px-3 py-1 text-xs font-medium text-white hover:bg-red-600">
          <i class="fa fa-trash"></i>
        </button>
      </td>
    </tr>
  <?php endforeach;
  exit;
}

?>

<?php include_once __DIR__ . '/includes/header.php'; ?>
<?php include_once __DIR__ . '/includes/sidebar.php'; ?>

<main class="pt-24 md:ml-64 p-6">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-semibold">Data File</h2>
  </div>

  <div class="flex justify-end mb-4">
    <button id="btnUpload"
        class="rounded bg-customText px-4 py-2 text-sm font-semibold text-white hover:bg-[#B91C1C]">
        + Upload File
    </button>
  </div>

  <div class="flex justify-between items-center mb-4">
      <input type="text" id="searchInput" placeholder="Cari nama file..."
          class="px-3 py-1 border rounded-lg dark:bg-gray-800 dark:text-white" />  
      <select id="limitSelect" class="ml-2 px-3 py-1 border rounded-lg dark:bg-gray-800 dark:text-white">
          <option value="5">5</option>
          <option value="10" selected>10</option>
          <option value="25">25</option>
          <option value="50">50</option>
      </select>
  </div>

  <div class="w-full overflow-x-auto">
    <table class="w-full border-collapse table-auto min-w-[600px]">
        <thead>
            <tr class="bg-gray-200 dark:bg-[#B91C1C] border-hidden">
                <th class="border px-4 py-2 text-left sortable cursor-pointer" data-field="filename">Filename</th>
                <th class="border px-4 py-2 text-left sortable cursor-pointer" data-field="path">Path</th>
                <th class="border px-4 py-2 text-left sortable cursor-pointer" data-field="uploaded_at">Uploaded At</th>
                <th class="border px-4 py-2 text-left">Aksi</th>
            </tr>
        </thead>

      <tbody>
        <?php if (!$fileList): ?>
          <tr>
            <td colspan="4" class="text-center py-4">Belum ada file.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($fileList as $file): ?>
            <tr>
              <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white">
                <?= htmlspecialchars($file['filename']) ?>
              </td>
              <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300">
                <?= htmlspecialchars($file['path']) ?>
              </td>
              <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300">
                <?= htmlspecialchars(date('d M Y H:i', strtotime($file['uploaded_at']))) ?>
              </td>
              <td class="whitespace-nowrap px-4 py-2 space-x-2">
                <button onclick='openEditModal(<?= json_encode($file) ?>)' class="rounded bg-yellow-500 px-3 py-1 text-xs font-medium text-white hover:bg-yellow-600">
                    <i class="fa fa-pencil"></i>
                </button>

                <button onclick="confirmDelete(<?= $file['id'] ?>)" class="rounded bg-red-500 px-3 py-1 text-xs font-medium text-white hover:bg-red-600">
                    <i class="fa fa-trash"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Upload Modal -->
    <div id="modalUpload" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 border border-black dark:border-red-500 rounded-2xl shadow-lg max-w-md w-full p-6">
            <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-white">Upload File</h2>

            <form action="./actions/file_add.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="file" class="block mb-1 font-medium text-gray-800 dark:text-white">Pilih File</label>
                <input type="file" name="file" id="file" required
                class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">
            </div>

            <div class="flex justify-end space-x-4">
                <button type="submit"
                class="bg-black text-white dark:bg-white dark:text-black px-6 py-2 rounded-lg font-medium transition hover:bg-white hover:text-red-600 hover:border hover:border-red-500 hover:shadow-red-400">
                Upload
                </button>
                <button type="button" id="btnUploadCancel"
                class="bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition hover:bg-white hover:text-red-600 hover:border hover:border-red-500 hover:shadow-red-400">
                Batal
                </button>
            </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 border border-black dark:border-red-500 rounded-2xl shadow-lg max-w-md w-full p-6">
            <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-white">Edit File</h2>

            <form action="./actions/file_edit.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="id" />
            <input type="hidden" name="old_path" />

            <div>
                <label for="filename" class="block mb-1 font-medium text-gray-800 dark:text-white">Nama File</label>
                <input type="text" name="filename" required
                class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" />
            </div>

            <div>
                <label class="block mb-1 font-medium text-gray-800 dark:text-white">Ganti File (opsional)</label>
                <input type="file" name="new_file" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" />
            </div>

            <div class="flex justify-end space-x-4">
                <button type="submit"
                class="bg-black text-white dark:bg-white dark:text-black px-6 py-2 rounded-lg font-medium transition hover:bg-white hover:text-red-600 hover:border hover:border-red-500 hover:shadow-red-400">
                Simpan
                </button>
                <button type="button" id="btnEditCancel"
                class="bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition hover:bg-white hover:text-red-600 hover:border hover:border-red-500 hover:shadow-red-400">
                Batal
                </button>
            </div>
            </form>
        </div>
    </div>
  </div>
</main>

<!-- Tombol Aksesibilitas -->
  <div class="fixed bottom-6 right-6 z-50">
    <button id="accessBtn" class="w-14 h-14 rounded-full bg-red-600 text-white shadow-lg flex items-center justify-center text-xl">
      ⚙️
    </button>
    <div id="accessPopup" class="hidden mt-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-red-500 rounded-lg shadow-lg p-4 space-y-2">
      <button id="btnDark" class="block w-full text-left px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">Mode Gelap</button>
      <button id="btnLight" class="block w-full text-left px-4 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">Mode Terang</button>
    </div>
  </div>

<script>
    // upload
    const btnUpload = document.getElementById("btnUpload");
    const modalUpload = document.getElementById("modalUpload");
    const btnUploadCancel = document.getElementById("btnUploadCancel");

    btnUpload?.addEventListener("click", () => modalUpload.classList.remove("hidden"));
    btnUploadCancel?.addEventListener("click", () => modalUpload.classList.add("hidden"));

    // edit
    function openEditModal(file) {
        const modal = document.getElementById("modalEdit");
        modal.classList.remove("hidden");

        modal.querySelector('input[name="id"]').value = file.id;
        modal.querySelector('input[name="filename"]').value = file.filename;
        modal.querySelector('input[name="old_path"]').value = file.path;
    }

    document.getElementById("btnEditCancel").addEventListener("click", () => {
        document.getElementById("modalEdit").classList.add("hidden");
    });

    // delete
    function confirmDelete(id) {
        if (confirm("Yakin ingin menghapus file ini?")) {
            window.location.href = `./actions/file_delete.php?id=${id}`;
        }
    }
</script>

<script>
    let currentPage = 1;
    let currentLimit = 10;
    let currentSearch = '';
    let currentSortBy = 'uploaded_at';
    let currentSortDir = 'desc';

    function loadTableData() {
    const params = new URLSearchParams({
        ajax: 'true',
        page: currentPage,
        limit: currentLimit,
        search: currentSearch,
        sortBy: currentSortBy,
        sortDir: currentSortDir,
    });

    fetch('<?= $_SERVER['PHP_SELF'] ?>?' + params.toString())
        .then(res => res.text())
        .then(html => {
        document.querySelector("tbody").innerHTML = html;
        });
    }

    document.getElementById("limitSelect").addEventListener("change", e => {
    currentLimit = parseInt(e.target.value);
    currentPage = 1;
    loadTableData();
    });

    document.getElementById("searchInput").addEventListener("input", e => {
    currentSearch = e.target.value;
    currentPage = 1;
    loadTableData();
    });

    document.querySelectorAll("th.sortable").forEach(th => {
    th.addEventListener("click", () => {
        const sortField = th.dataset.field;
        if (currentSortBy === sortField) {
        currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
        } else {
        currentSortBy = sortField;
        currentSortDir = 'asc';
        }
        loadTableData();
    });
    });

    loadTableData(); // initial load
</script>

<script>
    // Aksesibilitas
    const accessBtn = document.getElementById("accessBtn");
    const accessPopup = document.getElementById("accessPopup");
    const btnDark = document.getElementById("btnDark");
    const btnLight = document.getElementById("btnLight");

    accessBtn.addEventListener("click", () => {
      accessPopup.classList.toggle("hidden");
    });

    btnDark.addEventListener("click", () => {
      document.documentElement.classList.add("dark");
      localStorage.setItem("theme", "dark");
    });

    btnLight.addEventListener("click", () => {
      document.documentElement.classList.remove("dark");
      localStorage.setItem("theme", "light");
    });
</script>

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


<?php include_once __DIR__ . '/../includes/footer.php'; ?>
