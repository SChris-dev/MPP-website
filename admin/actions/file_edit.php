<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $filename = $_POST['filename'] ?? null;
    $oldPath = $_POST['old_path'] ?? null;

    if (!$id || !$filename) {
        die("Invalid request.");
    }

    $uploadDir = __DIR__ . '/../../public/downloads/';
    $newPath = $oldPath;

    // Handle new file upload (optional)
    if (!empty($_FILES['new_file']['name'])) {
        $originalName = $_FILES['new_file']['name'];
        $tmpName = $_FILES['new_file']['tmp_name'];
        $newFilename = uniqid() . '_' . basename($originalName);
        $targetPath = $uploadDir . $newFilename;
        $publicPath = 'downloads/' . $newFilename;

        // Delete old file
        $oldFilePath = __DIR__ . '/../../public/' . $oldPath;
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }

        // Move new file
        if (move_uploaded_file($tmpName, $targetPath)) {
            $newPath = $publicPath;
        } else {
            die("Gagal upload file baru.");
        }
    }

    // Update DB
    $stmt = $pdo->prepare("UPDATE files SET filename = ?, path = ? WHERE id = ?");
    $stmt->execute([$filename, $newPath, $id]);

    header("Location: ../files.php");
    exit;
} else {
    die("Invalid method.");
}
