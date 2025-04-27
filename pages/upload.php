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
            'image/jpeg', 'image/png', 'image/gif',
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
            $message = "Invalid file type! Only JPG, PNG and GIF allowed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Meme Upload</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 0 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .message { padding: 10px; margin-bottom: 15px; }
        .success { background-color: #dff0d8; border: 1px solid #d6e9c6; }
        .error { background-color: #f2dede; border: 1px solid #ebccd1; }
    </style>
</head>
<body>
    <?php if ($message): ?>
        <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <h1>Upload a Meme</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="image">Select Media:</label>
            <input type="file" name="image" id="image" required 
                   accept="image/jpeg,image/png,image/gif,video/mp4,video/webm,video/ogg">
            <small>Supported formats: JPG, PNG, GIF, MP4, WebM, OGG</small>
        </div>
        
        <div class="form-group">
            <label for="tags">Tags (comma-separated):</label>
            <input type="text" name="tags" id="tags" required placeholder="funny, cats, reaction">
        </div>
        
        <button type="submit">Upload</button>
    </form>
</body>
</html>