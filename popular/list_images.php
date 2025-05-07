<?php
header('Content-Type: application/json');

// Dynamically determine the base directory and URL
$baseDir = $_SERVER['DOCUMENT_ROOT'] . '/MyMCE/file-web/CDN/';
$baseURL = 'http://' . $_SERVER['HTTP_HOST'] . '/MyMCE/file-web/CDN/';

// Helper function to generate a URL for the file
function generateURL($baseURL, $relativePath) {
    return $baseURL . str_replace(' ', '%20', $relativePath);
}

// Get the directory from the query parameter
$directory = isset($_GET['folderPath']) ? $baseDir . $_GET['folderPath'] : $baseDir;

// Ensure the directory exists
if (!is_dir($directory)) {
    echo json_encode(['error' => 'Invalid directory specified.']);
    exit;
}

$fileList = array();

// List directories (folders)
$folders = glob($directory . '*', GLOB_ONLYDIR);
foreach ($folders as $folder) {
    $folderName = basename($folder);
    $relativePath = str_replace($baseDir, '', $folder . '/');

    $fileList[] = array(
        'name' => $folderName,
        'url' => $relativePath, // Keep folder URL relative
        'type' => 'folder',
        'size' => null,
        'path' => $relativePath
    );
}

// List files
$files = glob($directory . '*.*');
foreach ($files as $file) {
    $fileName = basename($file);
    $relativePath = str_replace($baseDir, '', $file);
    $fileURL = generateURL($baseURL, $relativePath); // Generate full URL for files
    $fileSize = filesize($file);

    // Convert the file extension to lowercase
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $fileType = '';

    // Determine the file type based on the extension
    switch ($fileExtension) {
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            $fileType = 'image';
            break;
        case 'pdf':
            $fileType = 'application/pdf';
            break;
        case 'txt':
            $fileType = 'text/plain';
            break;
        case 'xlsx':
            $fileType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            break;
        case 'mp3':
            $fileType = 'audio/mpeg';
            break;
        case 'mp4':
            $fileType = 'video/mp4';
            break;
        default:
            $fileType = 'file';
            break;
    }

    // Create the file data array
    $fileData = array(
        'name' => $fileName,
        'url' => $fileURL, // Full URL for files
        'type' => $fileType,
        'size' => $fileSize,
        'path' => $relativePath
    );

    // If it's an image, add the imageName field
    if ($fileType === 'image') {
        $fileData['imageName'] = $fileName;
    }

    $fileList[] = $fileData;
}

// Output the file list as JSON
echo json_encode($fileList, JSON_PRETTY_PRINT);
?>
