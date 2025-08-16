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
    http_response_code(404);
    echo "File not found";
}
?>