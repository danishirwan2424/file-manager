<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the 'path' parameter is provided in the URL
if (isset($_GET['path'])) {
    // Sanitize the file path to prevent directory traversal attacks
    $filePath = realpath('C:/xampp/htdocs/MyMCE/file-web/CDN/' . basename($_GET['path']));

    // Check if the path exists and is within the CDN directory
    if ($filePath && strpos($filePath, realpath('C:/xampp/htdocs/MyMCE/file-web/CDN/')) === 0) {
        if (is_dir($filePath)) {
            // If the path is a directory, list its contents
            $contents = scandir($filePath);
            
            echo '<ul>';
            foreach ($contents as $item) {
                // Skip the special directories '.' and '..'
                if ($item !== '.' && $item !== '..') {
                    // Display the item as a link if it's a file
                    $itemPath = $filePath . DIRECTORY_SEPARATOR . $item;
                    $relativePath = str_replace(realpath('C:/xampp/htdocs/MyMCE/file-web/CDN/'), '', $itemPath);
                    
                    if (is_dir($itemPath)) {
                        // If the item is a folder, show it as a folder link
                        echo '<li><a href="?path=' . urlencode($relativePath) . '">' . $item . '/</a></li>';
                    } else {
                        // If the item is a file, show it as a file link
                        echo '<li><a href="?path=' . urlencode($relativePath) . '">' . $item . '</a></li>';
                    }
                }
            }
            echo '</ul>';
        } elseif (is_file($filePath)) {
            // If the path is a file, serve the file as before
            $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

            // Set the appropriate Content-Type header based on the file extension
            switch ($fileExtension) {
                case 'jpg':
                case 'jpeg':
                    header('Content-Type: image/jpeg');
                    break;
                case 'png':
                    header('Content-Type: image/png');
                    break;
                case 'gif':
                    header('Content-Type: image/gif');
                    break;
                case 'pdf':
                    header('Content-Type: application/pdf');
                    break;
                case 'txt':
                    header('Content-Type: text/plain');
                    break;
                case 'mp3':
                    header('Content-Type: audio/mpeg');
                    break;
                case 'mp4':
                    header('Content-Type: video/mp4');
                    break;
                case 'xls':
                    header('Content-Type: application/vnd.ms-excel');
                    break;
                default:
                    // If the file type is not supported, return an error
                    header('HTTP/1.1 415 Unsupported Media Type');
                    echo 'Unsupported file type.';
                    exit;
            }

            // Serve the file
            readfile($filePath);
        } else {
            // If the file does not exist or is outside the allowed directory, return a 404 error
            header('HTTP/1.1 404 Not Found');
            echo 'File not found.';
        }
    } else {
        // If the file does not exist or is outside the allowed directory, return a 404 error
        header('HTTP/1.1 404 Not Found');
        echo 'Path not found.';
    }
} else {
    // If no 'path' parameter is provided, return a 400 error
    header('HTTP/1.1 400 Bad Request');
    echo 'No file path specified.';
}
