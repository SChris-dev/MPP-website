<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';


$limitOptions = [5, 10, 25, 50];
$limit = isset($_GET['limit']) && in_array((int)$_GET['limit'], $limitOptions) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sortBy = in_array($_GET['sortBy'] ?? '', ['nama_pegawai', 'jenis_kelamin', 'jabatan']) ? $_GET['sortBy'] : 'nama_pegawai';
$sortDir = ($_GET['sortDir'] ?? '') === 'desc' ? 'desc' : 'asc';

$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM pegawai WHERE nama_pegawai LIKE :search OR jabatan LIKE :search ORDER BY $sortBy $sortDir LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', "%$search%");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$pegawaiList = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
  foreach ($pegawaiList as $pegawai): ?>
    <tr>
      <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($pegawai['nama_pegawai']) ?></td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['jenis_kelamin']) ?></td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['tempat_lahir']) ?></td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['tanggal_lahir']) ?></td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['jabatan']) ?></td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['no_hp']) ?></td>
      <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['alamat']) ?></td>
      <td>
        <button onclick='openEditModal(<?= json_encode($pegawai) ?>)' class="rounded bg-yellow-500 px-3 py-1 text-xs font-medium text-white hover:bg-yellow-600">
          <i class="fa fa-pencil"></i>
        </button>
        <button onclick="confirmDelete(<?= $pegawai['id'] ?>)" class="rounded bg-red-500 px-3 py-1 text-xs font-medium text-white hover:bg-red-600">
          <i class="fa fa-trash"></i>
        </button>
      </td>
    </tr>
  <?php endforeach;
  exit;
}

// $stmt = $pdo->query('SELECT * FROM pegawai ORDER BY id DESC');
// $pegawaiList = $stmt->fetchAll();
?>

