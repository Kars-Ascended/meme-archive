<?php
// Load data from the JSON file if it exists
$tagDataFile = '../backend/data.json';
$tagData = file_exists($tagDataFile) ? json_decode(file_get_contents($tagDataFile), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $originalName = basename($_FILES['image']['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);

        // Generate unique filename if original already exists
        $counter = 0;
        $uploadDir = '/mnt/archive/memes/uploads/'; // Filesystem path
        $imageName = $originalName;

        while (file_exists($uploadDir . $imageName)) {
            $randomString = substr(md5(uniqid()), 0, 8);
            $imageName = $nameWithoutExt . '_' . $randomString . '.' . $extension;
        }

        $destinationPath = $uploadDir . $imageName;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $destinationPath)) {
            // File moved successfully

            // Handle tags
            $tags = isset($_POST['tags']) ? explode(',', $_POST['tags']) : [];
            $tags = array_map('trim', $tags);  // Clean up spaces

            // Save the file info using the public URL for later use/display
            $publicUrl = 'http://192.168.0.19/mnt/archive/memes/uploads/' . $imageName;
            $tagData[$publicUrl] = $tags;

            // Save the updated data back to the JSON file
            file_put_contents($tagDataFile, json_encode($tagData, JSON_PRETTY_PRINT));
        } else {
            echo "Failed to move uploaded file.";
        }
    }
}
?>

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