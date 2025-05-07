<?php
$targetDir = "C:/xampp/htdocs/MyMCE/file-web/CDN/";

if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}
// Get the folder name from the formData
$folderName = isset($_POST['folderName']) ? $_POST['folderName'] : '';

if ($folderName) {
    $targetDir .= $folderName . '/';
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    foreach ($_FILES['files']['name'] as $key => $name) {
        $relativePath = $_FILES['files']['name'][$key];
        $filePath = $targetDir . $relativePath;
        $fileDir = dirname($filePath);

        if (!file_exists($fileDir)) {
            mkdir($fileDir, 0755, true); // Create nested directories if they don't exist
        }

        if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $filePath)) {
            // File moved successfully
        } else {
            echo json_encode(['success' => false, 'message' => 'Error moving file: ' . $name]);
            exit;
        }
    }

    echo json_encode(['success' => true, 'message' => 'Folder uploaded successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'No folder name provided.']);
}