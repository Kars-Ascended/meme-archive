<?php
// Load data from the JSON file if it exists
$tagDataFile = 'http://192.168.0.19/mnt/archive/memes/data.json';
$tagData = file_exists($tagDataFile) ? json_decode(file_get_contents($tagDataFile), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $originalName = basename($_FILES['image']['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
        
        // Generate unique filename if original already exists
        $imageName = $originalName;
        $counter = 0;
        while (file_exists('http://192.168.0.19/mnt/archive/memes/uploads/' . $imageName)) {
            $randomString = substr(md5(uniqid()), 0, 8);
            $imageName = $nameWithoutExt . '_' . $randomString . '.' . $extension;
        }
        
        $imagePath = 'http://192.168.0.19/mnt/archive/memes/uploads/' . $imageName;

        // Move the uploaded file to the uploads directory
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

        // Handle tags
        $tags = isset($_POST['tags']) ? explode(',', $_POST['tags']) : [];
        $tags = array_map('trim', $tags);  // Clean up spaces

        // Store the image and its tags in the $tagData array
        $tagData[$imageName] = $tags;

        // Save the updated data back to the JSON file
        file_put_contents($tagDataFile, json_encode($tagData, JSON_PRETTY_PRINT));
    }
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta.php'; ?>
</head>
<body>

<h1>Upload Meme + Tags</h1>
<form method="POST" enctype="multipart/form-data">
    <label for="image">Choose an image:</label>
    <input type="file" name="image" id="image" required><br><br>

    <label for="tags">Enter tags (comma separated):</label>
    <input type="text" name="tags" id="tags" placeholder="e.g. cat, funny, reaction" required><br><br>

    <button type="submit">Upload</button>
</form>

</body>
</html>