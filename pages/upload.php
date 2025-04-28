<!DOCTYPE html>
<html>
<head>
    <?php include '../backend/meta.php'?>
    <?php include '../backend/scripts/upload.php'; ?>
    <title>Meme Upload</title>
</head>
<body>
    <h1>Upload a Meme</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="image">Select Media:</label>
            <input type="file" name="image" id="image" required 
                   accept="image/jpeg,image/png,image/gif,image/webp,video/mp4,video/webm,video/ogg">
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