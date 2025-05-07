<?php
// Check if 'path' parameter is set
if (isset($_GET['path'])) {
    $path = $_GET['path'];

    // Construct the full path to the image
    $fullPath = 'C:/xampp/htdocs/MyMCE/file-web/CDN/' . $path;

    // Check if the file exists
    if (file_exists($fullPath)) {
        // Set the content type based on the file extension
        $fileInfo = pathinfo($fullPath);
        $extension = strtolower($fileInfo['extension']);
        
        switch ($extension) {
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
            default:
                header('Content-Type: application/octet-stream');
                break;
        }

        // Output the image
        readfile($fullPath);
    } else {
        // If file does not exist, send a 404 response
        http_response_code(404);
        echo 'File not found.';
    }
} else {
    // If 'path' parameter is not set, send a 400 response
    http_response_code(400);
    echo 'No path specified.';
}