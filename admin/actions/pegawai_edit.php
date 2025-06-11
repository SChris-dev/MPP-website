<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

$id = $_POST['id'] ?? null;
if (!$id) {
    header('Location: ./../pegawai.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM pegawai WHERE id = ?');
$stmt->execute([$id]);
$pegawai = $stmt->fetch();

if (!$pegawai) {
    header('Location: ./../pegawai.php');
    exit;
}

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
        $stmt = $pdo->prepare("UPDATE pegawai SET 
            nama_pegawai=?, jenis_kelamin=?, tempat_lahir=?, tanggal_lahir=?, jabatan=?, no_hp=?, alamat=?
            WHERE id=?");
        $stmt->execute([$nama, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $jabatan, $no_hp, $alamat, $id]);
        header('Location: ./../pegawai.php');
        exit;
    }
}
?>
