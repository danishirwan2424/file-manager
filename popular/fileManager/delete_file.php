<?php
// Include the deleteItem function
function deleteItem($relativePath) {
    $basePath = 'C:/xampp/htdocs/MyMCE/file-web/CDN/';
    $fullPath = $basePath . $relativePath; // Construct the full path

    if (is_file($fullPath)) {
        // Delete file
        return unlink($fullPath);
    } elseif (is_dir($fullPath)) {
        // Delete directory
        return deleteDirectory($fullPath);
    } else {
        return false; // Path does not exist
    }
}

// Helper function to recursively delete a directory
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return false;
    }
    
    if (is_file($dir) || is_link($dir)) {
        return unlink($dir);
    }

    if (is_dir($dir)) {
        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $itemPath = $dir . DIRECTORY_SEPARATOR . $item;
            if (!deleteDirectory($itemPath)) {
                return false;
            }
        }
        return rmdir($dir);
    }

    return false;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $filePaths = $data['filePaths']; // Get the array of file paths
    $success = true;

    foreach ($filePaths as $path) {
        // Attempt to delete each item
        if (!deleteItem($path)) {
            $success = false; // Mark as failed if any deletion fails
        }
    }

    // Return response based on success or failure
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete one or more items']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
