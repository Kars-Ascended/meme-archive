<!DOCTYPE html>
<html>
<head>
    <?php include '../backend/meta.php'?>
    <?php include '../backend/scripts/display.php'; ?>
    <title>Meme Archive</title>
</head>
<body>
    <div class="header">
        <h1>Meme Archive</h1>
        <a href="upload.php">Upload New Meme</a>
    </div>

    <div class="search-section">
        <form method="GET" class="search-form">
            <input type="text" name="search" 
                   placeholder="Search memes..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
        <h3>Tags:</h3>
        <div class="tag-cloud">
            <a href="index.php" <?php echo $searchTag === '' ? 'class="active-tag"' : ''; ?>>All</a>
            <?php foreach ($allTags as $tag): ?>
                <a href="?tag=<?php echo urlencode(trim($tag, '"')); ?>" 
                   <?php echo $searchTag === $tag ? 'class="active-tag"' : ''; ?>>
                    <?php echo htmlspecialchars(trim($tag, '"')); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="toggle-section">
        <button onclick="toggleDeleteButtons()">Toggle Delete Buttons</button>
    </div>

    <div class="meme-grid">
        <?php foreach ($memes as $meme): ?>
            <div class="meme-card" data-filename="<?php echo htmlspecialchars($meme['filename']); ?>">
                <button class="delete-btn" onclick="deleteMeme('<?php echo htmlspecialchars($meme['filename']); ?>')">
                    Delete
                </button>
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
                            <?php if (strpos($tag, '"') === false): ?>
                                <span><?php echo htmlspecialchars(trim($tag, '"')); ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <small>Uploaded: <?php echo htmlspecialchars($meme['uploadDate']); ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="/js/deleteMeme.js"></script>
    <script src="/js/toggleDeleteButtons.js"></script>
</body>
</html>