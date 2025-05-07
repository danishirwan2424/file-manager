<?php
$filePath = $_GET['file'];
$fullPath = __DIR__ . '/CDN/' . $filePath;

// Ensure the file exists and is a .txt file
if (file_exists($fullPath) && pathinfo($fullPath, PATHINFO_EXTENSION) === 'txt') {
    // Set headers for file download
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
    readfile($fullPath);
} else {
    echo "File not found.";
}