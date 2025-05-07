<?php
// file.php
$path = isset($_GET['path']) ? $_GET['path'] : '';
$fullPath = $_SERVER['DOCUMENT_ROOT'] . '/MyMCE/file-web/CDN/' . $path;

if (file_exists($fullPath)) {
    header('Content-Type: text/plain');
    echo file_get_contents($fullPath);
} else {
    http_response_code(404);
    echo 'File not found.';
}