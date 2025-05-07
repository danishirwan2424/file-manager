<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function logMessage($message) {
    file_put_contents('debug.log', date('Y-m-d H:i:s') . ' - ' . $message . "\n", FILE_APPEND);
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        logMessage("Directory does not exist: $dir");
        return false;
    }
    
    if (is_file($dir) || is_link($dir)) {
        return unlink($dir);
    }

    $items = array_diff(scandir($dir), ['.', '..']);
    foreach ($items as $item) {
        $itemPath = $dir . DIRECTORY_SEPARATOR . $item;
        if (!deleteDirectory($itemPath)) {
            return false;
        }
    }

    return rmdir($dir);
}

function deleteItem($relativePath) {
    $basePath = 'C:/xampp/htdocs/MyMCE/file-web/CDN/';
    $fullPath = $basePath . $relativePath;

    logMessage("Attempting to delete: $fullPath");

    if (is_file($fullPath)) {
        if (unlink($fullPath)) {
            logMessage("Successfully deleted file: $fullPath");
            return true;
        } else {
            logMessage("Failed to delete file: $fullPath");
            return false;
        }
    } elseif (is_dir($fullPath)) {
        if (deleteDirectory($fullPath)) {
            logMessage("Successfully deleted directory: $fullPath");
            return true;
        } else {
            logMessage("Failed to delete directory: $fullPath");
            return false;
        }
    } else {
        logMessage("Path does not exist: $fullPath");
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    logMessage('Received data: ' . print_r($data, true));

    if (!isset($data['filePaths']) || !is_array($data['filePaths'])) {
        logMessage('Invalid input data');
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }

    $filePaths = $data['filePaths'];
    $success = true;

    foreach ($filePaths as $path) {
        if (!deleteItem($path)) {
            $success = false;
        }
    }

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete one or more items']);
    }
} else {
    logMessage('Invalid request method');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
