<?php
$uploadDir = '/mnt/archive/memes/uploads/';
$dataFile = '../backend/data.json';
$message = '';

// Create directories if they don't exist
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && isset($_POST['tags'])) {
        $file = $_FILES['image'];
        $tags = array_map('trim', explode(',', $_POST['tags']));
        
        // Validate file
        $allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'video/mp4', 'video/webm', 'video/ogg'
        ];
        if (in_array($file['type'], $allowedTypes)) {
            // Generate safe filename
            $filename = uniqid() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "", $file['name']);
            $filepath = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                // Load existing data
                $jsonData = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
                if (!is_array($jsonData)) $jsonData = [];
                
                // Add new entry
                $jsonData[] = [
                    'filename' => $filename,
                    'originalName' => $file['name'],
                    'tags' => $tags,
                    'uploadDate' => date('Y-m-d H:i:s'),
                    'filesize' => $file['size']
                ];
                
                // Save JSON data
                if (file_put_contents($dataFile, json_encode($jsonData, JSON_PRETTY_PRINT))) {
                    $message = "File uploaded successfully!";
                } else {
                    $message = "Error saving metadata!";
                }
            } else {
                $message = "Error uploading file!";
            }
        } else {
            $message = "Invalid file type! Only JPG, PNG, GIF, and WebP allowed.";
        }
    }
}
?>

<?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>