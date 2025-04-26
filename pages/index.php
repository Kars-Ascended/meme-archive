<?php
// Load data from the JSON file if it exists
$tagDataFile = '../uploads/data.json';
$tagData = file_exists($tagDataFile) ? json_decode(file_get_contents($tagDataFile), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = basename($_FILES['image']['name']);
        $imagePath = '../uploads/memes/' . $imageName;

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

<!-- Add search form -->
<div class="search-section">
    <h2>Search Memes by Tags</h2>
    <form method="GET" class="search-form">
        <input type="text" name="search" id="search" 
               placeholder="Search by tags (e.g. funny, cat)" 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
    </form>
</div>

<h2>Uploaded Memes</h2>
<div class="gallery">
    <?php
    $searchQuery = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
    $hasResults = false;

    foreach ($tagData as $img => $tags):
        // If there's a search query, filter images
        if ($searchQuery) {
            $searchTags = array_map('trim', explode(',', $searchQuery));
            $matchFound = false;
            
            foreach ($searchTags as $searchTag) {
                if (array_filter($tags, function($tag) use ($searchTag) {
                    return stripos(strtolower($tag), $searchTag) !== false;
                })) {
                    $matchFound = true;
                    break;
                }
            }
            
            if (!$matchFound) continue;
        }
        
        $hasResults = true;
    ?>
        <div class="image-item">
            <img src="uploads/memes/<?php echo htmlspecialchars($img); ?>" 
                 alt="<?php echo htmlspecialchars($img); ?>" 
                 width="200">
            <br>
            <strong>Tags:</strong> <?php echo htmlspecialchars(implode(', ', $tags)); ?>
        </div>
    <?php endforeach; ?>
    
    <?php if (!$hasResults && $searchQuery): ?>
        <p>No images found matching your search.</p>
    <?php endif; ?>
</div>



</body>
</html>
