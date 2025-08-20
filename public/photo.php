<?php
// Simple photo access script
$filename = $_GET["file"] ?? "";
$type = $_GET["type"] ?? "profile";

if (empty($filename)) {
    http_response_code(404);
    echo "File not specified";
    exit;
}

$filepath = "../writable/uploads/$type/" . basename($filename);

if (file_exists($filepath)) {
    $mime = mime_content_type($filepath);
    header("Content-Type: $mime");
    header("Content-Disposition: inline; filename=\"" . basename($filename) . "\"");
    readfile($filepath);
} else {
    // Fallback ke default avatar jika file tidak ditemukan
    $defaultAvatar = "../assets/img/default-avatar.png";
    if (file_exists($defaultAvatar)) {
        $mime = mime_content_type($defaultAvatar);
        header("Content-Type: $mime");
        header("Content-Disposition: inline; filename=\"default-avatar.png\"");
        readfile($defaultAvatar);
    } else {
        http_response_code(404);
        echo "File not found and no default avatar available";
    }
}
?>