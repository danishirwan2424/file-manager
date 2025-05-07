<?php
$baseDir = "C:/xampp/htdocs/MyMCE/file-web/CDN/";

$folderPath = isset($_GET['folderPath']) ? $_GET['folderPath'] : '';
$targetDir = $baseDir . $folderPath;

if (!file_exists($targetDir)) {
    echo json_encode(['success' => false, 'message' => 'Directory not found']);
    exit;
}

$files = array_diff(scandir($targetDir), ['..', '.']);

$fileList = [];
foreach ($files as $file) {
    $filePath = $targetDir . '/' . $file;
    if (is_dir($filePath)) {
        $fileList[] = ['name' => $file, 'type' => 'folder'];
    } else {
        $fileType = mime_content_type($filePath);
        $fileList[] = ['name' => $file, 'type' => $fileType];
    }
}

echo json_encode(['success' => true, 'files' => $fileList]);