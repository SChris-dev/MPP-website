<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: ./../pegawai.php');
    exit;
}

$stmt = $pdo->prepare('DELETE FROM pegawai WHERE id = ?');
$stmt->execute([$id]);

header('Location: ./../pegawai.php');
exit;
