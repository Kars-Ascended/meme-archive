<!DOCTYPE html>
<html>
<head>
    <?php include '../backend/meta.php'?>
    <title>Style Guide - Meme Archive</title>
    <link rel="stylesheet" href="/css/base2.css">
    <style>
        .style-section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
        }
        
        .style-section h2 {
            margin-bottom: 15px;
            color: var(--secondary-color);
        }
        
        .color-swatch {
            display: inline-block;
            width: 100px;
            height: 100px;
            margin: 10px;
            border-radius: var(--border-radius);
        }
        
        .swatch-label {
            text-align: center;
            margin-top: 5px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <h1>Meme Archive Style Guide</h1>

    <!-- Colors -->
    <div class="style-section">
        <h2>Colors</h2>
        <div class="color-swatch" style="background: var(--primary-color)">
            <div class="swatch-label">Primary</div>
        </div>
        <div class="color-swatch" style="background: var(--secondary-color); color: white;">
            <div class="swatch-label">Secondary</div>
        </div>
        <div class="color-swatch" style="background: var(--white)">
            <div class="swatch-label">White</div>
        </div>
        <div class="color-swatch" style="background: var(--black); color: white;">
            <div class="swatch-label">Black</div>
        </div>
    </div>

    <!-- Messages -->
    <div class="style-section">
        <h2>Messages</h2>
        <div class="message success">
            This is a success message
        </div>
        <div class="message error">
            This is an error message
        </div>
    </div>

    <!-- Tags -->
    <div class="style-section">
        <h2>Tags</h2>
        <div class="tag-cloud">
            <a href="#" class="active-tag">Active Tag</a>
            <a href="#">Normal Tag</a>
            <a href="#">Another Tag</a>
        </div>
    </div>

    <!-- Meme Card -->
    <div class="style-section">
        <h2>Meme Card</h2>
        <div class="meme-grid">
            <div class="meme-card">
                <button class="delete-btn">Delete</button>
                <img src="https://via.placeholder.com/300x200" alt="Example Meme">
                <div class="meme-info">
                    <div class="meme-tags">
                        <span>example</span>
                        <span>test</span>
                        <span>demo</span>
                    </div>
                    <small>Uploaded: 2025-04-28 12:00:00</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="style-section">
        <h2>Buttons</h2>
        <button type="submit">Submit Button</button>
        <button class="delete-btn" style="display: inline-block;">Delete Button</button>
    </div>

    <!-- Forms -->
    <div class="style-section">
        <h2>Form Elements</h2>
        <div class="form-group">
            <label for="test-input">Input Label:</label>
            <input type="text" id="test-input" placeholder="Placeholder text">
        </div>
        <div class="form-group">
            <label for="test-file">File Input:</label>
            <input type="file" id="test-file">
            <small>Helper text goes here</small>
        </div>
    </div>

    <!-- Layout -->
    <div class="style-section">
        <h2>Layout</h2>
        <div class="header">
            <h3>Header Example</h3>
            <a href="#">Header Link</a>
        </div>
        <div class="search-section">
            <h3>Search Section Example</h3>
            <p>Content goes here</p>
        </div>
    </div>
</body>
</html>