<?php include_once __DIR__ . '/includes/header.php'; ?>
<?php include_once __DIR__ . '/includes/sidebar.php'; ?>

  <main class="pt-24 md:ml-64 p-6">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-3xl font-semibold">Data Pegawai</h2>
    </div>
    <div class="flex justify-end mb-4">
      <button id="btnCreate"
        class="rounded bg-customText px-4 py-2 text-sm font-semibold text-white hover:bg-[#B91C1C]">
        + Tambah Pegawai
      </button>
    </div>

    <div class="flex justify-between items-center mb-4">
      <input type="text" id="searchInput" placeholder="Cari nama atau jabatan..."
        class="px-3 py-1 border rounded-lg dark:bg-gray-800 dark:text-white" />

      <select id="limitSelect" class="ml-2 px-3 py-1 border rounded-lg dark:bg-gray-800 dark:text-white">
        <option value="5">5</option>
        <option value="10" selected>10</option>
        <option value="25">25</option>
        <option value="50">50</option>
      </select>
    </div>



    <div class="w-full overflow-x-auto">
      <table class="w-full border-collapse table-auto min-w-[800px]">
        <thead>
          <tr class="bg-gray-200 dark:bg-[#B91C1C] border-hidden">
            <!-- <th class="border px-4 py-2">ID</th> -->
            <th class="border px-4 py-2 sortable cursor-pointer" data-field="nama_pegawai">
              Nama <span class="sort-indicator"></span>
            </th>
            <th class="border px-4 py-2 sortable cursor-pointer" data-field="jenis_kelamin">
              Jenis Kelamin <span class="sort-indicator"></span>
            </th>
            <th class="border px-4 py-2">Tempat Lahir</th>
            <th class="border px-4 py-2">Tanggal Lahir</th>
            <th class="border px-4 py-2 sortable cursor-pointer" data-field="jabatan">
              Jabatan <span class="sort-indicator"></span>
            </th>
            <th class="border px-4 py-2">No. HP</th>
            <th class="border px-4 py-2">Alamat</th>
            <th class="border px-4 py-2">Action</th>
  
          </tr>
        </thead>
        <!-- <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($pegawai['id']) ?></td> -->
        <tbody>
          <?php if (!$pegawaiList): ?>
            <tr>
              <td colspan="6" class="text-center py-4">Tidak ada data pegawai.</td>
            </tr>
          <?php else: ?>
            <!-- <?php foreach ($pegawaiList as $pegawai): ?>
              <tr>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($pegawai['nama_pegawai']) ?></td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['jenis_kelamin']) ?></td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['tempat_lahir']) ?></td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['tanggal_lahir']) ?></td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['jabatan']) ?></td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['no_hp']) ?></td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-300"><?= htmlspecialchars($pegawai['alamat']) ?></td>
                <td class="whitespace-nowrap px-4 py-2 space-x-2">
                  <button id="btnEdit" onclick='openEditModal(<?= json_encode($pegawai) ?>)' 
                    class="rounded bg-yellow-500 px-3 py-1 text-xs font-medium text-white hover:bg-yellow-600">
                    Edit
                  </button>
  
                  <button onclick="confirmDelete(<?= $pegawai['id'] ?>)"
                    class="rounded bg-red-500 px-3 py-1 text-xs font-medium text-white hover:bg-red-600">
                    Hapus
                  </button>
                </td>
              </tr>
            <?php endforeach; ?> -->
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <!-- <p class="text-sm text-gray-700 dark:text-white mt-2">
      Menampilkan <?= ($offset + 1) ?> - <?= min($offset + $limit, $totalRows) ?> dari <?= $totalRows ?> entri
    </p> -->
  </main>

  <!-- Modal Create -->
  <div id="modalCreate" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 border border-black dark:border-red-500 rounded-2xl shadow-lg max-w-3xl w-full p-6 md:p-8">
      <h2 class="text-xl font-bold mb-6 text-gray-800 dark:text-white">Tambah Data Pegawai</h2>

      <form action="./actions/pegawai_add.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

        <!-- Nama -->
        <div>
          <label for="nama" class="block mb-1 font-medium text-gray-800 dark:text-white">Nama</label>
          <input type="text" id="nama" name="nama_pegawai" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
        </div>

        <!-- Jenis Kelamin -->
        <div>
          <label for="jenis_kelamin" class="block mb-1 font-medium text-gray-800 dark:text-white">Jenis Kelamin</label>
          <select id="jenis_kelamin" name="jenis_kelamin" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
            <option value="0" disabled selected>Pilih Jenis Kelamin</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>

        <!-- Tempat Lahir -->
        <div>
          <label for="tempat_lahir" class="block mb-1 font-medium text-gray-800 dark:text-white">Tempat Lahir</label>
          <input type="text" id="tempat_lahir" name="tempat_lahir" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
        </div>

        <!-- Tanggal Lahir -->
        <div>
          <label for="tanggal_lahir" class="block mb-1 font-medium text-gray-800 dark:text-white">Tanggal Lahir</label>
          <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
        </div>

        <!-- Jabatan -->
        <div>
          <label for="jabatan" class="block mb-1 font-medium text-gray-800 dark:text-white">Jabatan</label>
          <input type="text" id="jabatan" name="jabatan" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
        </div>

        <!-- No. HP -->
        <div>
          <label for="no_hp" class="block mb-1 font-medium text-gray-800 dark:text-white">No. HP</label>
          <input type="text" id="no_hp" name="no_hp" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required>
        </div>

        <!-- Alamat -->
        <div class="md:col-span-2">
          <label for="alamat" class="block mb-1 font-medium text-gray-800 dark:text-white">Alamat</label>
          <textarea id="alamat" name="alamat" rows="3" class="w-full px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white" required></textarea>
        </div>

        <!-- Buttons -->
        <div class="md:col-span-2 flex justify-end space-x-4 mt-4">
          <button type="submit" class="bg-black text-white dark:bg-white dark:text-black px-6 py-2 rounded-lg font-medium transition hover:bg-white hover:text-red-600 hover:border hover:border-red-500 hover:shadow-red-400">Simpan</button>
          <button type="button" id="btnCreateCancel" class="bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition hover:bg-white hover:text-red-600 hover:border hover:border-red-500 hover:shadow-red-400">Batal</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal Edit -->
  <div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white dark:bg-gray-800 border border-black dark:border-red-500 rounded-2xl shadow-lg max-w-4xl w-full mx-4 p-6 md:p-8 relative">
      <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Edit Data Pegawai</h3>
      <form action="./actions/pegawai_edit.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <input type="hidden" name="id" readonly class="w-full border px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-white cursor-not-allowed" />

        <!-- Nama Lengkap -->
        <div>
          <label class="block mb-1 font-medium text-gray-800 dark:text-white">Nama Lengkap</label>
          <input type="text" name="nama_pegawai" class="w-full border px-3 py-2 rounded-lg dark:bg-gray-700 dark:text-white" required />
        </div>

        <!-- Jenis Kelamin -->
        <div>
          <label class="block mb-1 font-medium text-gray-800 dark:text-white">Jenis Kelamin</label>
          <select name="jenis_kelamin" class="w-full border px-3 py-2 rounded-lg dark:bg-gray-700 dark:text-white" required>
            <option value="0" disabled>Pilih Jenis Kelamin</option>
            <option value="L" <?= $pegawai['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
            <option value="P" <?= $pegawai['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
          </select>
        </div>

        <!-- Tempat Lahir -->
        <div>
          <label class="block mb-1 font-medium text-gray-800 dark:text-white">Tempat Lahir</label>
          <input type="text" name="tempat_lahir" class="w-full border px-3 py-2 rounded-lg dark:bg-gray-700 dark:text-white" required />
        </div>

        <!-- Tanggal Lahir -->
        <div>
          <label class="block mb-1 font-medium text-gray-800 dark:text-white">Tanggal Lahir</label>
          <input type="date" name="tanggal_lahir" class="w-full border px-3 py-2 rounded-lg dark:bg-gray-700 dark:text-white" required />
        </div>

        <!-- Jabatan -->
        <div>
          <label class="block mb-1 font-medium text-gray-800 dark:text-white">Jabatan</label>
          <input type="text" name="jabatan" class="w-full border px-3 py-2 rounded-lg dark:bg-gray-700 dark:text-white" required />
        </div>

        <!-- No. HP -->
        <div>
          <label class="block mb-1 font-medium text-gray-800 dark:text-white">No. HP</label>
          <input type="text" name="no_hp" class="w-full border px-3 py-2 rounded-lg dark:bg-gray-700 dark:text-white" required />
        </div>

        <!-- Alamat -->
        <div class="md:col-span-2">
          <label class="block mb-1 font-medium text-gray-800 dark:text-white">Alamat</label>
          <textarea name="alamat" rows="3" class="w-full border px-3 py-2 rounded-lg dark:bg-gray-700 dark:text-white" required></textarea>
        </div>

        <!-- Buttons -->
        <div class="md:col-span-2 flex space-x-4 mt-4 justify-end">
          <button type="submit" class="bg-black text-white dark:bg-white dark:text-black px-6 py-2 rounded-lg font-medium transition hover:bg-white hover:text-red-600 hover:border hover:border-red-500 hover:shadow-red-400">
            Simpan
          </button>
          <button type="button" id="btnEditCancel" class="bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition hover:bg-white hover:text-red-600 hover:border hover:border-red-500 hover:shadow-red-400">
            Batal
          </button>
        </div>
      </form>
    </div>
  </div>


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
    // Create Modal
    const btnCreate = document.getElementById("btnCreate");
    const modalCreate = document.getElementById("modalCreate");
    const btnCreateCancel = document.getElementById("btnCreateCancel");

    btnCreate.addEventListener("click", () => modalCreate.classList.remove("hidden"));
    btnCreateCancel.addEventListener("click", () => modalCreate.classList.add("hidden"));

    // Modal edit
    const modalEdit = document.getElementById("modalEdit");
    const btnEditCancel = document.getElementById("btnEditCancel");

    // example function to open modal and populate fields (you'll fill data dynamically)
    function openEditModal(data) {
      modalEdit.classList.remove("hidden");

      // Fill form inputs with existing data
      modalEdit.querySelector('input[name="id"]').value = data.id || '';
      modalEdit.querySelector('input[name="nama_pegawai"]').value = data.nama_pegawai || '';
      modalEdit.querySelector('input[name="tempat_lahir"]').value = data.tempat_lahir || '';
      modalEdit.querySelector('input[name="tanggal_lahir"]').value = data.tanggal_lahir || '';
      modalEdit.querySelector('input[name="jabatan"]').value = data.jabatan || '';
      modalEdit.querySelector('input[name="no_hp"]').value = data.no_hp || '';
      modalEdit.querySelector('textarea[name="alamat"]').value = data.alamat || '';
    }

    btnEditCancel.addEventListener("click", () => modalEdit.classList.add("hidden"));


    // Confirm delete?
    function confirmDelete(id) {
      if (confirm("Yakin ingin menghapus pegawai ini?")) {
        window.location.href = `./actions/pegawai_delete.php?id=${id}`;
      }
    }

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

  <!-- JS untuk sidebar -->
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

<!-- JS AJAX -->
  <script>
  let currentPage = 1;
  let currentLimit = 10;
  let currentSearch = '';
  let currentSortBy = 'nama_pegawai';
  let currentSortDir = 'asc';

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

  // Event Listeners
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


<?php include_once __DIR__ . '/includes/footer.php'; ?>