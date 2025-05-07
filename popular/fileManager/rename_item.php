<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define the directory path
$directory = 'C:/xampp/htdocs/MyMCE/file-web/CDN/';

// Retrieve and sanitize input data
$oldPath = isset($_POST['oldPath']) ? $_POST['oldPath'] : '';
$newName = isset($_POST['newName']) ? $_POST['newName'] : '';
$isFolder = isset($_POST['isFolder']) ? $_POST['isFolder'] === 'true' : false;

// Validate input
if (empty($oldPath) || empty($newName)) {
    echo json_encode(['success' => false, 'message' => 'Old path or new name is missing']);
    exit;
}

// Define full paths
$oldPath = realpath($directory . $oldPath);
$newPath = dirname($oldPath) . '/' . $newName;

// Check if old path exists
if (!file_exists($oldPath)) {
    echo json_encode(['success' => false, 'message' => 'The old path does not exist']);
    exit;
}

// Check if new path already exists
if (file_exists($newPath)) {
    echo json_encode(['success' => false, 'message' => 'A file or directory with the new name already exists']);
    exit;
}

// Try renaming the file or directory
if (rename($oldPath, $newPath)) {
    echo json_encode(['success' => true, 'message' => 'Item renamed successfully']);
} else {
    $error = error_get_last();
    error_log("Rename failed: " . print_r($error, true));
    echo json_encode(['success' => false, 'message' => 'Failed to rename item']);
}