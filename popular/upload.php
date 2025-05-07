<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileInput'])) {
    $targetDir = "C:/xampp/htdocs/MyMCE/file-web/CDN/";
    $response = ['success' => false, 'message' => '', 'urls' => []];

    // Ensure target directory exists
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0755, true)) {
            echo json_encode(['success' => false, 'message' => 'Failed to create target directory.']);
            exit();
        }
    }

    // Define allowed file types and their MIME types
    $allowedFileTypes = [
    'jpg'  => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png'  => 'image/png',
    'gif'  => 'image/gif',
    'pdf'  => 'application/pdf',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'txt'  => 'text/plain',
    'mp3'  => 'audio/mpeg',
    'mp4'  => 'video/mp4',
    'zip'  => 'application/zip',
    'doc'  => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'ppt'  => 'application/vnd.ms-powerpoint',
    'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'rar'  => 'application/x-rar-compressed',
    '7z'   => 'application/x-7z-compressed',
    'csv'  => 'text/csv',
    'json' => 'application/json',
    'html' => 'text/html',
    'htm'  => 'text/html',
    'webp' => 'image/webp',
    'svg'  => 'image/svg+xml',
    'ico'  => 'image/x-icon',
    'mkv'  => 'video/x-matroska',
    'avi'  => 'video/x-msvideo',
    'mov'  => 'video/quicktime',
    'flv'  => 'video/x-flv',
    'wmv'  => 'video/x-ms-wmv',
    'ogg'  => 'audio/ogg',
    'wav'  => 'audio/wav'
];

    foreach ($_FILES['fileInput']['name'] as $key => $name) {
        $targetFile = $targetDir . basename($name);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $fileMimeType = mime_content_type($_FILES['fileInput']['tmp_name'][$key]);

        // Check if file type is allowed
        if (!array_key_exists($fileType, $allowedFileTypes) || $fileMimeType !== $allowedFileTypes[$fileType]) {
            $response['message'] = "Invalid file type.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($targetFile)) {
            $response['message'] = "File already exists.";
            $uploadOk = 0;
        }

        // Check file size (5MB limit)
        if ($_FILES['fileInput']['size'][$key] > 30000000) {
            $response['message'] = "File is too large.";
            $uploadOk = 0;
        }

        // Proceed with upload if no errors
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES['fileInput']['tmp_name'][$key], $targetFile)) {
                if ($fileType === 'zip') {
                    // Extract ZIP file
                    $zip = new ZipArchive;
                    if ($zip->open($targetFile) === TRUE) {
                        $zip->extractTo($targetDir);
                        $zip->close();

                        // Set permissions to 755 for folders and 644 for files
                        $iterator = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($targetDir, RecursiveDirectoryIterator::SKIP_DOTS),
                            RecursiveIteratorIterator::SELF_FIRST
                        );

                        foreach ($iterator as $item) {
                            if ($item->isDir()) {
                                chmod($item->getRealPath(), 0755);
                            } else {
                                chmod($item->getRealPath(), 0644);
                            }
                        }

                        unlink($targetFile); // Delete the ZIP file after extraction
                        $response['success'] = true;
                        $response['urls'][] = '/file-web/CDN/' . basename($name) . ' (extracted)';
                    } else {
                        $response['message'] = "Failed to unzip the file.";
                    }
                } else {
                    // Set file permissions
                    chmod($targetFile, 0644);
                    $response['success'] = true;
                    $response['urls'][] = '/file-web/CDN/' . basename($name);
                }
            } else {
                $response['message'] = "Error uploading file.";
            }
        } else {
            $response['success'] = false;
        }
    }

    echo json_encode($response);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
