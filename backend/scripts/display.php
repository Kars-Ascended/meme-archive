<?php
$dataFile = '/var/www/meme-archive/backend/data.json';
$uploadDir = '/uploads/';
$searchTag = isset($_GET['tag']) ? trim($_GET['tag']) : '';
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Load meme data
$memes = [];
if (file_exists($dataFile)) {
    $memes = json_decode(file_get_contents($dataFile), true) ?: [];
}

// Filter by search query if present
if ($searchQuery !== '') {
    $searchTerms = array_map('strtolower', explode(' ', $searchQuery));
    $memes = array_filter($memes, function($meme) use ($searchTerms) {
        $memeText = strtolower(implode(' ', $meme['tags']) . ' ' . $meme['originalName']);
        foreach ($searchTerms as $term) {
            if (strpos($memeText, $term) === false) {
                return false;
            }
        }
        return true;
    });
}

// Filter by tag if tag search is active
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

// Add this function before using $search
function normalizeSearchString($str) {
    // Remove special characters and convert to lowercase
    $str = strtolower($str);
    $str = preg_replace("/[^a-z0-9]/", "", $str);
    return $str;
}

// Modify the search condition in your query or filter logic
if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $normalizedSearch = normalizeSearchString($search);
    
    $memes = array_filter($memes, function($meme) use ($normalizedSearch) {
        // Normalize the filename and tags for comparison
        $normalizedFilename = normalizeSearchString($meme['originalName']);
        $normalizedTags = array_map('normalizeSearchString', $meme['tags']);
        
        // Check if normalized search term exists in normalized content
        return strpos($normalizedFilename, $normalizedSearch) !== false ||
               array_reduce($normalizedTags, function($carry, $tag) use ($normalizedSearch) {
                   return $carry || strpos($tag, $normalizedSearch) !== false;
               }, false);
    });
}