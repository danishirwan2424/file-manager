<?php
$targetDir = "C:/xampp/htdocs/MyMCE/file-web/CDN/";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the name of the new folder from the POST request
    $folderName = $_POST['folderName'];
    $newFolderPath = $targetDir . $folderName;

    // Check if folder already exists
    if (file_exists($newFolderPath)) {
        echo json_encode(['success' => false, 'message' => 'Folder already exists.']);
        exit;
    }

    // Create the new folder
    if (mkdir($newFolderPath, 0777, true)) {
        echo json_encode(['success' => true, 'message' => 'Folder created successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error creating folder.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}