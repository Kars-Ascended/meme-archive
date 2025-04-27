<?php
// Load data from the JSON file if it exists
$tagDataFile = '../backend/data.json';
$tagData = file_exists($tagDataFile) ? json_decode(file_get_contents($tagDataFile), true) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../backend/meta.php'; ?>
</head>
<body>

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

    foreach ($tagData as $file => $tags):
        // If there's a search query, filter files
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
        $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $isVideo = in_array($fileExt, ['mp4', 'webm', 'ogg']);
    ?>
        <div class="image-item">
            <?php if ($isVideo): ?>
                <video controls>
                    <source src="http://192.168.0.19/mnt/archive/memes/uploads/<?php echo htmlspecialchars($file); ?>" 
                            type="video/<?php echo $fileExt; ?>">
                    Your browser does not support the video tag.
                </video>
            <?php else: ?>
                <img src="http://192.168.0.19/mnt/archive/memes/uploads/<?php echo htmlspecialchars($file); ?>" 
                     alt="<?php echo htmlspecialchars($file); ?>">
            <?php endif; ?>
            <br>
            <strong>Tags:</strong> <?php echo htmlspecialchars(implode(', ', $tags)); ?>
        </div>
    <?php endforeach; ?>
    
    <?php if (!$hasResults && $searchQuery): ?>
        <p>No files found matching your search.</p>
    <?php endif; ?>
</div>



</body>
</html>
