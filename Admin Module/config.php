<?php
// Admin Module Configuration & Session Management

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Admin credentials (in production, use a database)
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'tagum2026'); // Default password - change in production

// Destination data file
define('DESTINATIONS_FILE', dirname(__DIR__) . '/assets/data/destinations.json');

// Experience data file
define('EXPERIENCES_FILE', dirname(__DIR__) . '/assets/data/experiences.json');

// Cuisine data file
define('CUISINE_FILE', dirname(__DIR__) . '/assets/data/cuisine.json');

// Natural Wonders data file
define('NATURAL_WONDERS_FILE', dirname(__DIR__) . '/assets/data/natural-wonders.json');

// Cultural Sites data file
define('CULTURAL_SITES_FILE', dirname(__DIR__) . '/assets/data/cultural-sites.json');

// Festivals data file
define('FESTIVALS_FILE', dirname(__DIR__) . '/assets/data/festivals.json');

// Images directory


// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Redirect to login if not authenticated
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Load destinations from JSON file
function loadDestinations() {
    if (!file_exists(DESTINATIONS_FILE)) {
        return [];
    }
    
    $json = file_get_contents(DESTINATIONS_FILE);
    return json_decode($json, true) ?? [];
}

// Save destinations to JSON file
function saveDestinations($destinations) {
    $dir = dirname(DESTINATIONS_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents(DESTINATIONS_FILE, json_encode($destinations, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return true;
}

// Logout function
function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Validate image upload
function validateImageUpload($file) {
    if (!isset($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'File upload error'];
    }
    
    $fileName = basename($file['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    if (!in_array($fileExt, ALLOWED_EXTENSIONS)) {
        return ['success' => false, 'error' => 'Invalid file type. Allowed: JPG, PNG, GIF'];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'error' => 'File too large. Maximum: 10MB'];
    }
    
    return ['success' => true];
}

// Save uploaded image
function saveUploadedImage($file) {
    $validation = validateImageUpload($file);
    if (!$validation['success']) {
        return $validation;
    }
    
    // Create images directory if it doesn't exist
    if (!is_dir(IMAGES_DIR)) {
        mkdir(IMAGES_DIR, 0755, true);
    }
    
    // Generate unique filename
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileName = 'destination_' . time() . '_' . uniqid() . '.' . $fileExt;
    $filePath = IMAGES_DIR . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['success' => true, 'fileName' => $fileName, 'path' => IMAGES_URL . $fileName];
    }
    
    return ['success' => false, 'error' => 'Failed to save image'];
}

// Delete image
function deleteImage($fileName) {
    $filePath = IMAGES_DIR . $fileName;
    if (file_exists($filePath)) {
        unlink($filePath);
        return true;
    }
    return false;
}

// ==================== EXPERIENCE MANAGEMENT FUNCTIONS ====================

// Load experiences from JSON file
function loadExperiences() {
    if (!file_exists(EXPERIENCES_FILE)) {
        return [];
    }
    
    $json = file_get_contents(EXPERIENCES_FILE);
    return json_decode($json, true) ?? [];
}

// Save experiences to JSON file
function saveExperiences($experiences) {
    $dir = dirname(EXPERIENCES_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents(EXPERIENCES_FILE, json_encode($experiences, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return true;
}

// Save uploaded experience image
function saveExperienceImage($file) {
    $validation = validateImageUpload($file);
    if (!$validation['success']) {
        return $validation;
    }
    
    // Create experiences images directory if it doesn't exist
    if (!is_dir(EXPERIENCES_IMAGES_DIR)) {
        mkdir(EXPERIENCES_IMAGES_DIR, 0755, true);
    }
    
    // Generate unique filename
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileName = 'experience_' . time() . '_' . uniqid() . '.' . $fileExt;
    $filePath = EXPERIENCES_IMAGES_DIR . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['success' => true, 'fileName' => $fileName, 'path' => EXPERIENCES_IMAGES_URL . $fileName];
    }
    
    return ['success' => false, 'error' => 'Failed to save image'];
}

// Delete experience image
function deleteExperienceImage($fileName) {
    $filePath = EXPERIENCES_IMAGES_DIR . $fileName;
    if (file_exists($filePath)) {
        unlink($filePath);
        return true;
    }
    return false;
}

// ==================== CUISINE MANAGEMENT FUNCTIONS ====================

// Load cuisine from JSON file
function loadCuisine() {
    if (!file_exists(CUISINE_FILE)) {
        return [];
    }
    
    $json = file_get_contents(CUISINE_FILE);
    return json_decode($json, true) ?? [];
}

// Save cuisine to JSON file
function saveCuisine($cuisine) {
    $dir = dirname(CUISINE_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents(CUISINE_FILE, json_encode($cuisine, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return true;
}

// Save uploaded cuisine image
function saveCuisineImage($file) {
    $validation = validateImageUpload($file);
    if (!$validation['success']) {
        return $validation;
    }
    
    // Create cuisine images directory if it doesn't exist
    if (!is_dir(CUISINE_IMAGES_DIR)) {
        mkdir(CUISINE_IMAGES_DIR, 0755, true);
    }
    
    // Generate unique filename
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileName = 'cuisine_' . time() . '_' . uniqid() . '.' . $fileExt;
    $filePath = CUISINE_IMAGES_DIR . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['success' => true, 'fileName' => $fileName, 'path' => CUISINE_IMAGES_URL . $fileName];
    }
    
    return ['success' => false, 'error' => 'Failed to save image'];
}

// Delete cuisine image
function deleteCuisineImage($fileName) {
    $filePath = CUISINE_IMAGES_DIR . $fileName;
    if (file_exists($filePath)) {
        unlink($filePath);
        return true;
    }
    return false;
}

// ==================== NATURAL WONDERS MANAGEMENT FUNCTIONS ====================

// Load natural wonders from JSON file
function loadNaturalWonders() {
    if (!file_exists(NATURAL_WONDERS_FILE)) {
        return [];
    }
    
    $json = file_get_contents(NATURAL_WONDERS_FILE);
    return json_decode($json, true) ?? [];
}

// Save natural wonders to JSON file
function saveNaturalWonders($naturalWonders) {
    $dir = dirname(NATURAL_WONDERS_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents(NATURAL_WONDERS_FILE, json_encode($naturalWonders, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return true;
}

// Save uploaded natural wonders image
function saveNaturalWonderImage($file) {
    $validation = validateImageUpload($file);
    if (!$validation['success']) {
        return $validation;
    }
    
    // Create natural wonders images directory if it doesn't exist
    if (!is_dir(NATURAL_WONDERS_IMAGES_DIR)) {
        mkdir(NATURAL_WONDERS_IMAGES_DIR, 0755, true);
    }
    
    // Generate unique filename
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileName = 'natural_' . time() . '_' . uniqid() . '.' . $fileExt;
    $filePath = NATURAL_WONDERS_IMAGES_DIR . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['success' => true, 'fileName' => $fileName, 'path' => NATURAL_WONDERS_IMAGES_URL . $fileName];
    }
    
    return ['success' => false, 'error' => 'Failed to save image'];
}

// Delete natural wonder image
function deleteNaturalWonderImage($fileName) {
    $filePath = NATURAL_WONDERS_IMAGES_DIR . $fileName;
    if (file_exists($filePath)) {
        unlink($filePath);
        return true;
    }
    return false;
}

// ==================== CULTURAL SITES MANAGEMENT FUNCTIONS ====================

// Load cultural sites from JSON file
function loadCulturalSites() {
    if (!file_exists(CULTURAL_SITES_FILE)) {
        return [];
    }
    
    $json = file_get_contents(CULTURAL_SITES_FILE);
    return json_decode($json, true) ?? [];
}

// Save cultural sites to JSON file
function saveCulturalSites($culturalSites) {
    $dir = dirname(CULTURAL_SITES_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents(CULTURAL_SITES_FILE, json_encode($culturalSites, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return true;
}

// Save uploaded cultural sites image
function saveCulturalSiteImage($file) {
    $validation = validateImageUpload($file);
    if (!$validation['success']) {
        return $validation;
    }
    
    // Create cultural sites images directory if it doesn't exist
    if (!is_dir(CULTURAL_SITES_IMAGES_DIR)) {
        mkdir(CULTURAL_SITES_IMAGES_DIR, 0755, true);
    }
    
    // Generate unique filename
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileName = 'cultural_' . time() . '_' . uniqid() . '.' . $fileExt;
    $filePath = CULTURAL_SITES_IMAGES_DIR . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['success' => true, 'fileName' => $fileName, 'path' => CULTURAL_SITES_IMAGES_URL . $fileName];
    }
    
    return ['success' => false, 'error' => 'Failed to save image'];
}

// Delete cultural site image
function deleteCulturalSiteImage($fileName) {
    $filePath = CULTURAL_SITES_IMAGES_DIR . $fileName;
    if (file_exists($filePath)) {
        unlink($filePath);
        return true;
    }
    return false;
}

// ==================== FESTIVALS MANAGEMENT FUNCTIONS ====================

// Load festivals from JSON file
function loadFestivals() {
    if (!file_exists(FESTIVALS_FILE)) {
        return [];
    }
    
    $json = file_get_contents(FESTIVALS_FILE);
    return json_decode($json, true) ?? [];
}

// Save festivals to JSON file
function saveFestivals($festivals) {
    $dir = dirname(FESTIVALS_FILE);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    file_put_contents(FESTIVALS_FILE, json_encode($festivals, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return true;
}

// Save uploaded festivals image
function saveFestivalImage($file) {
    $validation = validateImageUpload($file);
    if (!$validation['success']) {
        return $validation;
    }
    
    // Create festivals images directory if it doesn't exist
    if (!is_dir(FESTIVALS_IMAGES_DIR)) {
        mkdir(FESTIVALS_IMAGES_DIR, 0755, true);
    }
    
    // Generate unique filename
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileName = 'festival_' . time() . '_' . uniqid() . '.' . $fileExt;
    $filePath = FESTIVALS_IMAGES_DIR . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return ['success' => true, 'fileName' => $fileName, 'path' => FESTIVALS_IMAGES_URL . $fileName];
    }
    
    return ['success' => false, 'error' => 'Failed to save image'];
}

// Delete festival image
function deleteFestivalImage($fileName) {
    $filePath = FESTIVALS_IMAGES_DIR . $fileName;
    if (file_exists($filePath)) {
        unlink($filePath);
        return true;
    }
    return false;
}

?>
