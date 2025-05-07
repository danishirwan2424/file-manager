<?php
header('Content-Type: application/json');

$targetDir = "C:/xampp/htdocs/MyMCE/file-web/CDN/";

if (!file_exists($targetDir)) {
    if (!mkdir($targetDir, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'Failed to create target directory.']);
        exit;
    }
}

if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = basename($file['name']);
    $fileTmpName = $file['tmp_name'];
    $targetFilePath = $targetDir . $fileName;

    if ($file['error'] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($fileTmpName, $targetFilePath)) {
            chmod($targetFilePath, 0644);
            echo json_encode(['success' => true, 'message' => 'File uploaded successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error moving file to the target directory.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error during file upload. Error code: ' . $file['error']]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
}
