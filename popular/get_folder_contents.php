<?php

// file: get_folder_contents.php

$folderPath = $_GET['folder']; // Example of getting folder path
$files = [];

// Ensure the folder exists
if (is_dir($folderPath)) {
    $dir = opendir($folderPath);
    while (($file = readdir($dir)) !== false) {
        if ($file !== '.' && $file !== '..') {
            $filePath = $folderPath . '/' . $file;
            $isFolder = is_dir($filePath);
            $files[] = ['name' => $file, 'url' => $filePath, 'isFolder' => $isFolder];
        }
    }
    closedir($dir);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Folder not found']);
    exit();
}

echo json_encode($files);
