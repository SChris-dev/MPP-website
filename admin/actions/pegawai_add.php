<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_pegawai'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $tempat_lahir = $_POST['tempat_lahir'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $jabatan = $_POST['jabatan'] ?? '';
    $no_hp = $_POST['no_hp'] ?? '';
    $alamat = $_POST['alamat'] ?? '';

    if (!$nama || !$jenis_kelamin) {
        $error = 'Nama dan jenis kelamin wajib diisi.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO pegawai 
          (nama_pegawai, jenis_kelamin, tempat_lahir, tanggal_lahir, jabatan, no_hp, alamat) 
          VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $jabatan, $no_hp, $alamat]);
        header('Location: ./../pegawai.php');
        exit;
    }
}
?>
<!-- 
<div class="max-w-md mx-auto p-6">
  <h1 class="text-2xl font-bold mb-6">Tambah Pegawai</h1>
  <?php if ($error): ?>
    <p class="mb-4 text-red-600"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form method="POST" class="space-y-4">
    <div>
      <label class="block mb-1 font-semibold">Nama Pegawai</label>
      <input type="text" name="nama_pegawai" required class="w-full border border-gray-300 rounded px-3 py-2" />
    </div>

    <div>
      <label class="block mb-1 font-semibold">Jenis Kelamin</label>
      <select name="jenis_kelamin" required class="w-full border border-gray-300 rounded px-3 py-2">
        <option value="">-- Pilih --</option>
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
      </select>
    </div>

    <div>
      <label class="block mb-1 font-semibold">Tempat Lahir</label>
      <input type="text" name="tempat_lahir" class="w-full border border-gray-300 rounded px-3 py-2" />
    </div>

    <div>
      <label class="block mb-1 font-semibold">Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir" class="w-full border border-gray-300 rounded px-3 py-2" />
    </div>

    <div>
      <label class="block mb-1 font-semibold">Jabatan</label>
      <input type="text" name="jabatan" class="w-full border border-gray-300 rounded px-3 py-2" />
    </div>

    <div>
      <label class="block mb-1 font-semibold">No HP</label>
      <input type="text" name="no_hp" class="w-full border border-gray-300 rounded px-3 py-2" />
    </div>

    <div>
      <label class="block mb-1 font-semibold">Alamat</label>
      <textarea name="alamat" rows="3" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
    </div>

    <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700 transition">Simpan</button>
  </form>
</div> -->
