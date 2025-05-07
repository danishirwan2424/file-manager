<?php
$directory = 'C:/xampp/htdocs/MyMCE/file-web/CDN';
$items = [];

function getDirectoryContents($dir, &$items) {
    if ($handle = opendir($dir)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $path = $dir . '/' . $entry;
                $type = is_dir($path) ? 'folder' : mime_content_type($path);
                $items[] = ['name' => $entry, 'path' => $path, 'type' => $type];
                if (is_dir($path)) {
                    getDirectoryContents($path, $items);
                }
            }
        }
        closedir($handle);
    }
}

getDirectoryContents($directory, $items);
header('Content-Type: application/json');
echo json_encode($items);