<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Get file info from DB
    $stmt = $pdo->prepare("SELECT * FROM files WHERE id = ?");
    $stmt->execute([$id]);
    $file = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($file) {
        $filePath = __DIR__ . '/../../public/' . $file['path'];

        // Delete file from disk
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete record from DB
        $deleteStmt = $pdo->prepare("DELETE FROM files WHERE id = ?");
        $deleteStmt->execute([$id]);
    }

    header("Location: ../files.php");
    exit;
} else {
    echo "Invalid request.";
}
