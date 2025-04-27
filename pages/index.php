<?php
$dataFile = '/var/www/meme-archive/backend/data.json';
$uploadDir = '/uploads/'; // Changed to web-accessible path
$searchTag = isset($_GET['tag']) ? trim($_GET['tag']) : '';

// Load meme data
$memes = [];
if (file_exists($dataFile)) {
    $memes = json_decode(file_get_contents($dataFile), true) ?: [];
}

// Filter by tag if search is active
if ($searchTag !== '') {
    $memes = array_filter($memes, function($meme) use ($searchTag) {
        return in_array(strtolower($searchTag), array_map('strtolower', $meme['tags']));
    });
}

// Sort by upload date (newest first)
usort($memes, function($a, $b) {
    return strtotime($b['uploadDate']) - strtotime($a['uploadDate']);
});

// Collect all unique tags
$allTags = [];
foreach ($memes as $meme) {
    $allTags = array_merge($allTags, $meme['tags']);
}
$allTags = array_unique(array_map('strtolower', $allTags));
sort($allTags);
?>

<!DOCTYPE html>
<html>
<head>
    <?php include '../backend/meta.php'?>
    <title>Meme Archive</title>
    <style>
        
    </style>
</head>
<body>
    <div class="header">
        <h1>Meme Archive</h1>
        <a href="upload.php">Upload New Meme</a>
    </div>

    <div class="search-section">
        <h3>Tags:</h3>
        <div class="tag-cloud">
            <a href="index.php" <?php echo $searchTag === '' ? 'class="active-tag"' : ''; ?>>All</a>
            <?php foreach ($allTags as $tag): ?>
                <a href="?tag=<?php echo urlencode($tag); ?>" 
                   <?php echo $searchTag === $tag ? 'class="active-tag"' : ''; ?>>
                    <?php echo htmlspecialchars($tag); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="meme-grid">
        <?php foreach ($memes as $meme): ?>
            <div class="meme-card">
                <?php
                $fileExt = strtolower(pathinfo($meme['filename'], PATHINFO_EXTENSION));
                if (in_array($fileExt, ['mp4', 'webm'])) : ?>
                    <video controls>
                        <source src="<?php echo htmlspecialchars($uploadDir . $meme['filename']); ?>" 
                                type="video/<?php echo $fileExt; ?>">
                        Your browser does not support the video tag.
                    </video>
                <?php else : ?>
                    <img src="<?php echo htmlspecialchars($uploadDir . $meme['filename']); ?>" 
                         alt="<?php echo htmlspecialchars($meme['originalName']); ?>"
                         loading="lazy">
                <?php endif; ?>
                <div class="meme-info">
                    <div class="meme-tags">
                        <?php foreach ($meme['tags'] as $tag): ?>
                            <span><?php echo htmlspecialchars($tag); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <small>Uploaded: <?php echo htmlspecialchars($meme['uploadDate']); ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>