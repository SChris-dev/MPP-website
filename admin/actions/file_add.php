<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadDir = __DIR__ . '/../../public/downloads/';
    $originalName = $_FILES['file']['name'];
    $tmpName = $_FILES['file']['tmp_name'];

    // Optional: check if folder exists
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Rename the file to avoid conflicts
    $newName = uniqid() . '_' . basename($originalName);
    $targetPath = $uploadDir . $newName;
    $publicPath = 'downloads/' . $newName;

    if (move_uploaded_file($tmpName, $targetPath)) {
        // Save into DB
        $stmt = $pdo->prepare("INSERT INTO files (filename, path) VALUES (?, ?)");
        $stmt->execute([$originalName, $publicPath]);

        header("Location: ../files.php");
        exit;
    } else {
        echo "Upload gagal.";
    }
} else {
    echo "Invalid request.";
}
