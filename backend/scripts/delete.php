<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Method not allowed']));
}

$dataFile = '/var/www/meme-archive/backend/data.json';
$uploadDir = '/mnt/archive/memes/uploads/';
$filename = isset($_POST['filename']) ? $_POST['filename'] : '';

if (empty($filename)) {
    http_response_code(400);
    die(json_encode(['error' => 'No filename provided']));
}

// Prevent directory traversal
$filename = basename($filename);
$filepath = $uploadDir . $filename;

// Load JSON data
$jsonData = json_decode(file_get_contents($dataFile), true) ?: [];

// Find and remove the meme from JSON
$found = false;
$jsonData = array_filter($jsonData, function($meme) use ($filename, &$found) {
    if ($meme['filename'] === $filename) {
        $found = true;
        return false;
    }
    return true;
});

if (!$found) {
    http_response_code(404);
    die(json_encode(['error' => 'File not found in database']));
}

// Delete the actual file
if (file_exists($filepath)) {
    if (!unlink($filepath)) {
        http_response_code(500);
        die(json_encode(['error' => 'Could not delete file']));
    }
}

// Save updated JSON data
if (!file_put_contents($dataFile, json_encode(array_values($jsonData), JSON_PRETTY_PRINT))) {
    http_response_code(500);
    die(json_encode(['error' => 'Could not update database']));
}

echo json_encode(['success' => true]);
?>